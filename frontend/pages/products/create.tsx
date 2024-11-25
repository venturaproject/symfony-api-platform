// frontend/pages/products/create.tsx
import { useRouter } from 'next/router';
import CreateProduct from '../../src/components/CreateProduct';
import withAuth from '../../hoc/withAuth';

const CreateProductPage: React.FC = () => {
    const router = useRouter();

    const handleSuccess = () => {
        router.push('/products'); 
    };

    return (
        <div className="flex items-center justify-center min-h-screen bg-white-100">
            <CreateProduct onSuccess={handleSuccess} />
        </div>
    );
};

export default withAuth(CreateProductPage);
