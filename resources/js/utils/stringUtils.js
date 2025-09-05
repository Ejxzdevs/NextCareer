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

/**
 * Extracts a readable name from an email.
 * - Removes domain (@gmail.com, etc.)
 * - Capitalizes first letter
 * - Turns dots into spaces
 */
export function formatNameFromEmail(email) {
  if (!email) return ''

  const username = email.split('@')[0] // before @
  const withSpaces = username.replace(/\./g, ' ') // john.doe -> john doe
  return withSpaces.charAt(0).toUpperCase() + withSpaces.slice(1)
}

/**
 * Capitalizes the first letter of a string.
 *
 * @param {string} str - The input string.
 * @returns {string} The string with the first letter capitalized.
 */
export function capitalizeFirstLetter(str) {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
}