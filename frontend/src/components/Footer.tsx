import React from 'react';
import { useTranslation } from 'react-i18next';

const Footer: React.FC = () => {
  const { t } = useTranslation();
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-white text-gray-700 p-4 text-center border-t border-gray-200">
      <p>© {currentYear} {t('Frontend')}. {t('Todos los derechos reservados.')}</p>
    </footer>
  );
};

export default Footer;
