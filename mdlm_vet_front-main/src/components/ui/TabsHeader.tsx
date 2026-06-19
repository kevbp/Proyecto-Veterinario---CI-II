'use client';

import { LucideIcon } from 'lucide-react';

interface Tab {
  id: string;
  label: string;
  icon: LucideIcon;
}

interface TabsHeaderProps {
  tabs: Tab[];
  activeTab: string;
  onTabChange: (id: string) => void;
}

export default function TabsHeader({ tabs, activeTab, onTabChange }: TabsHeaderProps) {
  return (
    <div className="bg-white/50 backdrop-blur-md rounded-[24px] p-2 inline-flex items-center space-x-2 border border-white/60 shadow-sm overflow-x-auto max-w-full">
      {tabs.map((tab) => {
        const Icon = tab.icon;
        const isActive = activeTab === tab.id;
        return (
          <button
            key={tab.id}
            onClick={() => onTabChange(tab.id)}
            className={`flex items-center space-x-2 px-6 py-2.5 rounded-[18px] transition-all duration-300 whitespace-nowrap ${
              isActive 
                ? 'bg-white shadow-sm text-gray-800 font-bold' 
                : 'text-gray-500 hover:text-gray-700 font-medium hover:bg-white/30'
            }`}
          >
            <Icon size={18} />
            <span>{tab.label}</span>
          </button>
        );
      })}
    </div>
  );
}
