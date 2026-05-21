'use client';

import { useState, Suspense } from 'react';
import { Users, Dog } from 'lucide-react';
import TabsHeader from '@/components/ui/TabsHeader';
import MascotasList from '@/components/veterinaria/mascotas/MascotasList';
import PropietariosList from '@/components/veterinaria/mascotas/PropietariosList';
import { useAuthStore } from '@/store/useAuthStore';

export default function MascotasPage() {
  const [activeTab, setActiveTab] = useState('mascotas');
  const { user } = useAuthStore();
  const isOwner = user?.roles?.includes('propietario');

  const tabs = [
    { id: 'mascotas', label: 'Mascotas', icon: Dog },
    ...(!isOwner ? [{ id: 'propietarios', label: 'Propietarios', icon: Users }] : []),
  ];

  return (
    <div className="space-y-8">
      <TabsHeader tabs={tabs} activeTab={activeTab} onTabChange={setActiveTab} />

      {/* Content Area */}
      <div className="relative">
        <Suspense fallback={<div>Cargando...</div>}>
          {activeTab === 'mascotas' ? <MascotasList /> : <PropietariosList />}
        </Suspense>
      </div>
    </div>
  );
}
