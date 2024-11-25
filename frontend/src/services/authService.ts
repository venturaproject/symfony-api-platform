// services/authService.ts
import axios from '../../lib/axios';
import Cookies from 'js-cookie';

const login = async (email: string, password: string) => {
  const response = await axios.post('/login_check', { email, password });
  const { token } = response.data; // Asegúrate de que esto coincida con la estructura de tu respuesta.

  // Guardar el token y el email en cookies
  Cookies.set('token', token, { expires: 1 });
  Cookies.set('userEmail', email, { expires: 1 });

  return response.data;
};

const logout = () => {
  // Eliminar el token y el email
  Cookies.remove('token');
  Cookies.remove('userEmail');
};

const getUserEmail = () => {
  return Cookies.get('userEmail');
};

export default {
  login,
  logout,
  getUserEmail,
};
