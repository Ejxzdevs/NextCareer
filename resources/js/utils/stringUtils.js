// utils.js

/**
 * Processes a comma-separated string of skills into a formatted,
 * uppercase string, trimming whitespace and removing empty entries.
 *
 * @param {string} skillsString - The raw comma-separated string of skills.
 * @returns {string} A comma-separated string of uppercase, trimmed skills.
 */
export function formatSkills(skillsString) {
  if (typeof skillsString !== 'string') {
    return '';
  }

  return skillsString
    .split(',')         // Split the string into an array by commas
    .map((s) => s.trim()) // Trim whitespace from each skill
    .filter((s) => s)   // Remove any empty strings resulting from extra commas
    .join(', ')         // Join the skills back into a comma-separated string
    .toUpperCase();     // Convert the entire string to uppercase
}