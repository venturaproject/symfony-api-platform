// hoc/withAuth.tsx
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Cookies from 'js-cookie';
import { useTranslation } from 'react-i18next';
import ClipLoader from 'react-spinners/ClipLoader';

const withAuth = <P extends object>(WrappedComponent: React.ComponentType<P>) => {
  return (props: P) => {
    const router = useRouter();
    const [loading, setLoading] = useState(true);
    const { t } = useTranslation();

    useEffect(() => {
      const token = Cookies.get('token');

      if (!token) {
        router.push('/login?reason=auth');
      } else {
        setLoading(false);
      }
    }, [router]);

    if (loading) {
      return (
        <div className="flex justify-center items-center h-full">
          <ClipLoader loading={loading} size={50} color="#000000" />
        </div>
      );
    }

    return <WrappedComponent {...props} />;
  };
};

export default withAuth;

