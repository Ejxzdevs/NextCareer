import axios from 'axios';

// Axios instance configured to send requests with default headers and credentials,
// used for making API calls to the backend
const api = axios.create({
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
  withCredentials: true,
});

const API_ENDPOINTS = {
  UPDATE_APPLICATION: (id) => route('application.update', { id }),
};

export const updateStatusApi = async (application_status, application_id) => {
  try {
    const response = await api.patch(API_ENDPOINTS.UPDATE_APPLICATION(application_id), {
      application_status,
    });

    return { success: response.data, error: null };
  } catch (error) {
    console.error('Update application failed', error);

    return {
      success: null,
      error: error.response?.data?.message || error.message || 'Unexpected error',
    };
  }
};
