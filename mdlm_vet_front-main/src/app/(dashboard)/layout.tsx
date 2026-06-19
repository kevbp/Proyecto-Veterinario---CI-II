'use client';

import { usePathname } from 'next/navigation';
import Link from 'next/link';
import { useAuthStore } from '@/store/useAuthStore';
import {
  Users, Home, Shield, Calendar, CreditCard, Search, Bell, LayoutDashboard,
  LogOut, Dog, Package
} from 'lucide-react';
import Image from 'next/image';
import { useEffect, useState } from 'react';

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const { user, logout, hydrate } = useAuthStore();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    hydrate();
    setMounted(true);
  }, [hydrate]);

  const menuItems = [
    { name: 'Tablero', path: '/dashboard', icon: LayoutDashboard, roles: ['admin', 'gestor', 'veterinario'] },
    {
      name: user?.roles?.includes('propietario') ? 'Gestión de mascotas' : 'Gestión de mascotas y propietarios',
      path: '/mascotas',
      icon: Dog
    },
    { name: 'Gestión de albergue y adopciones', path: '/albergue', icon: Home, roles: ['admin', 'gestor', 'veterinario'] },
    { name: 'Gestión de personal', path: '/personal', icon: Users, roles: ['admin', 'gestor'] },
    { name: 'Gestión de campañas', path: '/campanias', icon: Calendar, roles: ['admin', 'gestor', 'veterinario'] },
    { name: 'Módulo de caja y ventas', path: '/caja', icon: CreditCard, roles: ['admin', 'gestor', 'veterinario'] },
    { name: 'Gestión de inventario', path: '/inventario', icon: Package, roles: ['admin', 'gestor', 'veterinario'] },
    { name: 'Mi perfil', path: '/perfil', icon: Users, roles: ['propietario'] },
  ].filter(item => !item.roles || item.roles.some(role => user?.roles?.includes(role)));

  const handleLogout = async () => {
    await logout();
  };

  if (!mounted) return null;

  return (
    <div className="flex h-screen overflow-hidden bg-gradient-to-br from-[#e6f4f1] to-[#f4e8f7]">
      {/* Sidebar */}
      <aside className="w-[280px] bg-white/50 backdrop-blur-xl border-r border-white/50 flex flex-col my-4 ml-4 rounded-[32px] shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] overflow-hidden relative z-10">
        <div className="p-8 flex items-center justify-center border-b border-white/40">
          <Image src="/logo_munimolina.png" alt="Logo" width={160} height={60} className="w-auto h-12 object-contain" />
        </div>

        <nav className="flex-1 px-5 py-8 space-y-2.5 overflow-y-auto custom-scrollbar">
          {menuItems.map((item) => {
            const isActive = pathname === item.path || pathname.startsWith(item.path + '/');
            const Icon = item.icon;
            return (
              <Link
                key={item.path}
                href={item.path}
                className={`flex items-center space-x-4 px-5 py-3.5 rounded-2xl transition-all duration-300 ${isActive
                  ? 'bg-gradient-to-r from-[#015f33] to-[#2ecc71] text-white shadow-lg shadow-[#2ecc71]/30 translate-x-1'
                  : 'text-gray-500 hover:bg-white/60 hover:text-[#015f33] hover:translate-x-1 font-medium'
                  }`}
              >
                <Icon size={20} className={isActive ? 'text-white' : 'text-[#015f33] opacity-70'} />
                <span className="font-semibold text-[14px] tracking-wide">{item.name}</span>
              </Link>
            );
          })}
        </nav>

        {/* Logout Button */}
        <div className="p-5 border-t border-white/40">
          <button
            onClick={handleLogout}
            className="w-full flex items-center space-x-4 px-5 py-3.5 rounded-2xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition-colors duration-300 font-medium"
          >
            <LogOut size={20} />
            <span className="font-semibold text-[14px] tracking-wide">Cerrar Sesión</span>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 flex flex-col h-full overflow-hidden relative">
        {/* Abstract Background Elements inside main to not overlap sidebar */}
        <div className="absolute top-0 right-0 w-96 h-96 bg-[#2ecc71]/5 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-purple-300/10 rounded-full blur-3xl pointer-events-none" />

        {/* Top Header */}
        <header className="h-28 px-10 flex items-center justify-between relative z-10 shrink-0">
          <div>
            <h1 className="text-[#015f33] font-semibold text-[15px] flex items-center space-x-2">
              <span>Bienvenido de vuelta, {user?.name?.split(' ')[0] || 'Usuario'}</span>
              <span className="text-xl animate-bounce">👋</span>
            </h1>
            <h2 className="text-[36px] font-extrabold text-gray-800 tracking-tight mt-0.5">
              {menuItems.find(i => pathname === i.path || pathname.startsWith(i.path + '/'))?.name || 'Tablero'}
            </h2>
          </div>

          <div className="flex items-center space-x-5">
            <button className="text-[#015f33]/70 hover:text-[#015f33] transition-colors p-2.5 rounded-full hover:bg-white/50">
              <Search size={24} />
            </button>
            <button className="relative text-[#015f33]/70 hover:text-[#015f33] transition-colors p-2.5 rounded-full hover:bg-white/50">
              <Bell size={24} />
              <span className="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#f4e8f7]"></span>
            </button>
            <div className="flex items-center space-x-3 bg-white/40 pl-2 pr-5 py-2 rounded-full backdrop-blur-md shadow-sm border border-white/60 cursor-pointer hover:bg-white/60 transition-colors">
              <div className="w-10 h-10 rounded-full bg-gradient-to-br from-[#015f33] to-[#2ecc71] text-white flex items-center justify-center font-bold text-[15px] shadow-md border-2 border-white">
                {user?.name?.charAt(0).toUpperCase() || 'U'}
              </div>
              <div className="flex flex-col">
                <span className="text-[14px] font-bold text-gray-800 leading-tight">{user?.name || 'Usuario'}</span>
                <span className="text-[11px] font-medium text-gray-500 leading-tight capitalize">{user?.roles?.[0] || 'Admin'}</span>
              </div>
            </div>
          </div>
        </header>

        {/* Scrollable Content Area */}
        <div className="flex-1 overflow-auto px-10 pb-10 relative z-10 custom-scrollbar">
          {children}
        </div>
      </main>

      {/* Scrollbar styles */}
      <style dangerouslySetInnerHTML={{
        __html: `
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(1, 95, 51, 0.15); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(1, 95, 51, 0.3); }
      `}} />
    </div>
  );
}
