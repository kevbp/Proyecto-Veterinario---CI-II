'use client';

import { useEffect, useState } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import api from '@/utils/api';
import { useAuthStore } from '@/store/useAuthStore';

export default function AuthCallbackPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const [status, setStatus] = useState('Autenticando...');
  const { setAuth } = useAuthStore();

  useEffect(() => {
    const token = searchParams.get('token');

    if (token) {
      // 1. Guardar el token en localStorage
      localStorage.setItem('access_token', token);

      // 2. Realizar petición a GET /api/auth/me para sincronizar y obtener perfil/roles
      api.get('/auth/me')
        .then((response) => {
          // Normalizar: /auth/me puede retornar array o un objeto
          const raw = response.data;
          const user = Array.isArray(raw) ? raw[0] : raw;
          // 3. Guardar información del usuario (con roles y permissions)
          localStorage.setItem('user_info', JSON.stringify(user));
          setAuth(user, token);
          
          setStatus('Sincronización exitosa. Redirigiendo...');

          // 4. Redirigir según el rol
          if (user.roles?.includes('propietario')) {
            router.push('/mascotas');
          } else {
            router.push('/dashboard');
          }
        })
        .catch((error) => {
          console.error('Error al sincronizar usuario:', error);
          setStatus('Error en la autenticación. Por favor, intenta de nuevo.');
          // Opcional: limpiar token si falló el "me"
          localStorage.removeItem('access_token');
          setTimeout(() => router.push('/'), 3000);
        });
    } else {
      setStatus('Token no encontrado. Redirigiendo al inicio...');
      setTimeout(() => router.push('/'), 2000);
    }
  }, [searchParams, router]);

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#013d21] text-white">
      <div className="text-center space-y-4">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto"></div>
        <p className="text-xl font-medium">{status}</p>
      </div>
    </div>
  );
}
