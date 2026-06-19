'use client';

import { useEffect, useState } from 'react';
import { Eye, X, Loader2, Calendar, Dog } from 'lucide-react';
import { adopcionService } from '@/utils/adopcionService';

export default function AdopcionesList() {
  const [filterType, setFilterType] = useState('campaign'); // 'campaign' | 'dates'
  const [selectedCampaign, setSelectedCampaign] = useState('');
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [selectedObservation, setSelectedObservation] = useState<string | null>(null);

  const [adoptions, setAdoptions] = useState<any[]>([]);
  const [campaigns, setCampaigns] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchInitialData();
  }, []);

  useEffect(() => {
    fetchAdoptions();
  }, [filterType, selectedCampaign, startDate, endDate]);

  const fetchInitialData = async () => {
    try {
      const camps = await adopcionService.getAllCampaigns();
      setCampaigns(camps);
    } catch (err) {
      console.error('Error fetching campaigns:', err);
    }
  };

  const fetchAdoptions = async () => {
    try {
      setLoading(true);
      const filters: any = {};
      if (filterType === 'campaign' && selectedCampaign) {
        filters.campania_id = selectedCampaign;
      } else if (filterType === 'dates' && startDate && endDate) {
        filters.fecha_inicio = startDate;
        filters.fecha_fin = endDate;
      }
      
      const data = await adopcionService.getAllAdoptions(filters);
      setAdoptions(data);
    } catch (err) {
      console.error('Error fetching adoptions:', err);
    } finally {
      setLoading(false);
    }
  };


  return (
    <div className="bg-white/80 backdrop-blur-md rounded-[28px] p-8 shadow-sm border border-white/60">
      <div className="mb-8">
        <h2 className="text-2xl font-bold text-gray-800 mb-6">Registro de Adopciones</h2>

        <div className="bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {/* Main Filter Selection */}
            <div className="space-y-2">
              <label className="block text-sm font-medium text-gray-700">Consultar adopciones por:</label>
              <select
                className="w-full rounded-xl border border-gray-200 p-3 bg-white focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] outline-none transition-all shadow-sm"
                value={filterType}
                onChange={(e) => setFilterType(e.target.value)}
              >
                <option value="campaign">Campaña finalizada</option>
                <option value="dates">Rango de fechas</option>
              </select>
            </div>

            {/* Dynamic Filter Input */}
            {filterType === 'campaign' ? (
              <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Seleccionar Campaña</label>
                <select
                  className="w-full rounded-xl border border-gray-200 p-3 bg-white focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] outline-none transition-all shadow-sm"
                  value={selectedCampaign}
                  onChange={(e) => setSelectedCampaign(e.target.value)}
                >
                  <option value="">-- Todas las campañas --</option>
                  {campaigns.map((c) => (
                    <option key={c.id} value={c.id}>{c.nombre}</option>
                  ))}
                </select>
              </div>
            ) : (
              <div className="flex gap-4">
                <div className="space-y-2 flex-1">
                  <label className="block text-sm font-medium text-gray-700">Fecha Inicial</label>
                  <input
                    type="date"
                    className="w-full rounded-xl border border-gray-200 p-3 bg-white focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] outline-none transition-all shadow-sm"
                    value={startDate}
                    onChange={(e) => setStartDate(e.target.value)}
                  />
                </div>
                <div className="space-y-2 flex-1">
                  <label className="block text-sm font-medium text-gray-700">Fecha Final</label>
                  <input
                    type="date"
                    className="w-full rounded-xl border border-gray-200 p-3 bg-white focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] outline-none transition-all shadow-sm"
                    value={endDate}
                    onChange={(e) => setEndDate(e.target.value)}
                  />
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="overflow-hidden border border-gray-100 rounded-2xl bg-white shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-gray-50/80 border-b border-gray-100 text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                <th className="p-4 w-16 text-center">Obs.</th>
                <th className="p-4">Mascota</th>
                <th className="p-4">Especie / Raza</th>
                <th className="p-4">Nuevo propietario</th>
                <th className="p-4">Fecha de adopción</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-50">
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-4 py-12 text-center">
                    <div className="flex flex-col items-center gap-2">
                      <Loader2 className="animate-spin text-[#11ba82]" size={28} />
                      <span className="text-gray-400 font-medium">Cargando registro de adopciones...</span>
                    </div>
                  </td>
                </tr>
              ) : adoptions.map((adoption) => (
                <tr key={adoption.id} className="hover:bg-green-50/30 transition-colors group">
                  <td className="p-4 text-center align-middle">
                    <button
                      onClick={() => setSelectedObservation(adoption.observaciones)}
                      className="p-2 text-gray-400 group-hover:text-[#11ba82] hover:bg-[#11ba82]/10 rounded-full transition-all"
                      title="Ver observaciones"
                    >
                      <Eye size={20} />
                    </button>
                  </td>
                  <td className="p-4 align-middle">
                    <div className="flex items-center gap-4">
                      <div className="w-11 h-11 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-[#11ba82]">
                        <Dog size={22} />
                      </div>
                      <span className="font-bold text-gray-800 text-[15px]">{adoption.animal.nombre}</span>
                    </div>
                  </td>
                  <td className="p-4 align-middle">
                    <div className="flex flex-col justify-center">
                      <span className="text-[15px] text-gray-700">{adoption.animal.especie?.nombre || 'N/A'}</span>
                      <span className="text-[13px] text-gray-500 font-medium">{adoption.animal.raza?.nombre || ''}</span>
                    </div>
                  </td>
                  <td className="p-4 text-gray-600 align-middle font-medium">{adoption.propietario_nuevo.nombre_completo}</td>
                  <td className="p-4 text-gray-600 align-middle">
                    <div className="flex items-center gap-2">
                      <Calendar size={14} className="text-gray-400" />
                      {new Date(adoption.fecha_adopcion).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                      })}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {!loading && adoptions.length === 0 && (
          <div className="text-center py-16 text-gray-500 bg-gray-50/50">
            <p className="text-lg font-medium text-gray-600 mb-1">No hay registros</p>
            <p className="text-sm">No se encontraron adopciones con los filtros seleccionados.</p>
          </div>
        )}
      </div>

      {/* Observation Modal */}
      {selectedObservation && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
          <div className="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in-95 duration-200 border border-gray-100">
            <div className="flex justify-between items-center mb-5">
              <div className="flex items-center gap-2 text-[#11ba82]">
                <Eye size={24} />
                <h3 className="text-xl font-bold text-gray-800">Observaciones</h3>
              </div>
              <button
                onClick={() => setSelectedObservation(null)}
                className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
              >
                <X size={20} />
              </button>
            </div>
            <div className="p-5 bg-green-50/50 rounded-2xl text-gray-700 leading-relaxed border border-green-100/50">
              {selectedObservation || 'No hay observaciones registradas para esta adopción.'}
            </div>
            <div className="mt-6 flex justify-end">
              <button
                onClick={() => setSelectedObservation(null)}
                className="px-6 py-2.5 bg-[#11ba82] text-white font-medium rounded-xl hover:bg-[#0e9d6d] active:bg-[#0c8a60] transition-colors shadow-sm shadow-[#11ba82]/20"
              >
                Cerrar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
