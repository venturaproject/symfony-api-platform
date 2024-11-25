import React from 'react';
import UserProfile from '../src/components/UserProfile'; 
import ChangePassword from '../src/components/ChangePassword'; 
import { useTranslation } from 'react-i18next';
import withAuth from '../hoc/withAuth'; 

const ProfilePage: React.FC = () => {
    const { t } = useTranslation(); 
    return (
        <div className="p-4">
            <div className="mb-8"> 
                <UserProfile />
            </div>
            <div className="mb-8"> 
            <ChangePassword />
            </div>
        </div>
    );
};

export default withAuth(ProfilePage);

