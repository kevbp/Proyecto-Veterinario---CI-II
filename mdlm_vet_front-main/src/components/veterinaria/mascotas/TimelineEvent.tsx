import { Calendar, Stethoscope, Syringe, Bug, FileSearch, Pill, CheckCircle2 } from 'lucide-react';

export interface ClinicEvent {
  id: string;
  eventable_id: string;
  fecha_hora: string;
  tipo_evento: string;
  eventable_type: string;
  detalles: any;
}

interface TimelineEventProps {
  event: ClinicEvent;
  hideDate?: boolean;
}

/**
 * Parsea fecha_hora del resource de historial de forma segura.
 * Si viene como ISO 8601 (con T), lo parsea directamente.
 * Si viene como date-only (YYYY-MM-DD), lo trata como fecha local para evitar desfase UTC.
 */
function parseEventDate(raw: string): Date {
  if (!raw) return new Date();
  if (raw.includes('T')) return new Date(raw);
  // Date-only: reemplazar guiones por barras para interpretar como local
  return new Date(raw.replace(/-/g, '/'));
}

export default function TimelineEvent({ event, hideDate = false }: TimelineEventProps) {
  const isConsulta = event.tipo_evento === 'Consulta Médica';
  const isVacuna = event.tipo_evento === 'Vacunación';
  const isDesparasitacion = event.tipo_evento === 'Desparasitación';
  const isExamen = event.tipo_evento === 'Examen Médico';

  const date = parseEventDate(event.fecha_hora);
  const formattedDate = date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
  const formattedTime = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });

  return (
    <div className={`relative ${hideDate ? 'py-2' : 'pl-8 sm:pl-36 py-4'} group`}>

      {/* === Desktop Date Column (left side, absolute) === */}
      {!hideDate && (
        <div className="hidden sm:block absolute left-0 top-5 w-28 text-right pr-3">
          <div className="text-[12px] font-bold text-gray-800 leading-snug">{formattedDate}</div>
          <div className="text-[11px] font-medium text-gray-400">{formattedTime}</div>
        </div>
      )}

      {/* === Timeline vertical line === */}
      {!hideDate && (
        <div className="absolute left-4 sm:left-[7.5rem] top-0 bottom-0 w-px bg-gradient-to-b from-gray-200 via-gray-200 to-transparent group-last:from-gray-200 group-last:to-transparent"></div>
      )}

      {/* === Timeline dot/icon === */}
      {!hideDate && (
        <div className={`absolute left-4 sm:left-[7.5rem] top-5 -translate-x-1/2 flex items-center justify-center w-7 h-7 rounded-full bg-white border-[2.5px] shadow-sm z-10 transition-transform group-hover:scale-110
          ${isConsulta ? 'border-blue-400 text-blue-500' : 
            isVacuna ? 'border-emerald-400 text-emerald-500' : 
            isDesparasitacion ? 'border-purple-400 text-purple-500' : 
            'border-orange-400 text-orange-500'}`}
        >
          {isConsulta && <Stethoscope size={12} strokeWidth={2.5} />}
          {isVacuna && <Syringe size={12} strokeWidth={2.5} />}
          {isDesparasitacion && <Bug size={12} strokeWidth={2.5} />}
          {isExamen && <FileSearch size={12} strokeWidth={2.5} />}
        </div>
      )}

      {/* === Mobile Date (visible only on small screens) === */}
      {!hideDate && (
        <div className="sm:hidden mb-1.5">
          <span className="text-xs font-bold text-gray-800 mr-2">{formattedDate}</span>
          <span className="text-[11px] font-medium text-gray-400">{formattedTime}</span>
        </div>
      )}

      {/* === Content Card === */}
      <div className={`bg-white rounded-xl ${hideDate ? 'p-3' : 'p-4'} border shadow-sm transition-shadow hover:shadow-md
        ${isConsulta ? 'border-blue-100' :
          isVacuna ? 'border-emerald-100' :
            isDesparasitacion ? 'border-purple-100' :
              'border-orange-100'}`}
      >
        {/* Header */}
        <div className="flex justify-between items-start mb-2">
          <h4 className="text-sm font-bold text-gray-800 flex items-center gap-2">
            {event.tipo_evento}
            {isExamen && event.detalles?.estado === 'Completado' && (
              <CheckCircle2 size={14} className="text-emerald-500" />
            )}
          </h4>
        </div>

        {/* Dynamic Body */}
        <div className="text-[12px] text-gray-600 space-y-2.5">

          {/* ── CONSULTA ── */}
          {isConsulta && event.detalles && (
            <>
              <div>
                <strong className="text-gray-800 block mb-0.5">Motivo:</strong>
                {event.detalles.motivo}
              </div>
              <div className="bg-blue-50/50 p-3 rounded-xl border border-blue-100/50">
                <strong className="text-blue-900 block mb-0.5">Diagnóstico:</strong>
                <p className="text-blue-800">{event.detalles.diagnostico}</p>
              </div>
              {event.detalles.receta && event.detalles.receta.length > 0 && (
                <div className="border-t border-gray-100 pt-3">
                  <strong className="text-gray-800 flex items-center gap-1.5 mb-1.5">
                    <Pill size={13} className="text-blue-500" /> Receta Médica
                  </strong>
                  <ul className="space-y-1.5">
                    {event.detalles.receta.map((receta: any, i: number) => (
                      <li key={i} className="text-[12px]">
                        <p className="font-medium text-gray-700">{receta.indicaciones_generales}</p>
                        {receta.lineas_medicamento?.map((linea: any, j: number) => (
                          <div key={j} className="flex items-start gap-2 mt-1 ml-1">
                            <div className="w-1.5 h-1.5 rounded-full bg-blue-300 mt-1.5 shrink-0"></div>
                            <div>
                              <span className="font-semibold text-gray-800">
                                {linea.medicamento_nombre || linea.medicamento_id}
                              </span> - {linea.cantidad}
                              <p className="text-gray-500 italic">{linea.instruccion_especifica}</p>
                            </div>
                          </div>
                        ))}
                      </li>
                    ))}
                  </ul>
                </div>
              )}
            </>
          )}

          {/* ── VACUNA ── */}
          {isVacuna && event.detalles && (
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Esquema / Vacuna</strong>
                <span className="font-medium text-gray-800">
                  {/* El resource envía esquema_vacuna como string directo */}
                  {event.detalles.esquema_vacuna || 'N/A'}
                </span>
              </div>
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Dosis</strong>
                <span className="font-medium text-gray-800">{event.detalles.nro_dosis || 'N/A'}</span>
              </div>
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Medicamento</strong>
                <span className="font-medium text-gray-800">
                  {/* El resource envía medicamento como string directo (nombre) */}
                  {event.detalles.medicamento || 'N/A'}
                </span>
              </div>
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Fabricante</strong>
                <span className="font-medium text-gray-800">{event.detalles.fabricante || 'N/A'}</span>
              </div>
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Lote</strong>
                <span className="font-medium text-gray-800">{event.detalles.lote || 'N/A'}</span>
              </div>
              {event.detalles.fecha_proxima && (
                <div className="bg-emerald-50 p-2 rounded-lg border border-emerald-100">
                  <strong className="text-emerald-700 block text-[10px] uppercase tracking-wider mb-0.5 flex items-center gap-1">
                    <Calendar size={11} /> Próxima Dosis
                  </strong>
                  <span className="font-bold text-emerald-800 text-[12px]">
                    {parseEventDate(event.detalles.fecha_proxima).toLocaleDateString('es-ES')}
                  </span>
                </div>
              )}
              {event.detalles.observaciones && (
                <div className="col-span-2 sm:col-span-3">
                  <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Observaciones</strong>
                  <p>{event.detalles.observaciones}</p>
                </div>
              )}
            </div>
          )}

          {/* ── DESPARASITACIÓN ── */}
          {isDesparasitacion && event.detalles && (
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Medicamento</strong>
                <span className="font-medium text-gray-800">
                  {/* El resource envía medicamento como string directo */}
                  {event.detalles.medicamento || 'N/A'}
                </span>
              </div>
              <div>
                <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Dosis / Vía</strong>
                <span className="font-medium text-gray-800">{event.detalles.dosis} - {event.detalles.via}</span>
              </div>
              {event.detalles.fecha_aplicacion_sgte && (
                <div className="bg-purple-50 p-2 rounded-lg border border-purple-100 sm:col-span-2">
                  <strong className="text-purple-700 block text-[10px] uppercase tracking-wider mb-0.5 flex items-center gap-1">
                    <Calendar size={11} /> Próxima Aplicación
                  </strong>
                  <span className="font-bold text-purple-800 text-[12px]">
                    {parseEventDate(event.detalles.fecha_aplicacion_sgte).toLocaleDateString('es-ES')}
                  </span>
                </div>
              )}
              {event.detalles.observaciones && (
                <div className="sm:col-span-2">
                  <strong className="text-gray-500 block text-[10px] uppercase tracking-wider mb-0.5">Observaciones</strong>
                  <p>{event.detalles.observaciones}</p>
                </div>
              )}
            </div>
          )}

          {/* ── EXAMEN ── */}
          {isExamen && event.detalles && (
            <div className="space-y-3">
              <div>
                <strong className="text-gray-800 block mb-0.5">Nombre del Examen:</strong>
                <span className="font-medium">{event.detalles.nombre}</span>
              </div>
              {event.detalles.descripcion && (
                <p className="text-gray-500">{event.detalles.descripcion}</p>
              )}
              {event.detalles.resultado && (
                <div className="bg-orange-50/50 p-3 rounded-xl border border-orange-100/50">
                  <strong className="text-orange-900 block mb-1.5 border-b border-orange-200/50 pb-1">Resultados</strong>
                  <div className="space-y-1 text-[12px]">
                    <div><strong className="text-orange-800">Hallazgos:</strong> {event.detalles.resultado.hallazgos}</div>
                    <div><strong className="text-orange-800">Valores:</strong> {event.detalles.resultado.valores}</div>
                    <div><strong className="text-orange-800">Interpretación:</strong> {event.detalles.resultado.interpretacion}</div>
                  </div>
                </div>
              )}
            </div>
          )}

        </div>
      </div>
    </div>
  );
}
