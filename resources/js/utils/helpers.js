export const imageFile = (fpl_id, name) => {
    const sanitizedName = name
        .toLowerCase()
        .replace(/[^a-z0-9]/g, "_");
    const capitalisedName = sanitizedName.charAt(0).toUpperCase() + sanitizedName.slice(1);
    return `/images/players/${fpl_id}_${capitalisedName}.png`;
};

export const teamImage = (team_id, team_name) => `/images/teams/${team_id}_${safeName(team_name)}.png`;
const safeName = (team_name) => team_name.replace(/[\s']+/g, '_');