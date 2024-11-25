import { useState } from 'react';
import { useRouter } from 'next/router';
import authService from '../services/authService'; 
import ClipLoader from 'react-spinners/ClipLoader'; 
import { useTranslation } from 'react-i18next';
import { MdVisibility, MdVisibilityOff } from 'react-icons/md'; 

const LoginForm = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false); 
  const [showPassword, setShowPassword] = useState(false); 
  const router = useRouter();
  const { t } = useTranslation();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setLoading(true); 

    try {
      await authService.login(email, password); 
      router.push('/'); 
    } catch (err: any) {
      setError(t('Error al iniciar sesión. Por favor, compruebe sus credenciales e inténtelo de nuevo.')); 
    } finally {
      setLoading(false); 
    }
  };

  return (
    <div className="flex items-center justify-center min-h-screen bg-white-100">
      <div className="w-full max-w-md p-8 space-y-4 bg-white rounded shadow-lg">
        <h2 className="text-2xl font-bold text-center text-gray-900">{t('Iniciar sesión')}</h2>

        {error && (
          <div className="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {error}
          </div>
        )}
        
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
              {t('Email')}
            </label>
            <div className="mt-1">
              <input
                type="email"
                id="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="w-full px-3 py-2 text-gray-900 bg-gray-100 border rounded focus:outline-none focus:ring-2 focus:ring-black"
              />
            </div>
          </div>

          <div>
            <label htmlFor="password" className="block text-sm font-medium text-gray-700">
              {t('Contraseña')}
            </label>
            <div className="relative mt-1">
              <input
                type={showPassword ? 'text' : 'password'} 
                id="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                className="w-full px-3 py-2 text-gray-900 bg-gray-100 border rounded focus:outline-none focus:ring-2 focus:ring-black"
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)} 
                className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 focus:outline-none"
              >
                {showPassword ? <MdVisibilityOff /> : <MdVisibility />} {/* Muestra el icono adecuado */}
              </button>
            </div>
          </div>

          <button
            type="submit"
            className={`w-full px-4 py-2 text-white bg-black rounded hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black ${loading ? 'opacity-50 cursor-not-allowed' : ''}`}
            disabled={loading} 
          >
            {loading ? <ClipLoader loading={loading} size={20} color="#ffffff" /> : t('Iniciar sesión')}
          </button>
        </form>
      </div>
    </div>
  );
};

export default LoginForm;


