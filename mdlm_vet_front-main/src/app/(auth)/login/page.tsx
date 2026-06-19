'use client';

import { useEffect } from 'react';
import Footer from '@/components/common/Footer';

export default function LoginPage() {
  useEffect(() => {
    const callback = encodeURIComponent(window.location.origin + '/auth/callback');
    const baseUrl = process.env.NEXT_PUBLIC_SSO_URL || 'http://sso.test/login';
    const ssoUrl = `${baseUrl}?callback=${callback}`;
    window.location.href = ssoUrl;
  }, []);

  return (
    <main
      className="flex min-h-screen flex-col text-white relative overflow-hidden"
      style={{
        background:
          'linear-gradient(135deg, #013d21 0%, #015f33 30%, #017a42 60%, #015f33 100%)',
      }}
    >
      <div className="flex-grow flex items-center justify-center relative z-10">
        <div className="text-center space-y-4">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto"></div>
          <p className="text-xl font-medium">Redirigiendo al inicio de sesión único...</p>
        </div>
      </div>

      <Footer />
    </main>
  );
}
