import React from 'react';
import ProductList from '../../src/components/ProductList';
import { useTranslation } from 'react-i18next'; 
import withAuth from '../../hoc/withAuth';

const ProductsPage: React.FC = () => {
    const { t } = useTranslation(); 
    return (
        <div className="container mx-auto p-4">
            <h1 className="text-2xl font-bold mb-4">{t('Productos')}</h1> 
            <ProductList />
        </div>
    );
};

export default withAuth(ProductsPage);
