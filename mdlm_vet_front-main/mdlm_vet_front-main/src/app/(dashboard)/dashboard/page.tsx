'use client';

import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from 'recharts';
import {
  Dog, HeartPulse, Activity, Syringe, ClipboardList, Home, CheckCircle, FileText
} from 'lucide-react';
import { useEffect, useState } from 'react';
import { mascotaService } from '@/utils/mascotaService';
import { campaniaService, Campania } from '@/utils/campaniaService';
import { adopcionService } from '@/utils/adopcionService';
import Link from 'next/link';
import { useAuthStore } from '@/store/useAuthStore';
import { useRouter } from 'next/navigation';

const mockBarData = [
  { name: 'Lun', visitas: 40 },
  { name: 'Mar', visitas: 30 },
  { name: 'Mié', visitas: 55 },
  { name: 'Jue', visitas: 45 },
  { name: 'Vie', visitas: 60 },
  { name: 'Sáb', visitas: 80 },
  { name: 'Dom', visitas: 25 },
];

export default function DashboardPage() {
  const { user } = useAuthStore();
  const router = useRouter();

  const [stats, setStats] = useState({
    mascotasReg: 0,
    mascotasAlbergue: 0,
    adopcionesMes: 0,
    atencionesMes: 8530 // Mock
  });
  const [campanias, setCampanias] = useState<Campania[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (user?.roles?.includes('propietario')) {
      router.replace('/mascotas');
    } else {
      fetchData();
    }
  }, [user, router]);

  const fetchData = async () => {
    try {
      setLoading(true);
      const allMascotas = await mascotaService.getAllAnimals();
      const shelterMascotas = await mascotaService.getAllAnimals({ albergue: true });
      const allAdopciones = await adopcionService.getAllAdoptions();
      const allCampanias = await campaniaService.getAll();

      const currentMonth = new Date().getMonth();
      const currentYear = new Date().getFullYear();
      const adopcionesMes = allAdopciones.filter((a: any) => {
        const d = new Date(a.fecha_adopcion || a.created_at);
        return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
      }).length;

      setStats({
        mascotasReg: allMascotas.length,
        mascotasAlbergue: shelterMascotas.length,
        adopcionesMes: adopcionesMes,
        atencionesMes: 8530
      });

      const filtered = allCampanias.filter(c =>
        c.estado.toLowerCase() === 'planificada' ||
        c.estado.toLowerCase() === 'en_curso' ||
        c.estado.toLowerCase() === 'programada'
      ).slice(0, 5);

      setCampanias(filtered);
    } catch (err) {
      console.error('Error loading dashboard data:', err);
    } finally {
      setLoading(false);
    }
  };

  const getEstadoLabel = (estado: string) => {
    switch (estado.toLowerCase()) {
      case 'programada':
      case 'planificada': return 'Planificada';
      case 'en_curso': return 'En Curso';
      case 'finalizada': return 'Finalizada';
      case 'cancelada': return 'Cancelada';
      default: return estado;
    }
  };

  const getEstadoBadgeColor = (estado: string) => {
    switch (estado.toLowerCase()) {
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
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
  };

  return (
    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

      {/* Left Column: Cards and Table (Takes 2 cols on lg) */}
      <div className="lg:col-span-2 space-y-8">

        {/* Top Cards Row */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">

          {/* Card 1: Registrar Atención */}
          <div className="bg-gradient-to-br from-blue-800 to-blue-500 rounded-[28px] p-8 shadow-xl shadow-blue-500/20 text-white relative overflow-hidden group cursor-pointer flex flex-col justify-between h-full">
            <div className="absolute -right-8 -top-8 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-colors duration-500"></div>
            <div className="absolute right-12 bottom-0 w-20 h-20 bg-black/10 rounded-full blur-xl"></div>

            <div className="relative z-10 flex flex-col justify-between h-full">
              <div>
                <p className="text-white/80 text-[10px] font-bold uppercase tracking-widest mb-3">Servicio Clínico</p>
                <h3 className="text-[26px] font-extrabold leading-tight mb-6">
                  Registrar <br /> atención
                </h3>
              </div>
              <Link href="/atenciones/nueva" className="bg-white text-blue-700 px-6 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-gray-50 transition-colors inline-block hover:scale-105 transform duration-300 self-start">
                Crear nueva atención
              </Link>
            </div>
          </div>

          {/* Card 2: Promotional Card */}
          <div className="bg-gradient-to-br from-[#015f33] to-[#2ecc71] rounded-[28px] p-8 shadow-xl shadow-[#2ecc71]/20 text-white relative overflow-hidden group cursor-pointer flex flex-col justify-between h-full">
            <div className="absolute -right-8 -top-8 w-32 h-32 bg-white/20 rounded-full blur-2xl group-hover:bg-white/30 transition-colors duration-500"></div>
            <div className="absolute right-12 bottom-0 w-20 h-20 bg-black/10 rounded-full blur-xl"></div>

            <div className="relative z-10 flex flex-col justify-between h-full">
              <div>
                <p className="text-white/80 text-[10px] font-bold uppercase tracking-widest mb-3">Recordatorio</p>
                <h3 className="text-[26px] font-extrabold leading-tight mb-6">
                  Configura la <br /> próxima campaña
                </h3>
              </div>
              <Link href="/campanias/crear" className="bg-white text-[#015f33] px-6 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-gray-50 transition-colors inline-block hover:scale-105 transform duration-300 self-start">
                Crear nueva campaña
              </Link>
            </div>
          </div>
        </div>

        {/* Main Table */}
        <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-7 shadow-sm border border-white/60">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-gray-500 font-medium text-sm">Próximas Campañas</h3>
            <Link href="/campanias" className="text-[13px] text-[#2ecc71] font-semibold hover:underline">Ver todas</Link>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-[13px] text-left text-gray-500">
              <thead className="text-[11px] text-gray-400 uppercase font-bold tracking-wider">
                <tr className="border-b border-gray-200/50">
                  <th scope="col" className="px-4 py-3 pb-4">Campaña</th>
                  <th scope="col" className="px-4 py-3 pb-4">Lugar</th>
                  <th scope="col" className="px-4 py-3 pb-4 text-center">Desc.</th>
                  <th scope="col" className="px-4 py-3 pb-4">Inicio</th>
                  <th scope="col" className="px-4 py-3 pb-4">Fin</th>
                  <th scope="col" className="px-4 py-3 pb-4">Resp.</th>
                  <th scope="col" className="px-4 py-3 pb-4 text-center">Estado</th>
                </tr>
              </thead>
              <tbody>
                {campanias.length === 0 && !loading ? (
                  <tr><td colSpan={7} className="py-8 text-center text-gray-400">No hay campañas vigentes</td></tr>
                ) : campanias.map((row) => (
                  <tr key={row.id} className="border-b border-gray-200/50 last:border-0 hover:bg-white/40 transition-colors">
                    <td className="px-4 py-4.5 font-bold text-gray-800 flex items-center space-x-3">
                      <div className="w-7 h-7 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#015f33]">
                        <Syringe size={14} />
                      </div>
                      <span className="truncate max-w-[120px]">{row.nombre}</span>
                    </td>
                    <td className="px-4 py-4.5 text-gray-600 truncate max-w-[100px]">{row.lugar || '-'}</td>
                    <td className="px-4 py-4.5 text-center">
                      <div className="inline-flex p-1.5 text-gray-400 hover:text-[#11ba82] transition-all cursor-help relative group/tooltip">
                        <FileText size={16} />
                        <div
                          className="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-gray-900 text-white text-[11px] leading-relaxed rounded-xl opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all shadow-xl z-50 pointer-events-none text-left prose prose-invert prose-sm"
                          dangerouslySetInnerHTML={{ __html: row.descripcion || 'Sin descripción' }}
                        />
                      </div>
                    </td>
                    <td className="px-4 py-4.5 font-medium whitespace-nowrap text-[12px]">{row.fecha_hora_inicio ? formatDate(row.fecha_hora_inicio) : '-'}</td>
                    <td className="px-4 py-4.5 font-medium whitespace-nowrap text-[12px]">{row.fecha_hora_fin ? formatDate(row.fecha_hora_fin) : '-'}</td>
                    <td className="px-4 py-4.5 text-gray-600 truncate max-w-[80px] text-[12px]">{row.responsable?.nombre || '-'}</td>
                    <td className="px-4 py-4.5 text-center">
                      <span className={`px-3 py-1 rounded-full text-[10px] font-bold tracking-wide ${getEstadoBadgeColor(row.estado)}`}>
                        {getEstadoLabel(row.estado)}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

      </div>

      {/* Right Column: Metrics and Charts */}
      <div className="space-y-8">

        {/* 2x2 Metrics Grid */}
        <div className="grid grid-cols-2 gap-5">
          <div className="bg-white/50 backdrop-blur-md rounded-[24px] p-5 shadow-sm border border-white/60 flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform duration-300 cursor-default">
            <div className="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-purple-600 mb-3 shadow-sm">
              <Dog size={20} />
            </div>
            <p className="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1 px-1">Mascotas registradas</p>
            <p className="text-[28px] font-extrabold text-gray-800 leading-none">{stats.mascotasReg.toLocaleString()}</p>
          </div>
          <div className="bg-white/50 backdrop-blur-md rounded-[24px] p-5 shadow-sm border border-white/60 flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform duration-300 cursor-default">
            <div className="w-12 h-12 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-500 mb-3 shadow-sm">
              <Home size={20} />
            </div>
            <p className="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1 px-1">Mascotas en el albergue</p>
            <p className="text-[28px] font-extrabold text-gray-800 leading-none">{stats.mascotasAlbergue}</p>
          </div>
          <div className="bg-white/50 backdrop-blur-md rounded-[24px] p-5 shadow-sm border border-white/60 flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform duration-300 cursor-default">
            <div className="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-500 mb-3 shadow-sm">
              <CheckCircle size={20} />
            </div>
            <p className="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1 px-1">Adopciones del mes</p>
            <p className="text-[28px] font-extrabold text-gray-800 leading-none">{stats.adopcionesMes}</p>
          </div>
          <div className="bg-white/50 backdrop-blur-md rounded-[24px] p-5 shadow-sm border border-white/60 flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform duration-300 cursor-default">
            <div className="w-12 h-12 rounded-2xl bg-[#e6f4f1] flex items-center justify-center text-[#015f33] mb-3 shadow-sm">
              <Activity size={20} />
            </div>
            <p className="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1 px-1">Atenciones del mes</p>
            <p className="text-[28px] font-extrabold text-gray-800 leading-none">{stats.atencionesMes.toLocaleString()}</p>
          </div>
        </div>

        {/* Bar Chart */}
        <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-7 shadow-sm border border-white/60">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-gray-500 font-medium text-sm">Visitas por Día</h3>
            <span className="text-[11px] bg-white text-gray-500 px-3 py-1 rounded-full font-bold shadow-sm">Esta semana</span>
          </div>
          <div className="h-[200px] w-full">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={mockBarData} margin={{ top: 0, right: 0, left: -25, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e5e7eb" />
                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fontSize: 12, fill: '#9ca3af', fontWeight: 500 }} dy={10} />
                <YAxis axisLine={false} tickLine={false} tick={{ fontSize: 12, fill: '#9ca3af', fontWeight: 500 }} />
                <Tooltip
                  cursor={{ fill: 'rgba(46, 204, 113, 0.05)' }}
                  contentStyle={{ borderRadius: '16px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)', fontWeight: 'bold', color: '#1f2937' }}
                />
                <Bar dataKey="visitas" fill="#2ecc71" radius={[6, 6, 6, 6]} barSize={24} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>
      </div>
    </div>
  );
}
