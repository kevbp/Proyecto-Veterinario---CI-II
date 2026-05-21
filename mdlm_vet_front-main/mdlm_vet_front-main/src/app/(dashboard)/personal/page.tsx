'use client';

import PersonalList from '@/components/veterinaria/personal/PersonalList';
import { useAuthStore } from '@/store/useAuthStore';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';

export default function PersonalPage() {
  const { user } = useAuthStore();
  const router = useRouter();

  useEffect(() => {
    if (user?.roles?.includes('veterinario')) {
      router.replace('/dashboard');
    }
  }, [user, router]);

  return (
    <div className="space-y-8 animate-in fade-in duration-500">
      <PersonalList />
    </div>
  );
}
