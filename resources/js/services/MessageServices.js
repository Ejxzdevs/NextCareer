import axios from 'axios';

// Create a pre-configured Axios instance with default headers and credentials
const api = axios.create({
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
  withCredentials: true,
});

const API_ENDPOINTS = {
  FETCH_INBOX: route('messages.notifications.read'),
  SEND_MESSAGE: route('messages.store'),
  FETCH_CONVERSATION: (userId) => route('messages.conversation.read', userId),
  MARK_ALL_READ: route('messages.markAllAsRead'),
};

/**
 * Formats API errors into a user-friendly message.
 * @param {any} error - Axios error object
 * @param {string} defaultMessage - Default fallback message
 * @returns {string} - User-friendly error message
 */
const formatApiError = (error, defaultMessage = 'An error occurred') => {
  if (error.response) {
    const status = error.response.status;
    const message = error.response.data?.message || defaultMessage;
    return `${defaultMessage}: ${status} - ${message}`;
  } else if (error.request) {
    return 'No response from server. Please check your internet connection.';
  } else {
    return `${defaultMessage}: ${error.message}`;
  }
};

/**
 * Fetches inbox messages from the API.
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const getInboxApi = async () => {
  try {
    const response = await api.get(API_ENDPOINTS.FETCH_INBOX);
    return { data: response.data || [], error: null };
  } catch (err) {
    return { data: null, error: formatApiError(err, 'Failed to load inbox') };
  }
};

/**
 * Sends a message to a specific user.
 * @param {number} receiverId - ID of the recipient
 * @param {string} messageContent - The message body
 * @returns {Promise<{ data: string | null, error: string | object | null }>}
 */
export const sendMessageApi = async (receiverId, messageContent) => {
  try {
    const response = await api.post(API_ENDPOINTS.SEND_MESSAGE, {
      receiver_id: receiverId,
      message_content: messageContent,
    });
    return { data: response.data.message, error: null };
  } catch (error) {
    if (error.response?.status === 422) {
      return { data: null, error: error.response.data.errors };
    }
    return { data: null, error: formatApiError(error, 'Failed to send message') };
  }
};

/**
 * Fetches conversation messages with a specific user.
 * @param {number} userId - The user ID to fetch the conversation with
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const getConversationApi = async (userId) => {
  try {
    const response = await api.get(API_ENDPOINTS.FETCH_CONVERSATION(userId));
    return { data: response.data.messages || [], error: null };
  } catch (err) {
    return { data: null, error: formatApiError(err, 'Failed to load conversation') };
  }
};

/**
 * Marks all messages as read.
 * @returns {Promise<{ data: boolean | null, error: string | null }>}
 */
export const markAllAsReadApi = async () => {
  try {
    const response = await api.put(API_ENDPOINTS.MARK_ALL_READ);
    return { data: response.data.success, error: null };
  } catch (err) {
    return { data: null, error: formatApiError(err, 'Failed to mark messages as read') };
  }
};
