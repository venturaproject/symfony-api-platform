import React from 'react';
import LanguageSelector from './LanguageSelector'; 
import LogoutButton from './LogoutButton'; 
import { useRouter } from 'next/router'; 

const Header: React.FC = () => {
  const router = useRouter(); 

  if (router.pathname === '/login') {
    return null; 
  }

  return (
    <header className="bg-white text-gray-700 p-4 flex justify-between items-center border-b border-gray-200">
      <h1 className="text-2xl font-bold">Frontend</h1>
      <div className="flex items-center space-x-4">
        <LogoutButton /> 
        <LanguageSelector />     
      </div>
    </header>
  );
};

export default Header;
