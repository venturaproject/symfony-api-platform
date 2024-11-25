import { useState, useEffect, useRef } from 'react';
import { useRouter } from 'next/router';
import Cookies from 'js-cookie';
import { MdAccountCircle } from 'react-icons/md';
import { useTranslation } from 'react-i18next';
import authService from '../services/authService';
import { getProfile } from '../services/UserService';

const LogoutButton = () => {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [userProfile, setUserProfile] = useState({ name: '', email: '' });
  const dropdownRef = useRef<HTMLDivElement>(null); // Especifica el tipo HTMLDivElement
  const router = useRouter();
  const { t } = useTranslation();

  useEffect(() => {
    const fetchUserProfile = async () => {
      try {
        const token = Cookies.get('token');
        if (token) {
          const profile = await getProfile(token);
          setUserProfile({ name: profile.username || 'Usuario', email: profile.email });
        }
      } catch (error) {
        console.error("Error al obtener el perfil:", error);
      }
    };
    fetchUserProfile();
  }, []);

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const handleLogout = () => {
    authService.logout();
    router.push('/login?reason=auth');
  };

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => { // Especifica el tipo MouseEvent
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setDropdownOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <div className="relative" ref={dropdownRef}>
      <button onClick={toggleDropdown} className="flex items-center space-x-2">
        <MdAccountCircle size={24} />
      </button>

      {dropdownOpen && (
        <div className="absolute left-1/2 mt-1 w-56 bg-white rounded shadow-lg py-2 z-10 transform -translate-x-1/2">
          <p className="px-3 py-2 text-sm text-gray-700 font-bold">{userProfile.name}</p>
          <p className="px-3 py-2 text-sm text-gray-700">{userProfile.email}</p>
          <hr />
          <button
            onClick={handleLogout}
            className="block w-full px-3 py-2 text-sm text-left text-gray-500 hover:bg-white-100"
          >
            {t('Cerrar sesión')}
          </button>
        </div>
      )}
    </div>
  );
};

export default LogoutButton;
