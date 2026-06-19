'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { ChevronRight, Dog, FileText, Activity, AlertTriangle, Syringe, Bug, Scale, HeartPulse, History, Plus, Download, Loader2 } from 'lucide-react';
import TimelineEvent, { ClinicEvent } from './TimelineEvent';
import { mascotaService } from '@/utils/mascotaService';
import { Mascota } from '@/interfaces/Mascota';
import { useAuthStore } from '@/store/useAuthStore';

export default function MascotaHistorial({ id }: { id: string }) {
  const [mascota, setMascota] = useState<Mascota | null>(null);
  const [timeline, setTimeline] = useState<ClinicEvent[]>([]);
  const [loading, setLoading] = useState(true);

  const { user } = useAuthStore();
  const isOwner = user?.roles?.includes('propietario');

  useEffect(() => {
    if (user) {
      fetchHistorial();
    }
  }, [id, user, isOwner]);

  const fetchHistorial = async () => {
    try {
      setLoading(true);
      const [animalData, timelineData] = await Promise.all([
        isOwner ? mascotaService.getClienteMascotaById(id) : mascotaService.getAnimalById(id),
        mascotaService.getAnimalTimeline(id)
      ]);
      setMascota(animalData);
      setTimeline(timelineData);
    } catch (err) {
      console.error('Error fetching historial:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center py-20 gap-4">
        <Loader2 className="animate-spin text-[#2ecc71]" size={40} />
        <p className="text-gray-500 font-medium text-lg">Cargando historial clínico...</p>
      </div>
    );
  }

  if (!mascota) {
    return (
      <div className="text-center py-20">
        <p className="text-gray-500 text-lg">No se encontró la información de la mascota.</p>
        <Link href="/mascotas" className="text-[#2ecc71] hover:underline mt-4 inline-block">Volver al directorio</Link>
      </div>
    );
  }

  // Extraer el peso más reciente de las consultas
  const consultas = timeline.filter(e => e.tipo_evento === 'Consulta Médica');
  const ultimoPeso = consultas.length > 0 ? consultas[0].detalles.peso_registrado : null;

  return (
    <div className="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-500">
      {/* Breadcrumb & Acciones */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div className="flex items-center text-sm text-gray-500">
          <Link href="/mascotas" className="hover:text-[#015f33] transition-colors">Directorio</Link>
          <ChevronRight size={16} className="mx-2" />
          <span className="text-gray-800 font-semibold">Historial Clínico</span>
        </div>

        <div className="flex items-center gap-3 w-full sm:w-auto">
          <button className="flex-1 sm:flex-none flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-700 to-blue-500 hover:shadow-lg hover:shadow-blue-500/30 text-white px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap">
            <Download size={18} />
            <span>Exportar en PDF</span>
          </button>
          {!isOwner && (
            <Link 
              href={`/atenciones/nueva?mascota_id=${id}`}
              className="flex-1 sm:flex-none flex items-center justify-center space-x-2 bg-gradient-to-r from-[#015f33] to-[#2ecc71] hover:shadow-lg hover:shadow-[#2ecc71]/30 text-white px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap"
            >
              <Plus size={18} />
              <span>Nueva atención</span>
            </Link>
          )}
        </div>
      </div>

      <div className="space-y-6">

        {/* Fila Superior: Datos Generales y Alergias */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

          {/* Tarjeta Mascota */}
          <div className="bg-white/80 backdrop-blur-md rounded-[32px] p-8 shadow-sm border border-white/60">
            <div className="flex flex-col items-center text-center">
              <h2 className="text-2xl font-bold text-gray-800">{mascota.nombre}</h2>
            </div>

            <div className="mt-8 space-y-4">
              <div className="flex justify-between items-center py-2 border-b border-gray-100">
                <span className="text-gray-500 text-[13px] font-semibold">Propietario</span>
                <span className="text-gray-800 font-medium text-sm text-right">
                  {mascota.propietario}<br />
                  <span className="text-gray-400 text-xs">{mascota.propietario_celular}</span>
                </span>
              </div>
              <div className="flex justify-between items-center py-2 border-b border-gray-100">
                <span className="text-gray-500 text-[13px] font-semibold">Especie / Raza</span>
                <span className="text-gray-800 font-medium text-sm text-right">
                  {mascota.especie || 'N/A'}<br />
                  <span className="text-gray-400 text-xs">{mascota.raza || 'N/A'}</span>
                </span>
              </div>
              <div className="flex justify-between items-center py-2 border-b border-gray-100">
                <span className="text-gray-500 text-[13px] font-semibold">Sexo</span>
                <span className="text-gray-800 font-medium text-sm">{mascota.sexo}</span>
              </div>
              <div className="flex justify-between items-center py-2 border-b border-gray-100">
                <span className="text-gray-500 text-[13px] font-semibold">Esterilizado</span>
                <span className="text-gray-800 font-medium text-sm">{mascota.esterilizacion ? 'Sí' : 'No'}</span>
              </div>
              <div className="flex justify-between items-center py-2">
                <span className="text-gray-500 text-[13px] font-semibold flex items-center gap-1.5"><Scale size={14} /> Peso Actual</span>
                <span className="text-gray-800 font-bold text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded-xl">
                  {ultimoPeso ? `${ultimoPeso} kg` : 'N/A'}
                </span>
              </div>
            </div>
          </div>

          {/* Tarjeta Alergias y Condiciones */}
          <div className="bg-white/80 backdrop-blur-md rounded-[32px] p-8 shadow-sm border border-white/60 flex flex-col">

            {/* Alergias */}
            <div>
              <h3 className="text-[13px] font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2 mb-3">
                <AlertTriangle size={16} className="text-red-500" /> Alergias
              </h3>
              {mascota.alergias && mascota.alergias.length > 0 ? (
                <div className="space-y-2">
                  {mascota.alergias.map((a: any, idx: number) => (
                    <div key={a.id || idx} className="bg-red-50/50 border border-red-100 p-3 rounded-xl flex flex-col gap-1 text-[13px]">
                      <span className="font-bold text-red-900">{a.alergia || 'Alergia'}</span>
                      <div className="flex gap-2">
                        <span className="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-red-100 text-red-700">Sev: {a.severidad}</span>
                        <span className="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-white text-gray-500 border border-gray-200">{a.estado_clinico}</span>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-sm text-gray-400 italic">No registra alergias.</p>
              )}
            </div>

            <div className="h-px bg-gray-100 w-full my-5"></div>

            {/* Condiciones */}
            <div>
              <h3 className="text-[13px] font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2 mb-3">
                <HeartPulse size={16} className="text-orange-500" /> Condiciones
              </h3>
              {mascota.condiciones && mascota.condiciones.length > 0 ? (
                <div className="space-y-2">
                  {mascota.condiciones.map((c: any, idx: number) => (
                    <div key={c.id || idx} className="bg-orange-50/50 border border-orange-100 p-3 rounded-xl flex flex-col gap-1 text-[13px]">
                      <span className="font-bold text-orange-900">{c.condicion || 'Condición'}</span>
                      <div className="flex gap-2">
                        {c.severidad && <span className="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-orange-100 text-orange-700">Sev: {c.severidad}</span>}
                        <span className="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-white text-gray-500 border border-gray-200">{c.estado_clinico}</span>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-sm text-gray-400 italic">No registra condiciones previas.</p>
              )}
            </div>

          </div>
        </div>

        {/* Fila Inferior: Timeline */}
        <div className="bg-white/80 backdrop-blur-md rounded-[32px] p-5 sm:p-6 shadow-sm border border-white/60">
          <div className="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div className="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500">
              <History size={20} />
            </div>
            <div>
              <h2 className="text-xl font-bold text-gray-800">Línea de Tiempo Clínica</h2>
              <p className="text-xs text-gray-500">Registro de eventos médicos ordenados por fecha</p>
            </div>
          </div>

          {timeline.length === 0 ? (
            <div className="text-center py-16 text-gray-500">
              <p>Esta mascota aún no tiene eventos registrados en su historial clínico.</p>
            </div>
          ) : (() => {
            // Group vaccine/desparasitación events that belong to a consultation
            const consultaChildEvents = new Map<string, typeof timeline>();
            const standaloneEvents: typeof timeline = [];

            timeline.forEach(event => {
              const consultaId = event.detalles?.consulta_id;
              if (consultaId && event.tipo_evento !== 'Consulta Médica') {
                if (!consultaChildEvents.has(consultaId)) {
                  consultaChildEvents.set(consultaId, []);
                }
                consultaChildEvents.get(consultaId)!.push(event);
              } else {
                standaloneEvents.push(event);
              }
            });

            return (
              <div className="relative">
                {standaloneEvents.map((event) => (
                  <div key={event.id}>
                    <TimelineEvent event={event} />
                    {/* Show child events (vaccinations, etc.) nested under this consultation */}
                    {event.tipo_evento === 'Consulta Médica' && consultaChildEvents.has(event.eventable_id) && (
                      <div className="ml-8 sm:ml-36 pl-4 border-l-2 border-dashed border-emerald-200 mb-2">
                        <p className="text-[10px] font-bold text-emerald-600 uppercase tracking-widest pt-1 pb-1 pl-2">
                          Procedimientos vinculados
                        </p>
                        {consultaChildEvents.get(event.eventable_id)!.map(childEvent => (
                          <div key={childEvent.id} className="relative py-1.5">
                            <div className="absolute -left-[17px] top-3 w-3 h-3 rounded-full bg-emerald-100 border-2 border-emerald-400 flex items-center justify-center">
                              <div className="w-1 h-1 rounded-full bg-emerald-500"></div>
                            </div>
                            <div className="bg-emerald-50/40 border border-emerald-100/50 rounded-xl ml-1">
                              <TimelineEvent event={childEvent} hideDate={true} />
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
                  </div>
                ))}
              </div>
            );
          })()}
        </div>
      </div>
    </div>
  );
}
