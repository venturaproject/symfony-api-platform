import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import { useTranslation } from 'react-i18next';
import Cookies from 'js-cookie';
import { getProductById, updateProduct, Product } from '../../src/services/ProductService';
import ClipLoader from 'react-spinners/ClipLoader';

interface EditProductProps {
    productId: string;
}

const EditProduct: React.FC<EditProductProps> = ({ productId }) => {
    const router = useRouter();
    const { t } = useTranslation();
    const [product, setProduct] = useState<Product | null>(null);
    const [originalProduct, setOriginalProduct] = useState<Product | null>(null);
    const [mensaje, setMensaje] = useState('');
    const [error, setError] = useState('');
    const [warning, setWarning] = useState('');
    const [loading, setLoading] = useState(true); 

    useEffect(() => {
        const fetchProduct = async () => {
            setLoading(true);
            try {
                const token = Cookies.get('token') || '';
                const fetchedProduct = await getProductById(productId, token);
                setProduct(fetchedProduct);
                setOriginalProduct(fetchedProduct); 
            } catch (err) {
                console.error('Error fetching product:', err);
                setError(t('error_loading_product'));
            } finally {
                setLoading(false);
            }
        };

        fetchProduct();
    }, [productId, t]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!product || Number(product.price) <= 0) {
            setError(`${t('error')}: ${t('error_price_positive')}`);
            return;
        }

        if (
            originalProduct &&
            (product.name === originalProduct.name &&
             product.description === originalProduct.description &&
             product.price === originalProduct.price &&
             product.date_add === originalProduct.date_add)
        ) {
            setWarning(t('No se han realizado cambios.')); 
            return;
        }

        try {
            const token = Cookies.get('token') || '';
            await updateProduct(productId, product, token);
            setMensaje(t('product_edited_success'));
            setError('');
            setWarning('');

            setTimeout(() => {
                router.push('/products');
            }, 2000);
        } catch (err) {
            console.error('Error editing product:', err);
            setError(`${t('error')}: ${t('error_editing_product')}`);
            setMensaje('');
        }
    };

    const handleCancel = () => {
        router.push('/products');
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        if (product) {
            const { name, value } = e.target;
            setProduct({
                ...product,
                [name]: name === 'price' ? parseFloat(value) || 0 : value,
            });
        }
        setWarning('');
    };

    return (
        <div className="flex items-center justify-center min-h-screen bg-white-100">
            {loading ? (
                <div className="flex justify-center items-center w-full h-full">
                    <ClipLoader loading={loading} size={50} color="#000000" />
                </div>
            ) : (
                <div className="bg-white p-8 rounded w-full max-w-3xl">
                    <h1 className="text-2xl font-semibold mb-6">
                        {product ? `${product.name}` : t('edit_product')}
                    </h1>
                    {mensaje && (
                        <div className="mb-4 p-4 text-green-800 bg-green-100 rounded border border-green-400 transition-all duration-300">
                            {mensaje}
                        </div>
                    )}
                    {error && (
                        <div className="mb-4 p-4 text-red-800 bg-red-100 rounded border border-red-400 transition-all duration-300">
                            {error}
                        </div>
                    )}
                    {warning && (
                        <div className="mb-4 p-4 text-orange-800 bg-orange-100 rounded border border-orange-400 transition-all duration-300">
                            {warning}
                        </div>
                    )}
                    {product && (
                        <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div className="md:col-span-2">
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700">{t('Nombre del Producto')}</label>
                                    <input
                                        type="text"
                                        name="name"
                                        className="mt-2 block w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-black"
                                        value={product.name}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700">{t('Descripción (opcional)')}</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        rows={4}
                                        className="w-full px-3 py-2 text-gray-900 bg-white-100 border rounded focus:outline-none focus:ring-2 focus:ring-black"
                                        value={product.description} 
                                        onChange={handleChange}
                                    />
                                </div>
                            </div>
                            <div className="md:col-span-1">
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700">{t('Precio')}</label>
                                    <input
                                        type="number"
                                        name="price"
                                        className="mt-2 block w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-black"
                                        value={product.price}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700">{t('Fecha de Adición')}</label>
                                    <input
                                        type="date"
                                        name="date_add"
                                        className="mt-2 block w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-black"
                                        value={product.date_add}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                            </div>
                            <div className="flex justify-center col-span-1 md:col-span-3 mt-6 space-x-4">
                                <button
                                    type="submit"
                                    className="bg-gray-900 text-white font-bold py-3 px-4 rounded hover:bg-gray-600 transition"
                                >
                                    {t('Guardar Cambios')}
                                </button>
                                <button
                                    type="button"
                                    onClick={handleCancel}
                                    className="bg-gray-300 text-black font-bold py-3 px-4 rounded hover:bg-gray-400 transition"
                                >
                                    {t('Cancelar')}
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            )}
        </div>
    );
};

export default EditProduct;
