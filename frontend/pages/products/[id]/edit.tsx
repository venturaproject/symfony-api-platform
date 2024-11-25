import { useRouter } from 'next/router';
import EditProduct from '../../../src/components/EditProduct';
import withAuth from '../../../hoc/withAuth';

const EditProductPage: React.FC = () => {
    const router = useRouter();
    const { id } = router.query;

    if (!id) return null; 

    return <EditProduct productId={id as string} />;
};

export default withAuth(EditProductPage);
