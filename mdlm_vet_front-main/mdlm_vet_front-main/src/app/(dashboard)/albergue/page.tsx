'use client';

import { useState } from 'react';
import { Home, Heart } from 'lucide-react';
import TabsHeader from '@/components/ui/TabsHeader';
import AlbergueList from '@/components/veterinaria/albergue/AlbergueList';
import AdopcionesList from '@/components/veterinaria/albergue/AdopcionesList';

export default function AlberguePage() {
  const [activeTab, setActiveTab] = useState('albergue');

  const tabs = [
    { id: 'albergue', label: 'Albergue', icon: Home },
    { id: 'adopciones', label: 'Adopciones', icon: Heart },
  ];

  return (
    <div className="space-y-8">
      <TabsHeader tabs={tabs} activeTab={activeTab} onTabChange={setActiveTab} />

      {/* Content Area */}
      <div className="relative">
        {activeTab === 'albergue' ? <AlbergueList /> : <AdopcionesList />}
      </div>
    </div>
  );
}
