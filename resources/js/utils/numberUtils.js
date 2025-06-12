
/**
 * Converts a numeric value to a formatted currency string with a dollar sign and commas.
 * Returns "$0" for zero, and "N/A" if the input is null, undefined, or not a valid number.
 *
 * @param {number|null|undefined} amount - The numeric value to format.
 * @returns {string} The formatted currency string.
 */

export function formatCurrency(amount) {
  if (amount === null || typeof amount === 'undefined' || isNaN(amount)) {
    return 'N/A';
  }
  // Convert to number once
  const numAmount = Number(amount);
  return numAmount === 0 ? '$0' : `$${numAmount.toLocaleString()}`;
}