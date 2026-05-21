'use client';

import { useEffect, useState } from 'react';
import { User, Mail, Phone, MapPin, Loader2, Save } from 'lucide-react';
import { useAuthStore } from '@/store/useAuthStore';
import { mascotaService } from '@/utils/mascotaService';
import { Propietario } from '@/interfaces/Mascota';

export default function PerfilPage() {
  const { user } = useAuthStore();
  const [propietario, setPropietario] = useState<Propietario | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [showConfirmModal, setShowConfirmModal] = useState(false);
  const [formData, setFormData] = useState({
    direccion: '',
    celular: '',
    email: ''
  });

  useEffect(() => {
    fetchProfile();
  }, []);

  const fetchProfile = async () => {
    try {
      setLoading(true);
      const data: any = await mascotaService.getClientePerfil();
      setPropietario(data);
      setFormData({
        direccion: data.vivienda_direccion || '',
        celular: data.celular || '',
        email: data.email || ''
      });
    } catch (err) {
      console.error('Error fetching profile:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSaveClick = (e: React.FormEvent) => {
    e.preventDefault();
    setShowConfirmModal(true);
  };

  const handleConfirmSave = async () => {
    if (!user?.propietario_id) return;

    setSaving(true);
    setShowConfirmModal(false);
    try {
      await mascotaService.updatePropietario(user.propietario_id, formData);
      alert('Perfil actualizado con éxito');
    } catch (err) {
      console.error('Error updating profile:', err);
      alert('Error al actualizar el perfil');
    } finally {
      setSaving(false);
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
    <div className="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-500">
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

        {/* Right Column: Edit Form */}
        <div className="lg:col-span-2">
          <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-8 shadow-sm border border-white/60">
            <form onSubmit={handleSaveClick} className="space-y-6">
              <div className="space-y-4">
                <div>
                  <label className="block text-[13px] font-bold text-gray-700 mb-2 flex items-center gap-2">
                    <MapPin size={16} className="text-[#015f33]" /> Dirección de Vivienda
                  </label>
                  <input
                    type="text"
                    value={formData.direccion}
                    onChange={(e) => setFormData({ ...formData, direccion: e.target.value })}
                    className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm transition-all"
                    placeholder="Tu dirección actual"
                  />
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-[13px] font-bold text-gray-700 mb-2 flex items-center gap-2">
                      <Phone size={16} className="text-[#015f33]" /> Celular / Teléfono
                    </label>
                    <input
                      type="tel"
                      value={formData.celular}
                      onChange={(e) => setFormData({ ...formData, celular: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm transition-all"
                      placeholder="999 999 999"
                    />
                  </div>
                  <div>
                    <label className="block text-[13px] font-bold text-gray-700 mb-2 flex items-center gap-2">
                      <Mail size={16} className="text-[#015f33]" /> Correo Electrónico
                    </label>
                    <input
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm transition-all"
                      placeholder="ejemplo@correo.com"
                    />
                  </div>
                </div>
              </div>

              <div className="pt-4">
                <button
                  type="submit"
                  disabled={saving}
                  className="w-full md:w-auto bg-gradient-to-r from-[#015f33] to-[#2ecc71] hover:shadow-lg hover:shadow-[#2ecc71]/30 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 flex items-center justify-center gap-2"
                >
                  {saving ? <Loader2 className="animate-spin" size={20} /> : <Save size={20} />}
                  Guardar Cambios
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {/* Confirmation Modal */}
      {showConfirmModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onClick={() => setShowConfirmModal(false)} />
          <div className="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in-95 duration-300">
            <div className="px-8 pt-8 pb-6 text-center">
              <div className="w-16 h-16 bg-[#2ecc71]/10 rounded-full flex items-center justify-center mx-auto mb-6 text-[#2ecc71]">
                <Save size={32} />
              </div>
              <h3 className="text-2xl font-black text-gray-900 mb-3">¿Guardar cambios?</h3>
              <p className="text-gray-500 leading-relaxed mb-8">
                Se actualizará tu información personal de contacto en el sistema.
              </p>
              <div className="flex flex-col gap-3">
                <button
                  onClick={handleConfirmSave}
                  className="w-full bg-gradient-to-r from-[#015f33] to-[#2ecc71] text-white py-4 rounded-2xl font-bold shadow-lg transition-all"
                >
                  Confirmar y Guardar
                </button>
                <button
                  onClick={() => setShowConfirmModal(false)}
                  className="w-full py-4 rounded-2xl font-bold text-gray-500 hover:bg-gray-50 transition-colors"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
