/**
 * scripts/fetch-fpl-images.js
 *
 * Fetches bootstrap-static data from the FPL API and downloads:
 *  - player images into public/images/players
 *  - team badges into public/images/teams
 *
 * Run: node scripts/fetch-fpl-images.js
 *
 * Notes:
 *  - Uses node-fetch v2 (install with `npm install node-fetch@2`).
 *  - Downloads happen sequentially to avoid hammering remote server.
 *  - Retries failed downloads up to `MAX_RETRIES`.
 */

const fetch = require('node-fetch'); // v2 style
const fs = require('fs').promises;
const path = require('path');

// === Config ===
const BOOTSTRAP_URL = 'https://fantasy.premierleague.com/api/bootstrap-static/';
const PLAYER_IMG_TEMPLATE =
  (code) => `https://resources.premierleague.com/premierleague25/photos/players/110x140/${code}.png`;
const TEAM_BADGE_TEMPLATE =
  (code) => `https://resources.premierleague.com/premierleague/badges/70/t${code}.png`;

// Where to save files (relative to project root)
const OUTPUT_PLAYERS_DIR = path.join(__dirname, '..', 'public', 'images', 'players');
const OUTPUT_TEAMS_DIR = path.join(__dirname, '..', 'public', 'images', 'teams');

// Download behaviour
const MAX_RETRIES = 3;
const RETRY_DELAY_MS = 800; // wait between retries
const TIMEOUT_MS = 20000; // fetch timeout per request

// Helper: sleep
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

// Helper: fetch with timeout
async function fetchWithTimeout(url, timeoutMs) {
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), timeoutMs);
  try {
    const res = await fetch(url, { signal: controller.signal });
    clearTimeout(id);
    return res;
  } catch (err) {
    clearTimeout(id);
    throw err;
  }
}

// Helper: download a single URL to disk with retries
async function downloadToFile(url, filepath, maxRetries = MAX_RETRIES) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      const res = await fetchWithTimeout(url, TIMEOUT_MS);
      if (!res.ok) {
        // 404 or similar
        throw new Error(`HTTP ${res.status} ${res.statusText}`);
      }
      const buffer = await res.buffer();
      await fs.writeFile(filepath, buffer);
      return { ok: true };
    } catch (err) {
      const last = attempt === maxRetries;
      console.warn(`[${last ? 'FINAL' : 'RETRY'}] Failed to download ${url} (attempt ${attempt}): ${err.message}`);
      if (last) {
        return { ok: false, error: err.message };
      }
      await sleep(RETRY_DELAY_MS);
    }
  }
}

// Ensure directory exists
async function ensureDir(dir) {
  try {
    await fs.mkdir(dir, { recursive: true });
  } catch (err) {
    // ignore if exists
  }
}

// Clean filename helper (remove bad chars)
function cleanName(name) {
  return name.replace(/[^a-z0-9_\-\.]/gi, '_').replace(/_+/g, '_');
}

// Main
(async () => {
  console.log('Starting FPL images fetcher...');
  try {
    // 1) Ensure output folders exist
    await ensureDir(OUTPUT_PLAYERS_DIR);
    await ensureDir(OUTPUT_TEAMS_DIR);

    // 2) Fetch bootstrap data
    console.log('Fetching bootstrap data...');
    const bootRes = await fetchWithTimeout(BOOTSTRAP_URL, TIMEOUT_MS);
    if (!bootRes.ok) throw new Error(`bootstrap fetch failed: ${bootRes.status} ${bootRes.statusText}`);
    const data = await bootRes.json();

    // 3) Download players
    const players = data.elements || [];
    console.log(`Found ${players.length} players. Starting download (sequential)...`);

    for (const p of players) {
      // p.code is the ID used by the resources CDN, p.id is the FPL element id (DB id)
      const code = p.code || p.id;
      const filename = `${p.id}_${cleanName(p.web_name || `${p.first_name}_${p.second_name}`)}.png`;
      const outPath = path.join(OUTPUT_PLAYERS_DIR, filename);
      const url = PLAYER_IMG_TEMPLATE(code);

      // skip if already exists
      try {
        await fs.access(outPath);
        console.log(`Skip (exists): ${filename}`);
        continue;
      } catch (_) {
        // file does not exist -> download
      }

      process.stdout.write(`Downloading player ${p.web_name || p.first_name} ... `);
      const result = await downloadToFile(url, outPath);
      if (result.ok) {
        console.log('OK');
      } else {
        console.log(`FAILED (${result.error})`);
      }

      // small delay between downloads (politeness)
      await sleep(120);
    }

    // 4) Download teams
    const teams = data.teams || [];
    console.log(`Found ${teams.length} teams. Downloading team badges...`);

    for (const t of teams) {
      // team.code or t.id used in CDN paths
      const code = t.code || t.id;
      const filename = `${t.id}_${cleanName(t.name)}.png`;
      const outPath = path.join(OUTPUT_TEAMS_DIR, filename);
      const url = TEAM_BADGE_TEMPLATE(code);

      try {
        await fs.access(outPath);
        console.log(`Skip (exists): ${filename}`);
        continue;
      } catch (_) { /* not exists */ }

      process.stdout.write(`Downloading team ${t.name} ... `);
      const result = await downloadToFile(url, outPath);
      if (result.ok) {
        console.log('OK');
      } else {
        console.log(`FAILED (${result.error})`);
      }

      await sleep(120);
    }

    console.log('All done. Images saved to:');
    console.log(`  Players -> ${OUTPUT_PLAYERS_DIR}`);
    console.log(`  Teams   -> ${OUTPUT_TEAMS_DIR}`);
  } catch (err) {
    console.error('Fatal error:', err);
    process.exit(1);
  }
})();
