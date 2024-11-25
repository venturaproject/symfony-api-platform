import Link from 'next/link';
import { useTranslation } from 'react-i18next';
import { MdHome, MdSettings, MdHelpOutline, MdAccountCircle, MdStore } from 'react-icons/md'; 


const Sidebar = () => {
  const { t } = useTranslation();
  return (
    <div className="w-64 h-screen bg-white text-gray-700 flex flex-col p-4 fixed border-r border-gray-200">
      <div className="mb-8">
        <Link href="/" className="text-xl font-semibold relative">
         {t('Frontend')}
          <span className="block h-0.5 w-full bg-transparent absolute bottom-0 left-0 transition-all duration-300 group-hover:bg-gray-700" />
        </Link>
      </div>
      <nav className="flex flex-col space-y-4">
        <Link href="/" className="flex items-center p-2 hover:bg-gray-100 rounded transition-colors">
          <MdHome className="mr-2" size={24} />
          <span>{t('Home')}</span>
        </Link>
        <Link href="/products" className="flex items-center p-2 hover:bg-gray-100 rounded transition-colors">
          <MdStore className="mr-2" size={24} />
          <span>{t('Productos')}</span>
        </Link>
        <Link href="/profile" className="flex items-center p-2 hover:bg-gray-100 rounded transition-colors">
          <MdAccountCircle className="mr-2" size={24} />
          <span>{t('Cuenta')}</span>
        </Link>
        <Link href={`${process.env.NEXT_PUBLIC_BACKEND_URL}`} legacyBehavior>
  <a target="_blank" rel="noopener noreferrer" className="flex items-center p-2 hover:bg-gray-100 rounded transition-colors">
    <MdSettings className="mr-2" size={24} />
    <span>{t('Configuraciones')}</span>
  </a>
</Link>

        <Link href="https://github.com/venturaproject/symfony-nextjs-products" className="flex items-center p-2 hover:bg-gray-100 rounded transition-colors">
          <MdHelpOutline className="mr-2" size={24} />
          <span>{t('Ayuda')}</span>
        </Link>
      </nav>
    </div>
  );
};

export default Sidebar;
