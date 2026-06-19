'use client';

import { ChevronRight, Loader2 } from 'lucide-react';
import { useState, useEffect } from 'react';
import { mascotaService } from '@/utils/mascotaService';
import SearchableSelect from '@/components/ui/SearchableSelect';
import dynamic from 'next/dynamic';

const MapSelector = dynamic(() => import('@/components/veterinaria/campanias/MapSelector'), { 
  ssr: false, 
  loading: () => <div className="h-[300px] bg-gray-50 animate-pulse rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100">Cargando mapa...</div> 
});

interface PropietarioFormProps {
  onCancel: () => void;
  editId?: string;
  isProfileMode?: boolean;
}

export default function PropietarioForm({ onCancel, editId, isProfileMode = false }: PropietarioFormProps) {
  const [loading, setLoading] = useState(false);
  const [fetchingData, setFetchingData] = useState(!!editId);
  const [tipoDocumentos, setTipoDocumentos] = useState<any[]>([]);

  const [formData, setFormData] = useState({
    tipo_doc: '',
    nro_doc: '',
    nombre: '',
    paterno: '',
    materno: '',
    email: '',
    celular: '',
    nro_emergencia: '',
    vivienda_direccion: '',
    vivienda_latitud: 0,
    vivienda_longitud: 0
  });

  useEffect(() => {
    fetchInitialData();
  }, [editId]);

  const fetchInitialData = async () => {
    try {
      setFetchingData(true);
      const docs = await mascotaService.getTipoDocumentos();
      setTipoDocumentos(docs);

      if (editId) {
        const owner = await mascotaService.getPropietarioById(editId);
        setFormData({
          tipo_doc: owner.tipo_doc || '',
          nro_doc: owner.nro_doc?.toString() || '',
          nombre: owner.nombre || '',
          paterno: owner.paterno || '',
          materno: owner.materno || '',
          email: owner.email || '',
          celular: owner.celular?.toString() || '',
          nro_emergencia: owner.nro_emergencia?.toString() || '',
          vivienda_direccion: owner.direccion || '',
          vivienda_latitud: owner.vivienda_latitud || 0,
          vivienda_longitud: owner.vivienda_longitud || 0
        });
      }
    } catch (err) {
      console.error('Error fetching initial data:', err);
    } finally {
      setFetchingData(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      if (editId) {
        await mascotaService.updatePropietario(editId, formData);
      } else {
        await mascotaService.createPropietario(formData);
      }
      onCancel();
    } catch (err) {
      console.error('Error saving owner:', err);
      alert('Error al guardar el propietario. Por favor, verifica los datos.');
    } finally {
      setLoading(false);
    }
  };

  if (fetchingData) {
    return (
      <div className="flex flex-col items-center justify-center py-20 gap-4">
        <Loader2 className="animate-spin text-[#2ecc71]" size={40} />
        <p className="text-gray-500 font-medium text-lg">Cargando información del propietario...</p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      {/* Header Breadcrumb */}
      <div>
        <div className="flex items-center text-sm text-gray-500 mb-2">
          <span className="hover:text-gray-800 cursor-pointer" onClick={onCancel}>Propietarios</span>
          <ChevronRight size={16} className="mx-2" />
          <span className="text-gray-800 font-medium">{editId ? 'Editar' : 'Crear'}</span>
        </div>
        <h2 className="text-3xl font-extrabold text-gray-900 tracking-tight">
          {isProfileMode ? 'Editar Datos de Contacto' : editId ? 'Editar Propietario' : 'Crear Propietario'}
        </h2>
      </div>

      {/* Form Container */}
      <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-8 shadow-sm border border-white/60">
        
        {!isProfileMode && (
        <div className="mb-6 border border-gray-100 rounded-2xl bg-white/70 shadow-sm">
          <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 className="text-[15px] font-bold text-gray-800">Información del Propietario</h3>
          </div>
          
          <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <SearchableSelect 
              label="Tipo documento"
              required
              placeholder="Seleccione un tipo"
              value={formData.tipo_doc}
              onChange={(val) => setFormData({...formData, tipo_doc: val.toString()})}
              options={tipoDocumentos.map(td => ({
                id: td.codigo,
                label: td.nombre
              }))}
            />

            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Nro documento <span className="text-pink-500">*</span>
              </label>
              <input 
                type="number" 
                required
                value={formData.nro_doc}
                onChange={(e) => setFormData({...formData, nro_doc: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>

            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Nombres <span className="text-pink-500">*</span>
              </label>
              <input 
                type="text" 
                required
                value={formData.nombre}
                onChange={(e) => setFormData({...formData, nombre: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>

            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Apellido paterno <span className="text-pink-500">*</span>
              </label>
              <input 
                type="text" 
                required
                value={formData.paterno}
                onChange={(e) => setFormData({...formData, paterno: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>

            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Apellido materno
              </label>
              <input 
                type="text" 
                value={formData.materno}
                onChange={(e) => setFormData({...formData, materno: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>

          </div>
        </div>
        )}

        <div className="mb-6 border border-gray-100 rounded-2xl bg-white/70 shadow-sm">
          <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 className="text-[15px] font-bold text-gray-800">Datos de Contacto</h3>
          </div>
          <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Email <span className="text-pink-500">*</span>
              </label>
              <input 
                type="email" 
                required
                value={formData.email}
                onChange={(e) => setFormData({...formData, email: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>
            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Celular
              </label>
              <input 
                type="number" 
                value={formData.celular}
                onChange={(e) => setFormData({...formData, celular: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>

            <div>
              <label className="block text-[13px] font-bold text-gray-700 mb-2">
                Nro Emergencia
              </label>
              <input 
                type="number" 
                value={formData.nro_emergencia}
                onChange={(e) => setFormData({...formData, nro_emergencia: e.target.value})}
                className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm"
              />
            </div>
          </div>
        </div>

        <div className="mb-8 border border-gray-100 rounded-2xl bg-white/70 shadow-sm">
          <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 className="text-[15px] font-bold text-gray-800">Ubicación de la Vivienda</h3>
          </div>
          <div className="p-6">
            <MapSelector 
              initialAddress={formData.vivienda_direccion}
              initialLat={formData.vivienda_latitud}
              initialLng={formData.vivienda_longitud}
              onLocationSelect={(address, lat, lng) => {
                setFormData({
                  ...formData,
                  vivienda_direccion: address,
                  vivienda_latitud: lat || 0,
                  vivienda_longitud: lng || 0
                });
              }}
            />
          </div>
        </div>
        
        <div className="flex items-center space-x-4">
          <button 
            type="submit"
            disabled={loading}
            className="bg-[#2ecc71] hover:bg-[#27ae60] text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            {loading && <Loader2 className="animate-spin" size={18} />}
            {editId ? 'Guardar Cambios' : 'Crear Propietario'}
          </button>
          <button 
            type="button"
            onClick={onCancel}
            className="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors"
          >
            Cancelar
          </button>
        </div>
      </div>
    </form>
  );
}
