// src/context/AuthContext.tsx
import { createContext, useContext, useEffect, useState } from 'react';
import Cookie from 'js-cookie';

interface AuthContextType {
  isAuthenticated: boolean;
  login: (token: string) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);

  useEffect(() => {
    const token = Cookie.get('token'); // Verifica el token en las cookies
    if (token) {
      setIsAuthenticated(true);
    }
  }, []);

  const login = (token: string) => {
    Cookie.set('token', token); // Almacena el token en las cookies
    setIsAuthenticated(true); // Actualiza el estado de autenticación
  };

  const logout = () => {
    Cookie.remove('token'); // Elimina el token de las cookies
    setIsAuthenticated(false); // Actualiza el estado de autenticación
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

// Hook personalizado para usar el contexto
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

