// services/NotificationService.js
import axios from "axios";

// Since Ziggy is global, 'route()' is available here without explicit import.
const API_ENDPOINTS = {
    FETCH_NOTIFICATIONS: route("employer.notifications.read"),
    MARK_ALL_AS_READ: route("notifications.markAllAsViewed"),
};

/**
 * Fetches raw notification data from the API.
 * This method directly interacts with the API endpoint for raw notifications.
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const fetchEmployerNotifications = async () => {
    try {
        const response = await axios.get(API_ENDPOINTS.FETCH_NOTIFICATIONS);
        return { data: response.data.notifications || [], error: null };
    } catch (err) {
        console.error(
            "NotificationService Error: Failed to fetch notifications (fetchApiData):",
            err
        );
        let errorMessage =
            "Failed to load notifications due to a network error.";

        if (err.response) {
            console.error("Response Data:", err.response.data);
            console.error("Response Status:", err.response.status);
            errorMessage = `Failed to load notifications: ${
                err.response.status
            } - ${err.response.data?.message || "Server error"}`;
        } else if (err.request) {
            errorMessage =
                "No response from server. Please check your internet connection.";
        }
        return { data: null, error: errorMessage };
    }
};

/**
 * Sends a request to the API to mark all applications as viewed.
 * This method directly interacts with the API endpoint for marking as read.
 * @returns {Promise<{ success: boolean, error: string | null }>} - Returns an object indicating success or an error message.
 */
export const markAllAsRead = async () => {
    try {
        await axios.put(API_ENDPOINTS.MARK_ALL_AS_READ);
        return { success: true, error: null };
    } catch (error) {
        console.error(
            "NotificationService Error: Failed to mark all as read (markAllAsRead):",
            error
        );
        let errorMessage = "Failed to mark all as read. Please try again.";

        if (error.response) {
            errorMessage = `Failed to mark all as read: ${
                error.response.status
            } - ${error.response.data?.message || "Server error"}`;
        } else if (error.request) {
            errorMessage =
                "No response from server. Please check your internet connection.";
        }
        return { success: false, error: errorMessage };
    }
};
