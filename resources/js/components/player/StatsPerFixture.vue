<script setup>
defineProps(['fixtures', 'performances']);

const safeName = (team_name) => team_name.replace(/[\s']/g, '_');
const teamImage = (team_id, team_name) => `/images/teams/${team_id}_${safeName(team_name)}.png`;
</script>

<template>
    <div class="mx-auto">
        <div v-for="(f, index) in fixtures" class="border border-gray-400/20 rounded-lg my-3 p-3">
            <div class="flex gap-3 justify-center items-center">
                <div class="flex flex-col justify-center items-center gap-2">
                    <img :src="teamImage(f.fixture.home_team.id, f.fixture.home_team.name)" alt="Player Team"
                        class="size-5 sm:size-8">
                </div>
                <p>vs</p>
                <div class="flex flex-col justify-center items-center gap-2">
                    <img :src="teamImage(f.fixture.away_team.id, f.fixture.away_team.name)" alt="Player Team"
                        class="size-5 sm:size-8">
                </div>
            </div>
            <div class="flex items-center my-3 overflow-x-auto text-center">

                <table class="border text-[8px] sm:text-base">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">GW</th>
                            <th class="px-4 py-2 border">MIN</th>
                            <th class="px-4 py-2 border">G</th>
                            <th class="px-4 py-2 border">A</th>
                            <th class="px-4 py-2 border">CS</th>
                            <th class="px-4 py-2 border">GC</th>
                            <th class="px-4 py-2 border">OG</th>
                            <th class="px-4 py-2 border">PS</th>
                            <th class="px-4 py-2 border">PM</th>
                            <th class="px-4 py-2 border">YC</th>
                            <th class="px-4 py-2 border">RC</th>
                            <th class="px-4 py-2 border">SV</th>
                            <th class="px-4 py-2 border">BON</th>
                            <th class="px-4 py-2 border">BPS</th>
                            <th class="px-4 py-2 border">INF</th>
                            <th class="px-4 py-2 border">CRE</th>
                            <th class="px-4 py-2 border">THR</th>
                            <th class="px-4 py-2 border">ICT</th>
                            <th class="px-4 py-2 border">CBI</th>
                            <th class="px-4 py-2 border">REC</th>
                            <th class="px-4 py-2 border">TCK</th>
                            <th class="px-4 py-2 border">DEF</th>
                            <th class="px-4 py-2 border">ST</th>
                            <th class="px-4 py-2 border">xG</th>
                            <th class="px-4 py-2 border">xA</th>
                            <th class="px-4 py-2 border">xGI</th>
                            <th class="px-4 py-2 border">xGC</th>
                            <th class="px-4 py-2 border">PTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">{{ performances[index].gameweek_id }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].minutes }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].goals_scored }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].assists }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].clean_sheets }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].goals_conceded }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].own_goals }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].penalties_saved }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].penalties_missed }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].yellow_cards }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].red_cards }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].saves }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].bonus }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].bps }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].influence }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].creativity }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].threat }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].ict_index }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].clearances_blocks_interceptions
                            }}
                            </td>
                            <td class="px-4 py-2 border">{{ performances[index].recoveries }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].tackles }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].defensive_contribution }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].starts }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].expected_goals }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].expected_assists }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].expected_goal_involvements }}
                            </td>
                            <td class="px-4 py-2 border">{{ performances[index].expected_goals_conceded }}</td>
                            <td class="px-4 py-2 border">{{ performances[index].total_points }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>