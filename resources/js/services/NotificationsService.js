// services/NotificationService.js
// -----------------------------------
// Notification Service
// Manages fetching and updating notifications
// -----------------------------------

import axios from "axios";

// API endpoints
const API_ENDPOINTS = {
    FETCH_USER_NOTIFICATIONS: route("notifications.read"),
    MARK_ALL_AS_READ: route("notifications.markAllAsViewed"),
};

/**
 * Fetch notifications for the current user
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const fetchUserNotifications = async () => {
    try {
        const response = await axios.get(
            API_ENDPOINTS.FETCH_USER_NOTIFICATIONS
        );
        return { data: response.data.notifications || [], error: null };
    } catch (err) {
        console.error("Error fetching notifications:", err);

        let errorMessage = "Network error while loading notifications.";
        if (err.response) {
            errorMessage = `Error ${err.response.status}: ${
                err.response.data?.message || "Server error"
            }`;
        } else if (err.request) {
            errorMessage = "No response from server. Check connection.";
        }

        return { data: null, error: errorMessage };
    }
};

/**
 * Mark all notifications as read
 * @returns {Promise<{ success: boolean, error: string | null }>}
 */
export const markAllAsRead = async () => {
    try {
        await axios.put(API_ENDPOINTS.MARK_ALL_AS_READ);
        return { success: true, error: null };
    } catch (error) {
        console.error("Error marking notifications as read:", error);

        let errorMessage = "Failed to mark all as read.";
        if (error.response) {
            errorMessage = `Error ${error.response.status}: ${
                error.response.data?.message || "Server error"
            }`;
        } else if (error.request) {
            errorMessage = "No response from server. Check connection.";
        }

        return { success: false, error: errorMessage };
    }
};
