// lib/axios.ts
import axios, { AxiosError } from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_BASE_URL,
  timeout: 10000,
});

// Exporta la función isAxiosError
export const isAxiosError = (error: any): error is AxiosError => {
  return axios.isAxiosError(error);
};

export default api;
