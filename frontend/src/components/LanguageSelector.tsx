import React, { useState, useEffect, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import Cookies from 'js-cookie'; 
import { MdLanguage } from 'react-icons/md';

const LanguageSelector: React.FC = () => {
    const { i18n, t } = useTranslation(); // Añadimos 't' para traducir textos
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const dropdownRef = useRef<HTMLDivElement>(null); 

    const changeLanguage = (lng: string) => {
        i18n.changeLanguage(lng);
        Cookies.set('language', lng, { expires: 365 });
        setDropdownOpen(false);
    };

    useEffect(() => {
        const savedLanguage = Cookies.get('language');
        if (savedLanguage) {
            i18n.changeLanguage(savedLanguage);
        }
    }, [i18n]);

    const toggleDropdown = () => setDropdownOpen(!dropdownOpen);

    const handleClickOutside = (event: MouseEvent) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
            setDropdownOpen(false);
        }
    };

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    const currentLanguage = i18n.language === 'es' ? t('Español') : t('English');

    return (
        <div className="flex justify-end">
            <div className="relative inline-block text-left" ref={dropdownRef}>
                <button
                    onClick={toggleDropdown}
                    className="flex items-center px-4 py-2 border rounded-lg bg-transparent hover:bg-transparent focus:outline-none"
                >
                    <MdLanguage className="mr-2" /> 
                    {currentLanguage}
                </button>

                {dropdownOpen && (
                    <ul className="absolute right-0 mt-2 py-2 bg-white border rounded-lg shadow-lg min-w-max">
                        <li>
                            <button
                                onClick={() => changeLanguage('es')}
                                className={`block px-4 py-2 text-left ${
                                    i18n.language === 'es' ? 'text-black font-semibold' : 'text-gray-500'
                                }`}
                            >
                                {t('Español')}
                            </button>
                        </li>
                        <li>
                            <button
                                onClick={() => changeLanguage('en')}
                                className={`block px-4 py-2 text-left ${
                                    i18n.language === 'en' ? 'text-black font-semibold' : 'text-gray-500'
                                }`}
                            >
                                {t('Inglés')}
                            </button>
                        </li>
                    </ul>
                )}
            </div>
        </div>
    );
};

export default LanguageSelector;
