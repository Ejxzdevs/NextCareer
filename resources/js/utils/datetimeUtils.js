/**
 * Converts a given date into a human-readable "time ago" format (e.g., "5 minutes ago", "2 days ago").
 * Returns "Just now" if the date is very recent or invalid.
 *
 * @param {string|Date} date - The date to format, can be a string or Date object.
 * @returns {string} The formatted time ago string.
 */
export function formatTimeAgo(date) {
    if (!date) return "";
    const now = new Date();
    const past = new Date(date);
    const seconds = Math.floor((now - past) / 1000);

    const intervals = [
        { label: "year", seconds: 31536000 },
        { label: "month", seconds: 2592000 },
        { label: "day", seconds: 86400 },
        { label: "hour", seconds: 3600 },
        { label: "minute", seconds: 60 },
        { label: "second", seconds: 1 },
    ];

    for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count >= 1) {
            return `${count} ${interval.label}${count > 1 ? "s" : ""} ago`;
        }
    }

    return "Just now";
}

/**
 * Formats a given date string into a localized short date format.
 * @param {string} date - The date string to format.
 * @returns {string} The formatted date string, or 'N/A' if null/empty.
 */
export function formatDate(date) {
    if (!date) return "N/A";
    const d = new Date(date);
    return d.toLocaleDateString(undefined, {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
}

export function formatDateTime(timestamp) {
    if (!timestamp) return null; // handle null/undefined

    return new Date(timestamp).toLocaleString("en-US", {
        month: "numeric",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: true,
    });
}
