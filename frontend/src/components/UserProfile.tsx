import React, { useEffect, useState } from 'react';
import { getProfile } from '../services/UserService';
import ClipLoader from 'react-spinners/ClipLoader';
import { useTranslation } from 'react-i18next';
import Cookies from 'js-cookie';

const UserProfile: React.FC = () => {
    const { t } = useTranslation();
    const [profile, setProfile] = useState<any>(null);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchProfile = async () => {
            const token = Cookies.get('token'); 

            if (!token) {
                setError(t('No se encontró el token'));
                setLoading(false);
                return;
            }

            try {
                const data = await getProfile(token); 
                setProfile(data);
            } catch (err) {
                setError(t('error_fetching_profile'));
            } finally {
                setLoading(false);
            }
        };

        fetchProfile();
    }, [t]);

    if (loading) {
        return (
            <div className="flex justify-center items-center h-full">
                <ClipLoader loading={loading} size={50} color={"#000000"} />
            </div>
        );
    }

    if (error) {
        return <div className="text-center text-red-600">{error}</div>;
    }

    return (
        <div className="p-4">
            <h1 className="text-xl font-bold mb-4">{t('Perfil del Cliente')}</h1>
            <div className="bg-white p-4 rounded">
                <p><strong>{t('Nombre')}:</strong> {profile.username}</p>
                <p><strong>{t('Email')}:</strong> {profile.email}</p>
            </div>
        </div>
    );
};

export default UserProfile;
