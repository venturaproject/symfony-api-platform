import { AppProps } from 'next/app';
import Head from 'next/head';
import '../styles/globals.css';
import Header from '../src/components/Header';
import Footer from '../src/components/Footer';
import Sidebar from '../src/components/Sidebar'; 
import { I18nextProvider } from 'react-i18next';
import i18n from '../src/i18n';
import { AuthProvider } from '../src/context/AuthContext';
import { useRouter } from 'next/router'; 

function MyApp({ Component, pageProps }: AppProps) {
  const router = useRouter();

  // Ocultar Sidebar y Header en la página de login
  const showSidebarAndHeader = router.pathname !== '/login';

  return (
    <I18nextProvider i18n={i18n}>
        <AuthProvider>
          <div className="flex min-h-screen">
            <Head>
              <link rel="icon" href="/favicon.ico" />
              <meta name="viewport" content="width=device-width, initial-scale=1" />
              <meta name="description" content="Web site created using Next.js" />
              <title>{pageProps.title || 'Frontend App'}</title>
            </Head>

            {/* Sidebar solo si no estamos en la página de login */}
            {showSidebarAndHeader && <Sidebar />}

            <div className="flex-grow flex flex-col">
              {/* Header solo si no estamos en la página de login */}
              {showSidebarAndHeader && <Header />}

              <main className="flex-grow container mx-auto p-4">
                <Component {...pageProps} />
              </main>

              {/* Footer solo si no estamos en la página de login */}
              {showSidebarAndHeader && <Footer />}
            </div>
          </div>
        </AuthProvider>
    </I18nextProvider>
  );
}

export default MyApp;
