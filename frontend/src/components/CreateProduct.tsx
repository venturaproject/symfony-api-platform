import { useState } from 'react';
import axios, { isAxiosError } from '../../lib/axios';
import { useRouter } from 'next/router';
import { useTranslation } from 'react-i18next';
import Cookies from 'js-cookie';
import { createProduct } from '../../src/services/ProductService';

interface CreateProductProps {
    onSuccess: () => void;
}

interface ErrorResponse {
    message: string;
    errors: {
        [key: string]: string[];
    };
}

const CreateProduct: React.FC<CreateProductProps> = ({ onSuccess }) => {
    const { t } = useTranslation();
    const router = useRouter();
    const [nombre, setNombre] = useState('');
    const [descripcion, setDescripcion] = useState('');
    const [precio, setPrecio] = useState('');
    const [fecha, setFecha] = useState('');
    const [mensaje, setMensaje] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const token = Cookies.get('token');

        if (!token) {
            setError(t('error_no_token'));
            return;
        }

        try {
            await createProduct(
                {
                    name: nombre,
                    description: descripcion || '',
                    price: parseFloat(precio),
                    date_add: fecha,
                    created_at: new Date().toISOString(),
                },
                token
            );

            setMensaje(t('product_added_success'));
            setError('');
            setTimeout(() => {
                onSuccess();
            }, 2000);
        } catch (err) {
            console.error('Error adding product:', err);
            if (isAxiosError(err) && err.response?.data) {
                const errorData = err.response.data as ErrorResponse;
                const errorMessage = errorData.errors?.price ? errorData.errors.price[0] : t('error_adding_product');
                setError(`${t('error')}: ${errorMessage}`);
            } else {
                setError(`${t('error')}: ${t('error_adding_product')}`);
            }
            setMensaje('');
        }
    };

    const handleCancel = () => {
        router.push('/products');
    };

    return (
        <div className="bg-white p-8 rounded w-full max-w-3xl">
            <h1 className="text-xl font-semibold mb-6">{t('Añadir Nuevo Producto')}</h1>
            {mensaje && (
                <div className="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <span className="font-medium"></span> {mensaje}
                </div>
            )}
            {error && (
                <div className="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <span className="font-medium">{t('Error')}</span> {error}
                </div>
            )}
            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div className="md:col-span-2">
                    <div className="mb-8">
                        <label className="block text-sm font-medium text-gray-700">
                            {t('Nombre del Producto')}
                        </label>
                        <input
                            type="text"
                            className="mt-1 block w-full border border-gray-300 rounded p-4 focus:outline-none focus:ring-2 focus:ring-black"
                            value={nombre}
                            onChange={(e) => setNombre(e.target.value)}
                            required
                        />
                    </div>
                    <div className="mb-8">
                        <label className="block text-sm font-medium text-gray-700">
                            {t('Descripción (opcional)')}
                        </label>
                        <textarea
                            className="mt-1 block w-full border border-gray-300 rounded p-4 focus:outline-none focus:ring-2 focus:ring-black"
                            rows={4}
                            value={descripcion}
                            onChange={(e) => setDescripcion(e.target.value)}
                        />
                    </div>
                </div>
                <div className="md:col-span-1">
                    <div className="mb-8">
                        <label className="block text-sm font-medium text-gray-700">
                            {t('Precio')}
                        </label>
                        <input
                            type="number"
                            className="mt-1 block w-full border border-gray-300 rounded p-4 focus:outline-none focus:ring-2 focus:ring-black"
                            value={precio}
                            onChange={(e) => setPrecio(e.target.value)}
                            required
                        />
                    </div>
                    <div className="mb-8">
                        <label className="block text-sm font-medium text-gray-700">
                            {t('Fecha de Adición')}
                        </label>
                        <input
                            type="date"
                            className="mt-1 block w-full border border-gray-300 rounded p-4 focus:outline-none focus:ring-2 focus:ring-black"
                            value={fecha}
                            onChange={(e) => setFecha(e.target.value)}
                            required
                        />
                    </div>
                </div>
                <div className="flex justify-center col-span-1 md:col-span-3 mt-6 space-x-4">
                    <button
                        type="submit"
                        className="bg-gray-900 text-white font-bold py-2 px-4 rounded hover:bg-gray-600 transition"
                    >
                        {t('Guardar Producto')}
                    </button>
                    <button
                        type="button"
                        onClick={handleCancel}
                        className="bg-gray-300 text-black font-bold py-2 px-4 rounded hover:bg-gray-400 transition"
                    >
                        {t('Cancelar')}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default CreateProduct;
