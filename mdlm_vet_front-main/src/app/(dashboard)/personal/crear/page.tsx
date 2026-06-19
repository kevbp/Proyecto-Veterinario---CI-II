'use client';

import PersonalForm from '@/components/veterinaria/personal/PersonalForm';
import { useAuthStore } from '@/store/useAuthStore';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';

export default function CrearPersonalPage() {
  const { user } = useAuthStore();
  const router = useRouter();

  useEffect(() => {
    if (user?.roles?.includes('veterinario')) {
      router.replace('/dashboard');
    }
  }, [user, router]);

  return (
    <div className="space-y-8 animate-in fade-in duration-500">
      <PersonalForm />
    </div>
  );
}
