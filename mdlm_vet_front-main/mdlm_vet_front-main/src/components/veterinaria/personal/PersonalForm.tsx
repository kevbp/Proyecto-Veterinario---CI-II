'use client';

import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { ChevronRight, Plus, Loader2, Check } from 'lucide-react';
import PersonalModal from './PersonalModal';
import { personalService } from '@/utils/personalService';
import { mascotaService } from '@/utils/mascotaService';
import { useEffect, useState } from 'react';

export default function PersonalForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [tipoDocumentos, setTipoDocumentos] = useState<any[]>([]);
  const [formData, setFormData] = useState({
    tipo_doc_id: '',
    nro_doc: '',
    nombre: '',
    paterno: '',
    materno: '',
    email: '',
    celular: '',
    especialidad: '',
    rol_sistema: ''
  });

  const searchParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : new URLSearchParams();
  const editId = searchParams.get('edit');

  useEffect(() => {
    fetchInitialData();
    if (editId) {
      fetchPersonalData();
    }
  }, [editId]);

  const fetchPersonalData = async () => {
    if (!editId) return;
    try {
      setLoading(true);
      const person = await personalService.getById(editId);
      setFormData({
        tipo_doc_id: person.tipo_doc_id || '',
        nro_doc: person.nro_doc,
        nombre: person.nombre,
        paterno: person.paterno,
        materno: person.materno || '',
        email: person.email,
        celular: person.celular || '',
        especialidad: person.profesion || '',
        rol_sistema: person.rol || ''
      });
    } catch (err) {
      console.error('Error fetching personal for edit:', err);
    } finally {
      setLoading(false);
    }
  };

  const fetchInitialData = async () => {
    try {
      const docs = await mascotaService.getTipoDocumentos();
      setTipoDocumentos(docs);
    } catch (err) {
      console.error('Error fetching docs:', err);
    }
  };

  const [showConfirmModal, setShowConfirmModal] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setShowConfirmModal(true);
  };

  const handleConfirmSubmit = async () => {
    try {
      setLoading(true);
      if (editId) {
        await personalService.update(editId, formData);
      } else {
        await personalService.create(formData);
      }
      router.push('/personal');
    } catch (err: any) {
      console.error('Error creating personal:', err);
      if (err.response?.status === 422) {
        alert('Error de validación: Verifique que el correo o documento no estén duplicados.');
      } else {
        alert('Ocurrió un error inesperado en el servidor al registrar el personal.');
      }
    } finally {
      setLoading(false);
      setShowConfirmModal(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  return (
    <div className="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-500 pb-12">
      {/* Header and Breadcrumb */}
      <div>
        <div className="flex items-center text-[13px] text-gray-500 mb-2 font-medium">
          <Link href="/personal" className="hover:text-[#2ecc71] transition-colors">Personal</Link>
          <ChevronRight size={14} className="mx-2" />
          <span className="text-gray-800">{editId ? 'Editar' : 'Crear'}</span>
        </div>
        <h1 className="text-[32px] font-extrabold text-[#111827] tracking-tight">{editId ? 'Editar Personal' : 'Crear Personal'}</h1>
      </div>

      <div className="bg-white/80 backdrop-blur-xl rounded-[28px] p-8 md:p-10 shadow-sm border border-white/60">
        <form onSubmit={handleSubmit} className="space-y-8">

          {/* Section Title */}
          <div className="border-b border-gray-100/80 pb-4 mb-2">
            <h3 className="text-[15px] font-bold text-gray-800">Información del Personal</h3>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
            {/* Row 1 */}
            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Tipo de Documento <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <select
                name="tipo_doc_id"
                value={formData.tipo_doc_id}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              >
                <option value="" disabled>Seleccione una opción</option>
                {tipoDocumentos.map(td => (
                  <option key={td.id} value={td.codigo}>{td.nombre}</option>
                ))}
              </select>
            </div>

            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Número de Documento <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="nro_doc"
                value={formData.nro_doc}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            {/* Row 2 */}
            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Nombre <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="nombre"
                value={formData.nombre}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Apellido Paterno <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="paterno"
                value={formData.paterno}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Apellido Materno <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="materno"
                value={formData.materno}
                onChange={handleChange}
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Correo Electrónico <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            {/* Row 4 */}
            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Número de Celular <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="celular"
                value={formData.celular}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Profesión / Especialidad <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <input
                type="text"
                name="especialidad"
                value={formData.especialidad}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              />
            </div>

            {/* Row 5 */}
            <div className="space-y-2.5">
              <label className="block text-[13px] font-bold text-gray-700">
                Rol del Sistema <span className="text-pink-500 ml-0.5">*</span>
              </label>
              <select
                name="rol_sistema"
                value={formData.rol_sistema}
                onChange={handleChange}
                required
                className="w-full rounded-xl border border-gray-200/80 p-3.5 bg-white focus:ring-2 focus:ring-[#2ecc71]/50 focus:border-[#2ecc71] outline-none transition-all shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-gray-700 text-[14px]"
              >
                <option value="" disabled>Seleccione una opción</option>
                <option value="veterinario">Veterinario</option>
                <option value="recepcionista">Recepcionista</option>
                <option value="admin">Administrador</option>
                <option value="gestor">Gestor</option>
              </select>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="pt-8 flex items-center gap-6">
            <button
              type="submit"
              disabled={loading}
              className="flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#015f33] to-[#2ecc71] hover:opacity-90 active:scale-[0.98] text-white font-bold rounded-xl transition-all shadow-sm shadow-[#2ecc71]/30 text-[15px] disabled:opacity-50"
            >
              {loading ? <Loader2 size={20} className="animate-spin" /> : (editId ? <Check size={20} strokeWidth={2.5} /> : <Plus size={20} strokeWidth={2.5} />)}
              {editId ? 'Guardar Cambios' : 'Crear Personal'}
            </button>
            <Link
              href="/personal"
              className="text-gray-500 font-bold hover:text-gray-800 transition-colors text-[15px]"
            >
              Cancelar
            </Link>
          </div>
        </form>
      </div>

      <PersonalModal
        isOpen={showConfirmModal}
        onClose={() => setShowConfirmModal(false)}
        onConfirm={handleConfirmSubmit}
        formData={formData}
        loading={loading}
      />
    </div>
  );
}
