import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/router';
import { Product } from '../types';
import { deleteProduct, getProducts } from '../services/ProductService'; 
import Swal from 'sweetalert2';
import { MdAdd, MdEdit, MdDelete, MdChevronLeft, MdChevronRight } from 'react-icons/md';
import { useTranslation } from 'react-i18next';
import ExportToExcel from './ExportToExcel';
import Cookies from 'js-cookie';
import withAuth from '../../hoc/withAuth'; 
import ClipLoader from 'react-spinners/ClipLoader';

const ITEMS_PER_PAGE_OPTIONS = (process.env.NEXT_PUBLIC_PRODUCTS_PER_PAGE || '10,20,50,100').split(',').map(Number);

const ProductList: React.FC = () => {
    const { t } = useTranslation();
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [sortField, setSortField] = useState<'name' | 'price' | 'created_at' | 'date_add'>('created_at');
    const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage, setItemsPerPage] = useState(ITEMS_PER_PAGE_OPTIONS[0]);

    const fetchProducts = async () => {
        setLoading(true);
        try {
            const token = Cookies.get('token');
            if (!token) throw new Error('No token found');
            const data = await getProducts(token);
            setProducts(data);
        } catch (err) {
            setError(t('error_fetching_products'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchProducts();
    }, [t]);

    const handleDelete = async (product: Product) => {
        const result = await Swal.fire({
            title: t('¿Estás seguro?'),
            text: t('confirm_delete', { productName: product.name }),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#b7b7b7',
            confirmButtonText: t('yes_delete'),
            cancelButtonText: t('Cancelar'),
        });
    
        if (result.isConfirmed) {
            try {
                const token = Cookies.get('token');
                if (token) { 
                    await deleteProduct(String(product.id), token); 
                    Swal.fire(t('deleted'), t('product_deleted_success', { productName: product.name }), 'success');
                    await fetchProducts(); 
                } else {
                    Swal.fire(t('error'), t('token_no_disponible'), 'error'); 
                }
            } catch (error: any) {
                if (error.response && error.response.status === 403) {
                    Swal.fire(t('error'), t('No tiene permisos para realizar esta accion'), 'error');
                } else {
                    Swal.fire(t('error'), t('error_deleting_product'), 'error');
                }
            }
        }
    };

    if (loading) return <div className="flex justify-center items-center h-full"><ClipLoader loading={loading} size={50} color="#000000" /></div>;
    if (error) return <div className="text-center text-red-600">{error}</div>;

    const filteredProducts = products.filter(product => product.name.toLowerCase().includes(searchTerm.toLowerCase()));
    
    const sortedProducts = [...filteredProducts].sort((a, b) => {
        const aValue = sortField === 'price' ? a.price : new Date(a[sortField] ?? '').getTime() || 0;
        const bValue = sortField === 'price' ? b.price : new Date(b[sortField] ?? '').getTime() || 0;
        return sortOrder === 'asc' ? (aValue > bValue ? 1 : -1) : (aValue < bValue ? 1 : -1);
    });

    const totalResults = filteredProducts.length;
    const totalPages = Math.ceil(totalResults / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentProducts = sortedProducts.slice(startIndex, startIndex + itemsPerPage);

    return (
        <div className="mt-4">
            <div className="mb-4 flex items-center">
                <label htmlFor="itemsPerPage" className="mr-2">{t('Mostrar')}:</label>
                <select
                    id="itemsPerPage"
                    value={itemsPerPage}
                    onChange={(e) => setItemsPerPage(parseInt(e.target.value, 10))}
                    className="border rounded p-2 mr-4"
                >
                    {ITEMS_PER_PAGE_OPTIONS.map(option => (
                        <option key={option} value={option}>{option}</option>
                    ))}
                </select>

                <input
                    type="text"
                    placeholder={t('Buscar Productos...')}
                    className="border rounded p-2 flex-grow mr-4 focus:outline-none focus:ring-2 focus:ring-black"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                />

                <Link href="/products/create">
                    <button className="bg-white-500 text-gray font-normal border border-gray-300 py-1 px-3 rounded hover:bg-gray-100 flex items-center">
                        <MdAdd className="mr-1" /> {t('Añadir')}
                    </button>
                </Link>
                <ExportToExcel products={filteredProducts} />
            </div>

            <table className="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr className="bg-gray-100">
                        <th className="py-2 px-4 border-b">{t('Nombre')}</th>
                        <th className="py-2 px-4 border-b">{t('Precio')}</th>
                        <th className="py-2 px-4 border-b">{t('Fecha Añadida')}</th>
                        <th className="py-2 px-4 border-b">{t('Acciones')}</th>
                    </tr>
                </thead>
                <tbody>
                    {currentProducts.map((product) => (
                        <tr key={product.id}>
                            <td className="py-2 px-4 border-b">{product.name}</td>
                            <td className="py-2 px-4 border-b">${product.price}</td>
                            <td className="py-2 px-4 border-b">{product.date_add ? new Date(product.date_add).toLocaleDateString() : t('no_disponible')}</td>
                            <td className="py-2 px-4 border-b flex space-x-2">
                                <Link href={`/products/${product.id}/edit`}>
                                    <button className="bg-white-500 text-gray font-normal border border-gray-300 py-1 px-3 rounded hover:bg-gray-100 flex items-center">
                                        <MdEdit className="mr-1" /> {t('Editar')}
                                    </button>
                                </Link>
                                <button className="bg-white-500 text-gray font-normal border border-gray-300 py-1 px-3 rounded hover:bg-gray-100 flex items-center" onClick={() => handleDelete(product)}>
                                    <MdDelete className="mr-1" /> {t('Borrar')}
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <div className="mt-4 flex justify-between items-center">
                <div>{t('Página')} {currentPage} {t('de')} {totalPages} | {t('Total')} {totalResults} {t('resultados')}</div>
                <div className="flex space-x-2">
                    <button onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))} disabled={currentPage === 1} className="bg-gray-500 text-white rounded p-2 disabled:opacity-50">
                        <MdChevronLeft />
                    </button>
                    <button onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))} disabled={currentPage === totalPages} className="bg-gray-500 text-white rounded p-2 disabled:opacity-50">
                        <MdChevronRight />
                    </button>
                </div>
            </div>
        </div>
    );
};

export default withAuth(ProductList);
