import React, { useState } from 'react';
import { MdClose } from 'react-icons/md'; // Icono de cerrar

type ProductFilterModalProps = {
  onFilter: (filters: { minPrice?: number; maxPrice?: number }) => void;
  onClose: () => void;
  isOpen: boolean;
};

const ProductFilterModal: React.FC<ProductFilterModalProps> = ({ onFilter, onClose, isOpen }) => {
  const [minPrice, setMinPrice] = useState<number | undefined>(undefined);
  const [maxPrice, setMaxPrice] = useState<number | undefined>(undefined);

  const handleFilter = () => {
    onFilter({ minPrice, maxPrice });
    onClose(); // Cierra el popup después de aplicar los filtros
  };

  const handleReset = () => {
    setMinPrice(undefined);
    setMaxPrice(undefined);
    onFilter({ minPrice: undefined, maxPrice: undefined });
  };

  if (!isOpen) return null; // No renderiza el modal si no está abierto

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
      <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-lg font-semibold">Filtros</h2>
          <button onClick={onClose} className="text-gray-500 hover:text-gray-700">
            <MdClose className="text-2xl" />
          </button>
        </div>

        <div className="mb-4">
          <label className="block mb-2 text-sm font-medium text-gray-700">
            Precio Mínimo
          </label>
          <input
            type="number"
            value={minPrice ?? ''}
            onChange={(e) => setMinPrice(Number(e.target.value))}
            placeholder="Min"
            className="border border-gray-300 rounded p-2 w-full"
          />
        </div>

        <div className="mb-4">
          <label className="block mb-2 text-sm font-medium text-gray-700">
            Precio Máximo
          </label>
          <input
            type="number"
            value={maxPrice ?? ''}
            onChange={(e) => setMaxPrice(Number(e.target.value))}
            placeholder="Max"
            className="border border-gray-300 rounded p-2 w-full"
          />
        </div>

        <div className="flex space-x-2">
          <button
            onClick={handleFilter}
            className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
          >
            Aplicar Filtros
          </button>
          <button
            onClick={handleReset}
            className="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600"
          >
            Resetear
          </button>
        </div>
      </div>
    </div>
  );
};

export default ProductFilterModal;
