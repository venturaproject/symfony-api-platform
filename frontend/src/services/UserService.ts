import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_BASE_URL || 'http://localhost:8800/api';

export const getProfile = async (token: string) => {
  try {

    // Realizar la solicitud GET al endpoint /profile
    const response = await axios.get(`${API_URL}/users/current`, {
      headers: {
        'Content-Type': 'application/json',  
        'Authorization': `Bearer ${token}`, 
        'Accept': 'application/json',         
      },
    });

    return response.data;
  } catch (error: any) {
    throw error;
  }
};

// Change password
export const changePassword = async (token: string, data: {
  current_password: string;
  new_password: string;
  new_password_confirmation: string;
}) => {
  const response = await axios.post(`${API_URL}/user/change-password`, data, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};
