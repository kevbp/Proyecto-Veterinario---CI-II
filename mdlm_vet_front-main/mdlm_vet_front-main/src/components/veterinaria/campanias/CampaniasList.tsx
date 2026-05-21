'use client';

import { useState, useEffect } from 'react';
import { Search, Plus, FileText, Check, X, Table, Flag, AlertTriangle, Loader2 } from 'lucide-react';
import Link from 'next/link';
import { campaniaService, Campania } from '@/utils/campaniaService';
import { inventarioService, Medicamento } from '@/utils/inventarioService';
import { useRouter } from 'next/navigation';

const mockCampaniasData: Campania[] = [
  {
    id: '1',
    nombre: 'Campaña de Vacunación Primavera 2024',
    descripcion: 'Vacunación masiva contra rabia y parvovirus para perros y gatos en todo el distrito durante la primavera de 2024. Incluye desparasitación gratuita.',
    lugar: 'Centro Comunitario de Salud Animal',
    fecha_hora_inicio: '2024-09-01T08:00:00Z',
    fecha_hora_fin: '2024-09-30T17:00:00Z',
    estado: 'programada',
    responsable_nombre: 'Juan Perez Gomez'
  },
  {
    id: '2',
    nombre: 'Esterilización Canina Zona Sur',
    descripcion: 'Campaña de esterilización a bajo costo enfocada en la zona sur del municipio para controlar la sobrepoblación canina.',
    lugar: 'Parque Zonal Sur',
    fecha_hora_inicio: '2024-10-15T07:00:00Z',
    fecha_hora_fin: '2024-10-17T18:00:00Z',
    estado: 'en_curso',
    responsable_nombre: 'Ana Maria Torres'
  },
  {
    id: '3',
    nombre: 'Registro Municipal de Mascotas',
    descripcion: 'Jornada intensiva para el registro y entrega de DNI para mascotas de los vecinos del municipio.',
    lugar: 'Plaza Principal',
    fecha_hora_inicio: '2024-05-10T09:00:00Z',
    fecha_hora_fin: '2024-05-12T16:00:00Z',
    estado: 'finalizada',
    responsable_nombre: 'Carlos Diaz Ruiz'
  }
];

export default function CampaniasList() {
  const router = useRouter();
  const [campanias, setCampanias] = useState<Campania[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [loading, setLoading] = useState(true);
  const [campaniaToStart, setCampaniaToStart] = useState<Campania | null>(null);
  const [campaniaToCancel, setCampaniaToCancel] = useState<Campania | null>(null);
  const [campaniaToFinish, setCampaniaToFinish] = useState<Campania | null>(null);

  // Finish Modal State
  const [medicamentos, setMedicamentos] = useState<Medicamento[]>([]);
  const [insumosConsumidos, setInsumosConsumidos] = useState<Array<{ medicamento_id: string, cantidad: number }>>([]);
  const [isFinishing, setIsFinishing] = useState(false);

  useEffect(() => {
    fetchCampanias();
  }, []);

  const fetchCampanias = async () => {
    try {
      setLoading(true);
      const data = await campaniaService.getAll();
      setCampanias(data);
    } catch (err) {
      console.error('Error fetching campanias:', err);
    } finally {
      setLoading(false);
    }
  };

  const fetchMedicamentos = async () => {
    try {
      const data = await inventarioService.getAllMedicamentos();
      setMedicamentos(data);
    } catch (err) {
      console.error('Error fetching medicamentos:', err);
    }
  };

  const filteredCampanias = campanias.filter(c =>
    c.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
    (c.lugar || '').toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleStartConfirm = async () => {
    if (!campaniaToStart) return;
    try {
      await campaniaService.iniciar(campaniaToStart.id);
      setCampaniaToStart(null);
      fetchCampanias();
    } catch (err) {
      alert('Error al iniciar la campaña');
    }
  };

  const handleCancelConfirm = async () => {
    if (!campaniaToCancel) return;
    try {
      await campaniaService.cancelar(campaniaToCancel.id);
      setCampaniaToCancel(null);
      fetchCampanias();
    } catch (err) {
      alert('Error al cancelar la campaña');
    }
  };

  const handleFinishConfirm = async () => {
    if (!campaniaToFinish) return;
    try {
      setIsFinishing(true);
      await campaniaService.finalizar(campaniaToFinish.id, {
        insumos_consumidos: insumosConsumidos
      });
      setCampaniaToFinish(null);
      setInsumosConsumidos([]);
      fetchCampanias();
    } catch (err) {
      alert('Error al finalizar la campaña. Verifique el stock de insumos.');
    } finally {
      setIsFinishing(false);
    }
  };

  useEffect(() => {
    if (campaniaToFinish) {
      fetchMedicamentos();
    }
  }, [campaniaToFinish]);

  const addInsumo = () => {
    setInsumosConsumidos([...insumosConsumidos, { medicamento_id: '', cantidad: 1 }]);
  };

  const updateInsumo = (index: number, field: string, value: any) => {
    const newInsumos = [...insumosConsumidos];
    newInsumos[index] = { ...newInsumos[index], [field]: value };
    setInsumosConsumidos(newInsumos);
  };

  const removeInsumo = (index: number) => {
    setInsumosConsumidos(insumosConsumidos.filter((_, i) => i !== index));
  };

  const getEstadoLabel = (estado: string) => {
    switch (estado) {
      case 'programada':
      case 'planificada': return 'Planificada';
      case 'en_curso': return 'En Curso';
      case 'finalizada': return 'Finalizada';
      case 'cancelada': return 'Cancelada';
      default: return estado;
    }
  };

  const handleRowClick = (campania: Campania) => {
    if (campania.estado.toLowerCase() === 'planificada' || campania.estado.toLowerCase() === 'programada') {
      router.push(`/campanias/crear?edit=${campania.id}`);
    }
  };

  const getEstadoBadgeColor = (estado: string) => {
    switch (estado) {
      case 'programada':
      case 'planificada': return 'bg-blue-100/80 text-blue-700';
      case 'en_curso': return 'bg-yellow-100/80 text-yellow-700';
      case 'finalizada': return 'bg-green-100/80 text-green-700';
      case 'cancelada': return 'bg-red-100/80 text-red-600';
      default: return 'bg-gray-100 text-gray-600';
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
  };

  return (
    <div className="bg-white/80 backdrop-blur-md rounded-[28px] p-8 shadow-sm border border-white/60">
      {/* Header section */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
          <h2 className="text-2xl font-bold text-gray-800">Gestión de Campañas</h2>
          <p className="text-gray-500 text-sm mt-1">Administra las campañas de salud y registro animal</p>
        </div>

        <div className="flex items-center gap-4 w-full md:w-auto">
          <div className="relative flex-1 md:w-64">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
            <input
              type="text"
              placeholder="Buscar campaña..."
              className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-[#11ba82] focus:border-[#11ba82] outline-none transition-all text-sm shadow-sm"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
          <Link
            href="/campanias/crear"
            className="flex items-center gap-2 bg-gradient-to-r from-[#015f33] to-[#2ecc71] text-white px-5 py-2.5 rounded-xl font-medium hover:opacity-90 transition-opacity shadow-sm whitespace-nowrap"
          >
            <Plus size={18} strokeWidth={2} />
            Nueva Campaña
          </Link>
        </div>
      </div>

      {/* Table */}
      <div className="overflow-hidden border border-gray-100 rounded-2xl bg-white shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-gray-50/80 border-b border-gray-100 text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                <th className="p-4 w-24 text-center">Acciones</th>
                <th className="p-4">Nombre</th>
                <th className="p-4">Lugar</th>
                <th className="p-4 text-center">Descripción</th>
                <th className="p-4">Fecha de Inicio</th>
                <th className="p-4">Fecha de Fin</th>
                <th className="p-4">Responsable</th>
                <th className="p-4 text-center">Estado</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-50">
              {loading ? (
                <tr>
                  <td colSpan={8} className="p-12 text-center text-gray-400">
                    <div className="flex flex-col items-center gap-2">
                      <Loader2 size={32} className="animate-spin text-[#11ba82]" />
                      <span>Cargando campañas...</span>
                    </div>
                  </td>
                </tr>
              ) : filteredCampanias.map((campania) => (
                <tr
                  key={campania.id}
                  onClick={() => handleRowClick(campania)}
                  className={`hover:bg-green-50/30 transition-colors group ${(campania.estado.toLowerCase() === 'planificada' || campania.estado.toLowerCase() === 'programada')
                      ? 'cursor-pointer'
                      : ''
                    }`}
                >
                  <td className="p-4 align-middle" onClick={(e) => e.stopPropagation()}>
                    <div className="flex items-center justify-center gap-1">
                      {(campania.estado === 'programada' || campania.estado === 'planificada') && (
                        <>
                          <button
                            onClick={() => setCampaniaToStart(campania)}
                            className="p-1.5 text-gray-400 hover:text-[#11ba82] hover:bg-[#11ba82]/10 rounded-full transition-all"
                            title="Iniciar campaña"
                          >
                            <Check size={18} strokeWidth={2} />
                          </button>
                          <button
                            onClick={() => setCampaniaToCancel(campania)}
                            className="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all"
                            title="Cancelar campaña"
                          >
                            <X size={18} strokeWidth={2} />
                          </button>
                        </>
                      )}

                      {campania.estado === 'en_curso' && (
                        <>
                          <Link
                            href={`/campanias/${campania.id}/estadisticas`}
                            className="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-full transition-all"
                            title="Ver estadísticas"
                          >
                            <Table size={18} strokeWidth={1.5} />
                          </Link>
                          <button
                            onClick={() => setCampaniaToFinish(campania)}
                            className="p-1.5 text-gray-400 hover:text-[#11ba82] hover:bg-[#11ba82]/10 rounded-full transition-all"
                            title="Finalizar campaña"
                          >
                            <Flag size={18} strokeWidth={1.5} />
                          </button>
                        </>
                      )}

                      {(campania.estado === 'finalizada' || campania.estado === 'cancelada') && (
                        <div className="text-gray-300 text-xs italic">N/A</div>
                      )}
                    </div>
                  </td>
                  <td className="p-4 align-middle">
                    <span className="font-bold text-gray-800 text-[14px]">
                      {campania.nombre}
                    </span>
                  </td>
                  <td className="p-4 align-middle">
                    <span className="text-[14px] text-gray-700">
                      {campania.lugar}
                    </span>
                  </td>
                  <td className="p-4 align-middle text-center">
                    <div
                      className="inline-flex p-2 text-gray-400 hover:text-[#11ba82] hover:bg-[#11ba82]/10 rounded-xl transition-all cursor-help relative group/tooltip"
                    >
                      <FileText size={20} strokeWidth={1.5} />
                      {/* Tooltip */}
                      <div
                        className="absolute left-full top-1/2 -translate-y-1/2 ml-3 w-64 p-3 bg-gray-900 text-white text-[12px] leading-relaxed rounded-xl opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all shadow-xl z-50 pointer-events-none text-left prose prose-invert prose-sm max-w-none"
                        dangerouslySetInnerHTML={{ __html: campania.descripcion }}
                      />
                      <div className="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all"></div>
                    </div>
                  </td>
                  <td className="p-4 text-gray-600 align-middle text-[14px]">{campania.fecha_hora_inicio ? formatDate(campania.fecha_hora_inicio) : '-'}</td>
                  <td className="p-4 text-gray-600 align-middle text-[14px]">{campania.fecha_hora_fin ? formatDate(campania.fecha_hora_fin) : '-'}</td>
                  <td className="p-4 text-gray-600 align-middle text-[14px]">{campania.responsable?.nombre || 'N/A'}</td>
                  <td className="p-4 align-middle text-center">
                    <span className={`inline-flex items-center justify-center px-3 py-1 rounded-full text-[13px] font-bold min-w-[90px] whitespace-nowrap ${getEstadoBadgeColor(campania.estado)}`}>
                      {getEstadoLabel(campania.estado)}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {filteredCampanias.length === 0 && (
            <div className="text-center py-16 text-gray-500 bg-gray-50/50">
              <p className="text-lg font-medium text-gray-600 mb-1">No hay campañas</p>
              <p className="text-sm">No se encontraron campañas con los filtros seleccionados.</p>
            </div>
          )}
        </div>
      </div>

      {/* Start Confirmation Modal */}
      {campaniaToStart && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
          <div className="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in-95 duration-200 border border-gray-100">
            <div className="flex flex-col items-center text-center mb-6">
              <div className="w-16 h-16 bg-[#11ba82]/10 text-[#11ba82] rounded-full flex items-center justify-center mb-4">
                <Check size={32} strokeWidth={2} />
              </div>
              <h3 className="text-xl font-bold text-gray-800 mb-2">Iniciar Campaña</h3>
              <p className="text-gray-600">
                ¿Deseas iniciar la campaña <strong className="text-gray-800">{campaniaToStart.nombre}</strong>?
              </p>
              <p className="text-gray-500 text-[13px] mt-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                Su estado pasará a "En Curso" y se habilitará la recolección de estadísticas.
              </p>
            </div>

            <div className="flex justify-between gap-3">
              <button
                onClick={() => setCampaniaToStart(null)}
                className="flex-1 px-6 py-2.5 bg-white text-gray-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-colors"
              >
                Volver
              </button>
              <button
                onClick={handleStartConfirm}
                className="flex-1 px-6 py-2.5 bg-[#11ba82] text-white font-medium rounded-xl hover:bg-[#0e9d6d] active:bg-[#0c8a60] transition-colors shadow-sm shadow-[#11ba82]/20"
              >
                Sí, iniciar
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Cancel Confirmation Modal */}
      {campaniaToCancel && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
          <div className="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in-95 duration-200 border border-gray-100">
            <div className="flex flex-col items-center text-center mb-6">
              <div className="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-4">
                <AlertTriangle size={32} />
              </div>
              <h3 className="text-xl font-bold text-gray-800 mb-2">Cancelar Campaña</h3>
              <p className="text-gray-600">
                ¿Estás seguro que deseas cancelar la campaña <strong className="text-gray-800">{campaniaToCancel.nombre}</strong>?
              </p>
              <p className="text-gray-500 text-[13px] mt-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                Esta acción no se puede deshacer y su estado pasará a ser "Cancelada".
              </p>
            </div>

            <div className="flex justify-between gap-3">
              <button
                onClick={() => setCampaniaToCancel(null)}
                className="flex-1 px-6 py-2.5 bg-white text-gray-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-colors"
              >
                Volver
              </button>
              <button
                onClick={handleCancelConfirm}
                className="flex-1 px-6 py-2.5 bg-red-500 text-white font-medium rounded-xl hover:bg-red-600 active:bg-red-700 transition-colors shadow-sm shadow-red-500/20"
              >
                Sí, cancelar
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Finish Confirmation Modal with Supplies */}
      {campaniaToFinish && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
          <div className="bg-white rounded-3xl p-6 max-w-xl w-full shadow-2xl animate-in fade-in zoom-in-95 duration-200 border border-gray-100 overflow-y-auto max-h-[90vh]">
            <div className="flex flex-col items-center text-center mb-6">
              <div className="w-16 h-16 bg-[#11ba82]/10 text-[#11ba82] rounded-full flex items-center justify-center mb-4">
                <Flag size={32} strokeWidth={1.5} />
              </div>
              <h3 className="text-xl font-bold text-gray-800 mb-2">Finalizar Campaña</h3>
              <p className="text-gray-600">
                Para finalizar la campaña <strong className="text-gray-800">{campaniaToFinish.nombre}</strong>, registre los insumos consumidos.
              </p>
            </div>

            <div className="space-y-4 mb-8">
              <div className="flex justify-between items-center px-1">
                <span className="text-sm font-bold text-gray-700 uppercase tracking-wider">Insumos / Medicamentos</span>
                <button
                  onClick={addInsumo}
                  className="text-[12px] font-bold text-[#11ba82] hover:underline flex items-center gap-1"
                >
                  <Plus size={14} />
                  Agregar insumo
                </button>
              </div>

              {insumosConsumidos.length === 0 ? (
                <div className="py-8 text-center border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 text-sm">
                  No se han registrado insumos.
                </div>
              ) : (
                <div className="space-y-3">
                  {insumosConsumidos.map((insumo, idx) => (
                    <div key={idx} className="flex gap-3 animate-in slide-in-from-top-2 duration-200">
                      <select
                        value={insumo.medicamento_id}
                        onChange={(e) => updateInsumo(idx, 'medicamento_id', e.target.value)}
                        className="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#11ba82]/30"
                      >
                        <option value="">Seleccione un insumo</option>
                        {medicamentos.map(m => (
                          <option key={m.id} value={m.id}>{m.nombre} (Stock: {m.stock})</option>
                        ))}
                      </select>
                      <input
                        type="number"
                        min="1"
                        placeholder="Cant."
                        value={insumo.cantidad}
                        onChange={(e) => updateInsumo(idx, 'cantidad', parseInt(e.target.value))}
                        className="w-20 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#11ba82]/30"
                      />
                      <button
                        onClick={() => removeInsumo(idx)}
                        className="p-2 text-gray-400 hover:text-red-500 transition-colors"
                      >
                        <X size={18} />
                      </button>
                    </div>
                  ))}
                </div>
              )}
            </div>

            <div className="flex justify-between gap-3">
              <button
                disabled={isFinishing}
                onClick={() => setCampaniaToFinish(null)}
                className="flex-1 px-6 py-2.5 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors disabled:opacity-50"
              >
                Volver
              </button>
              <button
                disabled={isFinishing}
                onClick={handleFinishConfirm}
                className="flex-1 px-6 py-2.5 bg-[#11ba82] text-white font-bold rounded-xl hover:bg-[#0e9d6d] active:bg-[#0c8a60] transition-colors shadow-sm disabled:opacity-50 flex items-center justify-center gap-2"
              >
                {isFinishing && <Loader2 size={16} className="animate-spin" />}
                Finalizar campaña
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
