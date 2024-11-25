import React from 'react';
import withAuth from '../hoc/withAuth';
import { useTranslation } from 'react-i18next';

const Home: React.FC = () => {
  const { t } = useTranslation(); 
  const backendUrl = `${process.env.NEXT_PUBLIC_BACKEND_URL}`;
  return (
    <div className="flex items-center justify-center min-h-screen bg-white-100">
      <div className="text-center">
        <h1 className="regard text-4xl font-bold text-gray-600 mb-6">
          👋 {t('Bienvenido a Frontend!')}
        </h1>

        <div className="flex justify-center space-x-6">
     
          <a
            href={backendUrl}
            className="inline-block bg-gray-900 text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-600 transition"
            target="_blank"
            rel="noopener noreferrer"
          >{t('Estado de la API')}
          </a>

          {/* Enlace al CRUD de productos */}
          <a
            href="/products"
            className="inline-block bg-gray-900 text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-600 transition"
          >{t('Productos')}
          </a>
        </div>
      </div>
    </div>
  );
};

export default withAuth(Home);
