import axios from 'axios';

// Since Ziggy is global, 'route()' is available here without explicit import.
const API_ENDPOINTS = {
    FETCH_INBOX: route('messages.listWithLastMessage'),
    SEND_MESSAGE: route('messages.send'),
    FETCH_CONVERSATION: (userId) => route('messages.conversation', userId),
    MARK_ALL_READ: route('messages.markAllAsRead'),
};

/** * Fetches inbox messages from the API.
 * This method directly interacts with the API endpoint for fetching inbox messages.
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const getInboxApi = async () => {
  try {
    const response = await axios.get(API_ENDPOINTS.FETCH_INBOX);
    return { data: response.data || [], error: null };
  } catch (err) {
    console.error('InboxService Error: Failed to fetch inbox:', err);

    let errorMessage = 'Failed to load inbox due to a network error.';

    if (err.response) {
      console.error('Response Data:', err.response.data);
      console.error('Response Status:', err.response.status);
      errorMessage = `Failed to load inbox: ${err.response.status} - ${err.response.data?.message || 'Server error'}`;
    } else if (err.request) {
      errorMessage = 'No response from server. Please check your internet connection.';
    }

    return { data: null, error: errorMessage };
  }
};

// send message
export const sendMessageApi = async (receiverId, messageContent) => {
  try {
    const response = await axios.post(API_ENDPOINTS.SEND_MESSAGE, {
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
 * Fetch conversation messages with a specific user.
 * @param {number} userId
 * @returns {Promise<{ data: Array | null, error: string | null }>}
 */
export const getConversationApi = async (userId) => {
  try {
    const response = await axios.get(API_ENDPOINTS.FETCH_CONVERSATION(userId));
    return { data: response.data.messages || [], error: null };
  } catch (err) {
    return { data: null, error: formatApiError(err, 'Failed to load conversation') };
  }
};


// Mark all messages as read
export const markAllAsReadApi = async () => {
  try {
    const response = await axios.put(API_ENDPOINTS.MARK_ALL_READ);
    return { data: response.data.success, error: null };
  }catch (err) {  
    return { data: null, error: formatApiError(err, 'Failed to mark messages as read') };
  }

}