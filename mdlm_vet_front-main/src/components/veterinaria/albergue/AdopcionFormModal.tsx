'use client';

import { useEffect, useState } from 'react';
import { X, Plus, Loader2 } from 'lucide-react';
import PropietarioModal from '../mascotas/PropietarioModal';
import { mascotaService } from '@/utils/mascotaService';
import { Propietario } from '@/interfaces/Mascota';
import { adopcionService } from '@/utils/adopcionService';
import SearchableSelect from '@/components/ui/SearchableSelect';

interface AdopcionFormModalProps {
  petId: string;
  petName: string;
  onClose: () => void;
  onSuccess: () => void;
}

export default function AdopcionFormModal({ petId, petName, onClose, onSuccess }: AdopcionFormModalProps) {
  const [isCampañaHabilitada, setIsCampañaHabilitada] = useState(false);
  const [isPropietarioModalOpen, setIsPropietarioModalOpen] = useState(false);
  const [owners, setOwners] = useState<Propietario[]>([]);
  const [campaigns, setCampaigns] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [loadingData, setLoadingData] = useState(true);

  // Form State
  const [nuevoPropietarioId, setNuevoPropietarioId] = useState('');
  const [observaciones, setObservaciones] = useState('');
  const [campaniaId, setCampaniaId] = useState('');

  useEffect(() => {
    fetchInitialData();
  }, []);

  const fetchInitialData = async () => {
    try {
      setLoadingData(true);
      const [ownersData, campaignsData] = await Promise.all([
        mascotaService.getAllOwners(),
        adopcionService.getAllCampaigns()
      ]);
      setOwners(ownersData);
      setCampaigns(campaignsData);
    } catch (err) {
      console.error('Error fetching initial data for adoption:', err);
    } finally {
      setLoadingData(false);
    }
  };

  const handleConfirmAdopcion = async () => {
    if (!nuevoPropietarioId) {
      alert('Por favor seleccione un nuevo propietario.');
      return;
    }

    try {
      setLoading(true);
      await adopcionService.registrarAdopcion(petId, {
        animal_id: petId,
        propietario_nuevo_id: nuevoPropietarioId,
        observaciones,
        campania_id: isCampañaHabilitada ? campaniaId : undefined
      });
      alert('¡Adopción registrada exitosamente!');
      onSuccess();
    } catch (err: any) {
      console.error('Error registering adoption:', err);
      alert(err.response?.data?.message || 'Error al registrar la adopción');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div 
        className="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"
        onClick={onClose}
      />
      
      {/* Modal Content */}
      <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden">
        
        {/* Header */}
        <div className="px-6 py-4 flex items-center justify-between border-b border-gray-100 shrink-0">
          <h2 className="text-lg font-extrabold text-gray-800">Registrar Adopción</h2>
          <button 
            onClick={onClose}
            className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
          >
            <X size={20} />
          </button>
        </div>
        
        {/* Body (Scrollable) */}
        <div className="p-6 overflow-y-auto custom-scrollbar">
          <div className="bg-pink-50 border border-pink-100 rounded-xl p-4 mb-6">
            <p className="text-sm text-pink-800 font-medium italic">Estás registrando la adopción de: <span className="font-extrabold uppercase not-italic ml-1">{petName}</span></p>
          </div>

          <div className="border border-gray-100 rounded-2xl bg-white shadow-sm">
            <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
              <h3 className="text-[15px] font-bold text-gray-800">Detalles de la Adopción</h3>
            </div>
            
            <div className="p-6 space-y-6">
              
              {/* Nuevo Propietario */}
              <div className="flex gap-2 items-end">
                <SearchableSelect 
                  label="Nuevo Propietario"
                  required
                  className="flex-1"
                  placeholder={loadingData ? 'Cargando propietarios...' : 'Seleccione un propietario'}
                  value={nuevoPropietarioId}
                  onChange={(val) => setNuevoPropietarioId(val.toString())}
                  options={owners.map(o => ({
                    id: o.id,
                    label: `${o.nombre} ${o.paterno}`,
                    sublabel: `DNI: ${o.nro_doc}`
                  }))}
                  disabled={loadingData}
                />
                <button 
                  type="button"
                  onClick={() => setIsPropietarioModalOpen(true)}
                  className="mb-[2px] h-[45px] w-[45px] bg-white border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-500 transition-colors flex items-center justify-center shrink-0 shadow-sm"
                  title="Registrar nuevo propietario"
                >
                  <Plus size={18} />
                </button>
              </div>

              {/* Observaciones */}
              <div>
                <label className="block text-[13px] font-bold text-gray-700 mb-2">
                  Observaciones
                </label>
                <textarea 
                  rows={3}
                  placeholder="Detalles sobre la adopción..."
                  className="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm resize-none"
                />
              </div>

              {/* Campaña Toggle */}
              <div className="pt-2 border-t border-gray-100">
                <label className="flex items-center space-x-3 cursor-pointer mb-4">
                  <input 
                    type="checkbox" 
                    className="w-4 h-4 text-[#2ecc71] rounded border-gray-300 focus:ring-[#2ecc71]"
                    checked={isCampañaHabilitada}
                    onChange={(e) => setIsCampañaHabilitada(e.target.checked)}
                  />
                  <span className="text-[13px] font-bold text-gray-700">Adopción registrada durante una campaña</span>
                </label>

                {isCampañaHabilitada && (
                  <div className="mt-4 animate-in slide-in-from-top-2 fade-in duration-200">
                    <SearchableSelect 
                      label="Campaña de Adopción"
                      placeholder="Seleccione una campaña"
                      value={campaniaId}
                      onChange={(val) => setCampaniaId(val.toString())}
                      options={campaigns.map(c => ({
                        id: c.id,
                        label: c.nombre
                      }))}
                    />
                  </div>
                )}
              </div>

            </div>
          </div>
        </div>
        
        {/* Footer */}
        <div className="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end space-x-3 shrink-0">
          <button 
            onClick={onClose}
            className="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition-colors text-sm"
          >
            Cancelar
          </button>
          <button 
            onClick={handleConfirmAdopcion}
            disabled={loading || loadingData}
            className="bg-pink-500 hover:bg-pink-600 disabled:bg-pink-300 text-white px-5 py-2.5 rounded-xl font-bold shadow-sm transition-all duration-300 text-sm flex items-center space-x-2"
          >
            {loading ? <Loader2 className="animate-spin" size={18} /> : <span>Confirmar Adopción</span>}
          </button>
        </div>
      </div>

      {isPropietarioModalOpen && (
        <PropietarioModal onClose={() => setIsPropietarioModalOpen(false)} />
      )}
    </div>
  );
}
