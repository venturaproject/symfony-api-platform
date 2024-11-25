import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import Swal from 'sweetalert2';
import Cookies from 'js-cookie';
import { changePassword } from '../../src/services/UserService';
import { MdVisibility, MdVisibilityOff } from 'react-icons/md';
import ClipLoader from 'react-spinners/ClipLoader';

const ChangePassword: React.FC = () => {
    const { t } = useTranslation(); 
    const [currentPassword, setCurrentPassword] = useState('');
    const [newPassword, setNewPassword] = useState('');
    const [newPasswordConfirmation, setNewPasswordConfirmation] = useState('');
    const [errors, setErrors] = useState<{ [key: string]: string[] }>({});
    const [loading, setLoading] = useState(true); 
    const [showCurrentPassword, setShowCurrentPassword] = useState(false); 
    const [showNewPassword, setShowNewPassword] = useState(false); 
    const [showNewPasswordConfirmation, setShowNewPasswordConfirmation] = useState(false); 

    useEffect(() => {
        const timer = setTimeout(() => {
            setLoading(false); 
        }, 1000); 

        return () => clearTimeout(timer);
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrors({});

        const token = Cookies.get('token');

        if (!token) {
            setErrors({ general: [t('tokenMissingError')] });
            return;
        }

        try {
            await changePassword(token, {
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: newPasswordConfirmation,
            });

            Swal.fire({
                icon: 'success',
                title: t('passwordChangeSuccess'),
                text: t('passwordChanged'),
            });
        } catch (err: any) {
            const errorResponse = err.response.data; 
            setErrors(errorResponse.errors || { general: [t('Error al cambiar la contraseña')] });
            Swal.fire({
                icon: 'error',
                title: t('error'),
                text: t('Error al cambiar la contraseña'),
                confirmButtonColor: '#000000',
                cancelButtonColor: '#b7b7b7',
            });
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-screen">
                <ClipLoader loading={loading} size={50} color="#000000" />
            </div>
        );
    }

    return (
        <div className="space-y-4 p-4 bg-white rounded w-96">
            <h1 className="text-xl font-bold mb-4 mt-6">{t('Cambiar contraseña')}</h1>
            <form onSubmit={handleSubmit} className="space-y-4"> 
                <div>
                    <label htmlFor="current-password" className="block text-sm font-medium text-gray-700">{t('Contraseña actual')}</label>
                    <div className="relative">
                        <input
                            type={showCurrentPassword ? 'text' : 'password'}
                            id="current-password"
                            value={currentPassword}
                            onChange={(e) => setCurrentPassword(e.target.value)}
                            required
                            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button
                            type="button"
                            onClick={() => setShowCurrentPassword(!showCurrentPassword)}
                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 focus:outline-none"
                        >
                            {showCurrentPassword ? <MdVisibilityOff /> : <MdVisibility />}
                        </button>
                    </div>
                    {errors.current_password && (
                        <p className="text-red-500 text-sm">{errors.current_password.join(', ')}</p>
                    )}
                </div>
                <div>
                    <label htmlFor="new-password" className="block text-sm font-medium text-gray-700">{t('Nueva contraseña')}</label>
                    <div className="relative">
                        <input
                            type={showNewPassword ? 'text' : 'password'}
                            id="new-password"
                            value={newPassword}
                            onChange={(e) => setNewPassword(e.target.value)}
                            required
                            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button
                            type="button"
                            onClick={() => setShowNewPassword(!showNewPassword)}
                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 focus:outline-none"
                        >
                            {showNewPassword ? <MdVisibilityOff /> : <MdVisibility />}
                        </button>
                    </div>
                    {errors.new_password && (
                        <p className="text-red-500 text-sm">{errors.new_password.join(', ')}</p>
                    )}
                </div>
                <div>
                    <label htmlFor="new-password-confirmation" className="block text-sm font-medium text-gray-700">{t('Confirmar nueva contraseña')}</label>
                    <div className="relative">
                        <input
                            type={showNewPasswordConfirmation ? 'text' : 'password'}
                            id="new-password-confirmation"
                            value={newPasswordConfirmation}
                            onChange={(e) => setNewPasswordConfirmation(e.target.value)}
                            required
                            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button
                            type="button"
                            onClick={() => setShowNewPasswordConfirmation(!showNewPasswordConfirmation)}
                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 focus:outline-none"
                        >
                            {showNewPasswordConfirmation ? <MdVisibilityOff /> : <MdVisibility />}
                        </button>
                    </div>
                    {errors.new_password_confirmation && (
                        <p className="text-red-500 text-sm">{errors.new_password_confirmation.join(', ')}</p>
                    )}
                </div>
                <button
                    type="submit"
                    className="w-full px-4 py-2 mt-6 text-white bg-gray-900 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    {t('Cambiar contraseña')}
                </button>
                {errors.general && <p className="text-red-500 text-sm">{errors.general.join(', ')}</p>} 
            </form>
        </div>
    );
};

export default ChangePassword;

