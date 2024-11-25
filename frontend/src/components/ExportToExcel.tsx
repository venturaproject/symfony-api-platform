import React from 'react';
import * as ExcelJS from 'exceljs';
import { Product } from '../types'; 
import { useTranslation } from 'react-i18next';
import { MdCloudDownload } from 'react-icons/md'; // Importar el ícono

interface ExportToExcelProps {
    products: Product[];
}

const ExportToExcel: React.FC<ExportToExcelProps> = ({ products }) => {
    const { t } = useTranslation();

    const handleExport = async () => {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet(t('Productos')); 

        // Definir las columnas
        worksheet.columns = [
            { header: t('Nombre'), key: 'name', width: 30 },
            { header: t('Precio'), key: 'price', width: 15 },
            { header: t('Fecha Añadida'), key: 'date_add', width: 20 },
        ];

        // Añadir las filas
        products.forEach((product) => {
            worksheet.addRow({
                name: product.name,
                price: product.price,
                date_add: product.date_add ? new Date(product.date_add).toLocaleDateString() : t('N/A'), 
            });
        });

        // Generar el archivo Excel
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/octet-stream' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        
        // Obtener la fecha y hora para el nombre del archivo
        const date = new Date();
        const formattedDate = date.toLocaleString().replace(/[\/:]/g, '-'); // Reemplazar / y : para evitar problemas en el nombre del archivo
        a.href = url;
        a.download = `${t('products')}_${formattedDate}.xlsx`; // Usar la fecha en el nombre del archivo
        a.click();
        window.URL.revokeObjectURL(url);
    };

    return (
        <button
            onClick={handleExport}
            className="bg-white-500 text-gray font-normal border border-gray-300 py-1 px-3 rounded hover:bg-gray-100 hover:border-gray-400 flex items-center"
        >
            <MdCloudDownload className="mr-2" /> {/* Mostrar el ícono */}
            {t('Exportar')}
        </button>
    );
};

export default ExportToExcel;
