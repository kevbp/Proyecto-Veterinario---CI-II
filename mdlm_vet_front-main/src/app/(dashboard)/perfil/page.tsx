'use client';

import { useEffect, useState } from 'react';
import { User, Mail, Phone, MapPin, Loader2 } from 'lucide-react';
import { useAuthStore } from '@/store/useAuthStore';
import { mascotaService } from '@/utils/mascotaService';
import { Propietario } from '@/interfaces/Mascota';
import PropietarioForm from '@/components/veterinaria/mascotas/PropietarioForm';

export default function PerfilPage() {
  const { user } = useAuthStore();
  const [propietario, setPropietario] = useState<Propietario | null>(null);
  const [loading, setLoading] = useState(true);
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    fetchProfile();
  }, []);

  const fetchProfile = async () => {
    try {
      setLoading(true);
      const data: any = await mascotaService.getClientePerfil();
      setPropietario(data);
    } catch (err) {
      console.error('Error fetching profile:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center py-20 gap-4">
        <Loader2 className="animate-spin text-[#2ecc71]" size={40} />
        <p className="text-gray-500 font-medium text-lg">Cargando perfil...</p>
      </div>
    );
  }

  if (!propietario) {
    return (
      <div className="text-center py-20">
        <p className="text-gray-500 text-lg">No se pudo cargar la información del perfil.</p>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-500 relative">
      <div>
        <h2 className="text-3xl font-extrabold text-gray-900 tracking-tight">Mi Perfil</h2>
        <p className="text-gray-500 mt-1 font-medium text-[15px]">Gestiona tu información personal de contacto</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Left Column: User Card */}
        <div className="lg:col-span-1">
          <div className="bg-white/50 backdrop-blur-md rounded-[32px] p-8 shadow-sm border border-white/60 flex flex-col items-center text-center sticky top-8">
            <div className="w-24 h-24 rounded-full bg-gradient-to-br from-[#015f33] to-[#2ecc71] text-white flex items-center justify-center text-3xl font-bold shadow-lg border-4 border-white mb-6">
              {propietario.nombre.charAt(0).toUpperCase()}
            </div>
            <h3 className="text-xl font-bold text-gray-800">{propietario.nombre} {propietario.paterno}</h3>
            <p className="text-gray-500 text-sm mt-1">{propietario.materno}</p>
            
            <div className="mt-8 w-full space-y-4">
              <div className="flex items-center gap-3 text-sm text-gray-600 bg-white/40 p-3 rounded-2xl border border-white/60">
                <User size={18} className="text-[#015f33]" />
                <span className="font-medium text-left">DNI: {propietario.nro_doc}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Read-Only Data */}
        <div className="lg:col-span-2">
          <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-8 shadow-sm border border-white/60 h-full">
            <div className="flex items-center justify-between mb-8">
              <h3 className="text-xl font-bold text-gray-800">Información de Contacto</h3>
              <button
                onClick={() => setIsEditing(true)}
                className="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-bold transition-colors text-sm"
              >
                Editar datos de contacto
              </button>
            </div>

            <div className="space-y-6">
              <div className="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                <div className="p-3 bg-[#2ecc71]/10 rounded-xl text-[#015f33] mt-1 shrink-0">
                  <MapPin size={24} />
                </div>
                <div>
                  <p className="text-sm font-bold text-gray-500 mb-1">Dirección de Vivienda</p>
                  <p className="text-gray-900 font-medium">{propietario.direccion || 'No registrada'}</p>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                  <div className="p-3 bg-[#2ecc71]/10 rounded-xl text-[#015f33] mt-1 shrink-0">
                    <Phone size={24} />
                  </div>
                  <div>
                    <p className="text-sm font-bold text-gray-500 mb-1">Celular / Teléfono</p>
                    <p className="text-gray-900 font-medium">{propietario.celular || 'No registrado'}</p>
                  </div>
                </div>

                <div className="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                  <div className="p-3 bg-[#2ecc71]/10 rounded-xl text-[#015f33] mt-1 shrink-0">
                    <Mail size={24} />
                  </div>
                  <div className="overflow-hidden">
                    <p className="text-sm font-bold text-gray-500 mb-1">Correo Electrónico</p>
                    <p className="text-gray-900 font-medium truncate" title={propietario.email}>{propietario.email || 'No registrado'}</p>
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-1 gap-6">
                <div className="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                  <div className="p-3 bg-red-500/10 rounded-xl text-red-500 mt-1 shrink-0">
                    <Phone size={24} />
                  </div>
                  <div>
                    <p className="text-sm font-bold text-gray-500 mb-1">Nro Emergencia</p>
                    <p className="text-gray-900 font-medium">{propietario.nro_emergencia || 'No registrado'}</p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      {/* Edit Form Modal */}
      {isEditing && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onClick={() => setIsEditing(false)} />
          <div className="relative bg-transparent rounded-[32px] w-full max-w-3xl max-h-[90vh] overflow-y-auto animate-in zoom-in-95 duration-300">
            <PropietarioForm 
              editId={user?.propietario_id}
              isProfileMode={true}
              onCancel={() => {
                setIsEditing(false);
                fetchProfile();
              }}
            />
          </div>
        </div>
      )}
    </div>
  );
}
