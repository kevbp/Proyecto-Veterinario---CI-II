'use client';

import { useState, useEffect } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { Plus, Loader2, HeartPulse, ChevronRight, Dog, Calendar, Check, ChevronLeft, FileText, Lock, Shield, AlertTriangle, X, Trash2, Syringe, Package } from 'lucide-react';
import { mascotaService } from '@/utils/mascotaService';
import { catalogoService } from '@/utils/catalogoService';
import { Mascota } from '@/interfaces/Mascota';
import SearchableSelect from '@/components/ui/SearchableSelect';
import MascotaModal from '../mascotas/MascotaModal';
import { useAuthStore } from '@/store/useAuthStore';

export default function AtencionSelection() {
  const router = useRouter();
  const { user } = useAuthStore();
  const searchParams = useSearchParams();
  const [currentStep, setCurrentStep] = useState(1);
  const [mascotas, setMascotas] = useState<Mascota[]>([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [selectedMascotaId, setSelectedMascotaId] = useState(searchParams.get('mascota_id') || '');
  const [isMascotaModalOpen, setIsMascotaModalOpen] = useState(false);

  // Consulta Form State (Step 2)
  const [consultaData, setConsultaData] = useState({
    motivo: '',
    diagnostico: '',
    tratamiento: '',
    observaciones: '',
    peso_registrado: '',
    cita_id: '', // Disabled for now
  });

  // Step 3: Alergias y Condiciones
  const [catalogoAlergias, setCatalogoAlergias] = useState<any[]>([]);
  const [catalogoCondiciones, setCatalogoCondiciones] = useState<any[]>([]);
  const [selectedAlergiaId, setSelectedAlergiaId] = useState('');
  const [selectedCondicionId, setSelectedCondicionId] = useState('');
  const [alergiasList, setAlergiasList] = useState<{ alergia_id: string; nombre: string; observaciones: string; severidad: string; estado_clinico: string }[]>([]);
  const [condicionesList, setCondicionesList] = useState<{ condicion_id: string; nombre: string; observaciones: string; severidad: string; estado_clinico: string }[]>([]);

  // Step 4: Añadidos (Vacunación, Desparasitación, Receta)
  const [addVacunacion, setAddVacunacion] = useState(false);
  const [addDesparasitacion, setAddDesparasitacion] = useState(false);
  const [addReceta, setAddReceta] = useState(false);
  const [esquemasVacunas, setEsquemasVacunas] = useState<any[]>([]);
  const [medicamentos, setMedicamentos] = useState<any[]>([]);
  const [vacunaData, setVacunaData] = useState({
    esquema_vacuna_id: '',
    medicamento_id: '',
    fecha_aplicacion: new Date().toLocaleDateString('en-CA'),
    fecha_proxima: '',
    dosis: '',
    lote: '',
    fabricante: '',
    cantidad: '1',
    observaciones: '',
  });

  const selectedMascota = mascotas.find(m => m.id === selectedMascotaId);

  useEffect(() => {
    if (user) {
      fetchInitialData();
    }
  }, [user]);

  const fetchInitialData = async () => {
    try {
      setLoading(true);
      const [animalesData, alergiasData, condicionesData, esquemasData, medicamentosData] = await Promise.all([
        mascotaService.getAllAnimals(),
        catalogoService.getAlergias(),
        catalogoService.getCondiciones(),
        catalogoService.getEsquemasVacunas(),
        catalogoService.getMedicamentos(),
      ]);
      setMascotas(animalesData);
      setCatalogoAlergias(alergiasData);
      setCatalogoCondiciones(condicionesData);
      setEsquemasVacunas(esquemasData);
      setMedicamentos(medicamentosData);
    } catch (err) {
      console.error('Error fetching initial data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleMascotaSuccess = (newPet: Mascota) => {
    setMascotas(prev => [newPet, ...prev]);
    setSelectedMascotaId(newPet.id);
  };

  // --- Helpers para agregar alergias/condiciones a la lista local ---
  const handleAddAlergia = () => {
    if (!selectedAlergiaId) return;
    if (alergiasList.find(a => a.alergia_id === selectedAlergiaId)) return;
    const cat = catalogoAlergias.find(c => c.id === selectedAlergiaId);
    setAlergiasList(prev => [...prev, {
      alergia_id: selectedAlergiaId,
      nombre: cat?.nombre || '',
      observaciones: '',
      severidad: 'leve',
      estado_clinico: 'activa',
    }]);
    setSelectedAlergiaId('');
  };

  const handleRemoveAlergia = (id: string) => {
    setAlergiasList(prev => prev.filter(a => a.alergia_id !== id));
  };

  const handleUpdateAlergia = (id: string, field: string, value: string) => {
    setAlergiasList(prev => prev.map(a => a.alergia_id === id ? { ...a, [field]: value } : a));
  };

  const handleAddCondicion = () => {
    if (!selectedCondicionId) return;
    if (condicionesList.find(c => c.condicion_id === selectedCondicionId)) return;
    const cat = catalogoCondiciones.find(c => c.id === selectedCondicionId);
    setCondicionesList(prev => [...prev, {
      condicion_id: selectedCondicionId,
      nombre: cat?.nombre || '',
      observaciones: '',
      severidad: 'leve',
      estado_clinico: 'activa',
    }]);
    setSelectedCondicionId('');
  };

  const handleRemoveCondicion = (id: string) => {
    setCondicionesList(prev => prev.filter(c => c.condicion_id !== id));
  };

  const handleUpdateCondicion = (id: string, field: string, value: string) => {
    setCondicionesList(prev => prev.map(c => c.condicion_id === id ? { ...c, [field]: value } : c));
  };

  const handleNextStep = () => {
    if (currentStep === 1 && selectedMascotaId) {
      setCurrentStep(2);
    } else if (currentStep === 2) {
      if (!consultaData.motivo || !consultaData.diagnostico || !consultaData.peso_registrado) {
        alert('Por favor complete los campos obligatorios (Motivo, Diagnóstico y Peso)');
        return;
      }
      setCurrentStep(3);
    } else if (currentStep === 3) {
      // Validar que todas las alergias agregadas tengan observaciones
      const alergiaIncompleta = alergiasList.find(a => !a.observaciones.trim());
      if (alergiaIncompleta) {
        alert(`La alergia "${alergiaIncompleta.nombre}" necesita observaciones antes de continuar.`);
        return;
      }
      // Validar que todas las condiciones agregadas tengan observaciones
      const condicionIncompleta = condicionesList.find(c => !c.observaciones.trim());
      if (condicionIncompleta) {
        alert(`La condición "${condicionIncompleta.nombre}" necesita observaciones antes de continuar.`);
        return;
      }
      setCurrentStep(4);
    } else if (currentStep === 4) {
      // Validar formulario de vacunación si está activo
      if (addVacunacion) {
        if (!vacunaData.esquema_vacuna_id || !vacunaData.medicamento_id || !vacunaData.dosis || !vacunaData.lote || !vacunaData.cantidad) {
          alert('Complete todos los campos obligatorios de la vacunación antes de continuar.');
          return;
        }
      }
      setCurrentStep(5);
    }
  };

  const handlePrevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  const handleSubmit = async () => {
    if (!user || !selectedMascotaId) return;

    setSubmitting(true);
    try {
      // 1. Crear la consulta
      const payload = {
        ...consultaData,
        animal_id: selectedMascotaId,
        personal_id: user.id,
        peso_registrado: parseFloat(consultaData.peso_registrado)
      };
      const consultaCreada = await mascotaService.createConsulta(payload);
      const consultaId = consultaCreada.id;

      // 2. Registrar alergias
      for (const alergia of alergiasList) {
        await mascotaService.createAnimalAlergia(selectedMascotaId, {
          alergia_id: alergia.alergia_id,
          observaciones: alergia.observaciones,
          severidad: alergia.severidad,
          estado_clinico: alergia.estado_clinico,
        });
      }

      // 3. Registrar condiciones (con consulta_id)
      for (const condicion of condicionesList) {
        await mascotaService.createAnimalCondicion(selectedMascotaId, {
          condicion_id: condicion.condicion_id,
          observaciones: condicion.observaciones,
          severidad: condicion.severidad,
          estado_clinico: condicion.estado_clinico,
          consulta_id: consultaId,
        });
      }

      // 4. Registrar vacunación (si se activó)
      if (addVacunacion) {
        await catalogoService.createVacunaAnimal({
          ...vacunaData,
          animal_id: selectedMascotaId,
          cantidad: parseFloat(vacunaData.cantidad),
          consulta_id: consultaId,
        });
      }

      alert('Atención médica registrada con éxito');
      router.push(`/mascotas/${selectedMascotaId}/historial`);
    } catch (err: any) {
      console.error('Error creating consulta:', err);
      alert(err.response?.data?.message || 'Error al registrar la consulta. Intente de nuevo.');
    } finally {
      setSubmitting(false);
    }
  };

  const steps = [
    { number: 1, title: 'Mascota', icon: <Dog size={18} /> },
    { number: 2, title: 'Consulta', icon: <HeartPulse size={18} /> },
    { number: 3, title: 'Historial', icon: <Shield size={18} /> },
    { number: 4, title: 'Añadidos', icon: <Package size={18} /> },
    { number: 5, title: 'Finalizar', icon: <Calendar size={18} /> },
  ];

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center py-20 gap-4">
        <Loader2 className="animate-spin text-[#2ecc71]" size={48} />
        <p className="text-gray-500 font-medium">Cargando datos...</p>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-10">
      {/* Header */}
      <div className="text-center space-y-1">
        <h1 className="text-3xl font-black text-gray-900 tracking-tight">Registro de Atención</h1>
        <p className="text-sm text-gray-500 font-medium">Siga los pasos para completar la atención médica</p>
      </div>

      {/* Stepper Indicator */}
      <div className="flex items-center justify-center mb-12 pt-4">
        <div className="flex items-center w-full max-w-2xl px-4">
          {steps.map((step, idx) => (
            <div key={step.number} className="flex items-center flex-1 last:flex-none">
              <div className="flex flex-col items-center relative">
                <div
                  className={`w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-500 border-2 ${currentStep >= step.number
                      ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200'
                      : 'bg-white border-gray-200 text-gray-400'
                    } ${currentStep === step.number ? 'scale-110' : ''}`}
                >
                  {currentStep > step.number ? <Check size={20} strokeWidth={3} /> : step.icon}
                </div>
                <span
                  className={`absolute -bottom-8 whitespace-nowrap text-[11px] font-bold uppercase tracking-widest transition-colors duration-500 ${currentStep >= step.number ? 'text-blue-600' : 'text-gray-400'
                    }`}
                >
                  {step.title}
                </span>
              </div>
              {idx < steps.length - 1 && (
                <div className="flex-1 h-[3px] mx-4 bg-gray-100 rounded-full overflow-hidden relative">
                  <div
                    className="absolute top-0 left-0 h-full bg-blue-600 transition-all duration-700 ease-in-out"
                    style={{ width: currentStep > step.number ? '100%' : '0%' }}
                  />
                </div>
              )}
            </div>
          ))}
        </div>
      </div>

      {/* Content area */}
      <div className="bg-white/70 backdrop-blur-xl rounded-[32px] p-6 md:p-8 shadow-2xl shadow-blue-900/5 border border-white relative overflow-hidden">
        {/* Step 1: Selection */}
        {currentStep === 1 && (
          <div className="space-y-6 animate-in fade-in zoom-in-95 duration-500">
            <div className="space-y-4">
              <h3 className="text-2xl font-bold text-gray-800">Seleccione la Mascota</h3>
              <p className="text-gray-500 leading-relaxed">Busque una mascota registrada o cree una nueva si es la primera vez que se atiende.</p>
            </div>

            <div className="flex gap-4 items-end">
              <div className="flex-1">
                <label className="block text-[11px] font-bold text-gray-400 mb-3 ml-1 uppercase tracking-widest">
                  Buscar Mascota
                </label>
                <SearchableSelect
                  placeholder="Escriba el nombre de la mascota o el dueño..."
                  value={selectedMascotaId}
                  onChange={(val) => setSelectedMascotaId(val.toString())}
                  options={mascotas.map(m => ({
                    id: m.id,
                    label: m.nombre,
                    sublabel: `${m.especie || 'Especie'} - Prop: ${m.propietario || 'N/A'}${m.hogar ? ` (${m.hogar})` : ''}`
                  }))}
                  className="w-full"
                />
              </div>
              <button
                onClick={() => setIsMascotaModalOpen(true)}
                className="h-[52px] w-[52px] bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 text-blue-600 transition-all flex items-center justify-center shrink-0 shadow-sm hover:shadow-md active:scale-95"
                title="Registrar nueva mascota"
              >
                <Plus size={24} strokeWidth={2.5} />
              </button>
            </div>

            {selectedMascotaId && (
              <div className="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-[32px] p-8 flex items-center gap-6 shadow-sm animate-in slide-in-from-top-4 duration-500">
                <div className="w-20 h-20 bg-white rounded-[24px] shadow-sm border border-blue-100 flex items-center justify-center text-blue-500">
                  <Dog size={40} />
                </div>
                <div className="space-y-1">
                  <h4 className="text-2xl font-bold text-gray-800">
                    {selectedMascota?.nombre}
                  </h4>
                  <p className="text-sm text-gray-500 font-medium">
                    Propietario: <span className="text-gray-700">{selectedMascota?.propietario}</span>
                  </p>
                  <div className="pt-2 flex gap-2">
                    <span className="px-3 py-1 bg-blue-100/50 text-blue-700 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                      {selectedMascota?.especie}
                    </span>
                    <span className="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                      {selectedMascota?.raza}
                    </span>
                  </div>
                </div>
              </div>
            )}

            <div className="pt-6 flex justify-end">
              <button
                onClick={handleNextStep}
                disabled={!selectedMascotaId}
                className="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-[20px] font-bold shadow-xl shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-1 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center gap-3 text-lg"
              >
                Continuar
                <ChevronRight size={22} strokeWidth={3} />
              </button>
            </div>
          </div>
        )}

        {/* Step 2: Consulta Form */}
        {currentStep === 2 && (
          <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
            {/* Header Informative */}
            <div className="bg-blue-50/30 border border-blue-100 rounded-2xl p-4 flex items-center justify-between">
              <div className="flex items-center gap-4">
                <div className="w-10 h-10 bg-white rounded-xl shadow-sm border border-blue-100 flex items-center justify-center text-blue-500">
                  <Dog size={22} />
                </div>
                <div>
                  <p className="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Atendiendo a:</p>
                  <h4 className="text-base font-bold text-gray-800">{selectedMascota?.nombre}</h4>
                </div>
              </div>
              <div className="text-right">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Responsable:</p>
                <p className="text-xs font-bold text-gray-600">{user?.name}</p>
              </div>
            </div>

            <div className="space-y-4">
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                  <FileText size={24} />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-gray-800">Detalles de la Consulta</h3>
                  <p className="text-gray-500">Ingrese la información clínica de la atención actual.</p>
                </div>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="md:col-span-2">
                <label className="block text-[13px] font-bold text-gray-700 mb-2 ml-1 uppercase tracking-wider">
                  Motivo de la consulta <span className="text-pink-500">*</span>
                </label>
                <textarea
                  required
                  rows={3}
                  value={consultaData.motivo}
                  onChange={(e) => setConsultaData({ ...consultaData, motivo: e.target.value })}
                  className="w-full px-5 py-4 bg-gray-50/50 border border-gray-200 rounded-[24px] focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-sm resize-none"
                  placeholder="Describa brevemente el motivo de la atención..."
                />
              </div>

              <div>
                <label className="block text-[13px] font-bold text-gray-700 mb-2 ml-1 uppercase tracking-wider">
                  Peso registrado (kg) <span className="text-pink-500">*</span>
                </label>
                <div className="relative">
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={consultaData.peso_registrado}
                    onChange={(e) => setConsultaData({ ...consultaData, peso_registrado: e.target.value })}
                    className="w-full px-5 py-4 bg-gray-50/50 border border-gray-200 rounded-[20px] focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-sm"
                    placeholder="0.00"
                  />
                  <div className="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm pointer-events-none">
                    kg
                  </div>
                </div>
              </div>

              <div>
                <label className="block text-[13px] font-bold text-gray-400 mb-2 ml-1 uppercase tracking-wider">
                  Cita Relacionada (Opcional)
                </label>
                <div className="relative opacity-60">
                  <input
                    type="text"
                    disabled
                    className="w-full px-5 py-4 bg-gray-100 border border-gray-200 rounded-[20px] text-sm cursor-not-allowed"
                    placeholder="Próximamente..."
                  />
                  <Lock className="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                </div>
              </div>

              <div className="md:col-span-2">
                <label className="block text-[13px] font-bold text-gray-700 mb-2 ml-1 uppercase tracking-wider">
                  Diagnóstico <span className="text-pink-500">*</span>
                </label>
                <textarea
                  required
                  rows={3}
                  value={consultaData.diagnostico}
                  onChange={(e) => setConsultaData({ ...consultaData, diagnostico: e.target.value })}
                  className="w-full px-5 py-4 bg-gray-50/50 border border-gray-200 rounded-[24px] focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-sm resize-none"
                  placeholder="Ingrese el diagnóstico clínico..."
                />
              </div>

              <div>
                <label className="block text-[13px] font-bold text-gray-700 mb-2 ml-1 uppercase tracking-wider">
                  Tratamiento
                </label>
                <textarea
                  rows={4}
                  value={consultaData.tratamiento}
                  onChange={(e) => setConsultaData({ ...consultaData, tratamiento: e.target.value })}
                  className="w-full px-5 py-4 bg-gray-50/50 border border-gray-200 rounded-[24px] focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-sm resize-none"
                  placeholder="Medicamentos, dosis, frecuencia..."
                />
              </div>

              <div>
                <label className="block text-[13px] font-bold text-gray-700 mb-2 ml-1 uppercase tracking-wider">
                  Observaciones adicionales
                </label>
                <textarea
                  rows={4}
                  value={consultaData.observaciones}
                  onChange={(e) => setConsultaData({ ...consultaData, observaciones: e.target.value })}
                  className="w-full px-5 py-4 bg-gray-50/50 border border-gray-200 rounded-[24px] focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-sm resize-none"
                  placeholder="Notas adicionales sobre la atención..."
                />
              </div>
            </div>

            <div className="pt-8 flex justify-between border-t border-gray-100">
              <button
                onClick={handlePrevStep}
                className="px-8 py-4 rounded-[20px] font-bold text-gray-500 hover:bg-gray-100 transition-all flex items-center gap-2 hover:-translate-x-1"
              >
                <ChevronLeft size={20} strokeWidth={3} />
                Atrás
              </button>
              <button
                onClick={handleNextStep}
                className="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-[20px] font-bold shadow-xl shadow-blue-500/20 transition-all flex items-center gap-3 hover:-translate-y-1"
              >
                Siguiente
                <ChevronRight size={20} strokeWidth={3} />
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Alergias y Condiciones */}
        {currentStep === 3 && (
          <div className="space-y-10 animate-in fade-in slide-in-from-right-8 duration-500">
            {/* Header Informative */}
            <div className="bg-blue-50/30 border border-blue-100 rounded-2xl p-4 flex items-center justify-between">
              <div className="flex items-center gap-4">
                <div className="w-10 h-10 bg-white rounded-xl shadow-sm border border-blue-100 flex items-center justify-center text-blue-500">
                  <Dog size={22} />
                </div>
                <div>
                  <p className="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Atendiendo a:</p>
                  <h4 className="text-base font-bold text-gray-800">{selectedMascota?.nombre}</h4>
                </div>
              </div>
              <div className="text-right">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Responsable:</p>
                <p className="text-xs font-bold text-gray-600">{user?.name}</p>
              </div>
            </div>

            <div className="space-y-4">
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center">
                  <Shield size={24} />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-gray-800">Alergias y Condiciones</h3>
                  <p className="text-gray-500">Registre las alergias y condiciones base del paciente (opcional).</p>
                </div>
              </div>
            </div>

            {/* --- Bloque Alergias --- */}
            <div className="bg-gradient-to-br from-red-50/40 to-white border border-red-100 rounded-[28px] p-6 space-y-5">
              <h4 className="text-sm font-bold text-red-700 uppercase tracking-wider flex items-center gap-2">
                <AlertTriangle size={16} /> Alergias
              </h4>
              <div className="flex gap-3 items-end">
                <div className="flex-1">
                  <SearchableSelect
                    placeholder="Buscar alergia en el catálogo..."
                    value={selectedAlergiaId}
                    onChange={(val) => setSelectedAlergiaId(val.toString())}
                    options={catalogoAlergias
                      .filter(a => !alergiasList.find(al => al.alergia_id === a.id))
                      .slice(0, 5)
                      .map(a => ({ id: a.id, label: a.nombre, sublabel: a.descripcion || '' }))}
                    className="w-full"
                  />
                </div>
                <button
                  onClick={handleAddAlergia}
                  disabled={!selectedAlergiaId}
                  className="h-[42px] px-5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 shrink-0 shadow-sm"
                >
                  <Plus size={18} /> Agregar
                </button>
              </div>
              {alergiasList.length > 0 && (
                <div className="space-y-3 pt-2">
                  {alergiasList.map(a => (
                    <div key={a.alergia_id} className="bg-white border border-red-100 rounded-2xl p-4 space-y-3 shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                      <div className="flex items-center justify-between">
                        <span className="font-bold text-gray-800">{a.nombre}</span>
                        <button onClick={() => handleRemoveAlergia(a.alergia_id)} className="text-gray-400 hover:text-red-500 transition-colors p-1">
                          <Trash2 size={16} />
                        </button>
                      </div>
                      <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Severidad</label>
                          <select value={a.severidad} onChange={e => handleUpdateAlergia(a.alergia_id, 'severidad', e.target.value)} className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                            <option value="leve">Leve</option>
                            <option value="moderado">Moderado</option>
                            <option value="severo">Severo</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Estado Clínico</label>
                          <select value={a.estado_clinico} onChange={e => handleUpdateAlergia(a.alergia_id, 'estado_clinico', e.target.value)} className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                            <option value="activa">Activa</option>
                            <option value="inactiva">Inactiva</option>
                            <option value="resuelta">Resuelta</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Observaciones</label>
                          <input type="text" value={a.observaciones} onChange={e => handleUpdateAlergia(a.alergia_id, 'observaciones', e.target.value)} placeholder="Notas..." className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20" />
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
              {alergiasList.length === 0 && (
                <p className="text-xs text-gray-400 italic text-center py-2">No se han registrado alergias aún.</p>
              )}
            </div>

            {/* --- Bloque Condiciones --- */}
            <div className="bg-gradient-to-br from-amber-50/40 to-white border border-amber-100 rounded-[28px] p-6 space-y-5">
              <h4 className="text-sm font-bold text-amber-700 uppercase tracking-wider flex items-center gap-2">
                <Shield size={16} /> Condiciones Base
              </h4>
              <div className="flex gap-3 items-end">
                <div className="flex-1">
                  <SearchableSelect
                    placeholder="Buscar condición en el catálogo..."
                    value={selectedCondicionId}
                    onChange={(val) => setSelectedCondicionId(val.toString())}
                    options={catalogoCondiciones
                      .filter(c => !condicionesList.find(cl => cl.condicion_id === c.id))
                      .slice(0, 5)
                      .map(c => ({ id: c.id, label: c.nombre, sublabel: c.descripcion || '' }))}
                    className="w-full"
                  />
                </div>
                <button
                  onClick={handleAddCondicion}
                  disabled={!selectedCondicionId}
                  className="h-[42px] px-5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 shrink-0 shadow-sm"
                >
                  <Plus size={18} /> Agregar
                </button>
              </div>
              {condicionesList.length > 0 && (
                <div className="space-y-3 pt-2">
                  {condicionesList.map(c => (
                    <div key={c.condicion_id} className="bg-white border border-amber-100 rounded-2xl p-4 space-y-3 shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                      <div className="flex items-center justify-between">
                        <span className="font-bold text-gray-800">{c.nombre}</span>
                        <button onClick={() => handleRemoveCondicion(c.condicion_id)} className="text-gray-400 hover:text-amber-600 transition-colors p-1">
                          <Trash2 size={16} />
                        </button>
                      </div>
                      <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Severidad</label>
                          <select value={c.severidad} onChange={e => handleUpdateCondicion(c.condicion_id, 'severidad', e.target.value)} className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <option value="leve">Leve</option>
                            <option value="moderado">Moderado</option>
                            <option value="severo">Severo</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Estado Clínico</label>
                          <select value={c.estado_clinico} onChange={e => handleUpdateCondicion(c.condicion_id, 'estado_clinico', e.target.value)} className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <option value="activa">Activa</option>
                            <option value="inactiva">Inactiva</option>
                            <option value="resuelta">Resuelta</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Observaciones</label>
                          <input type="text" value={c.observaciones} onChange={e => handleUpdateCondicion(c.condicion_id, 'observaciones', e.target.value)} placeholder="Notas..." className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20" />
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
              {condicionesList.length === 0 && (
                <p className="text-xs text-gray-400 italic text-center py-2">No se han registrado condiciones aún.</p>
              )}
            </div>

            <div className="pt-8 flex justify-between border-t border-gray-100">
              <button
                onClick={handlePrevStep}
                className="px-8 py-4 rounded-[20px] font-bold text-gray-500 hover:bg-gray-100 transition-all flex items-center gap-2 hover:-translate-x-1"
              >
                <ChevronLeft size={20} strokeWidth={3} />
                Atrás
              </button>
              <button
                onClick={handleNextStep}
                className="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-[20px] font-bold shadow-xl shadow-blue-500/20 transition-all flex items-center gap-3 hover:-translate-y-1"
              >
                Siguiente
                <ChevronRight size={20} strokeWidth={3} />
              </button>
            </div>
          </div>
        )}

        {/* Step 4: Añadidos */}
        {currentStep === 4 && (
          <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
            {/* Header Informative */}
            <div className="bg-blue-50/30 border border-blue-100 rounded-2xl p-4 flex items-center justify-between">
              <div className="flex items-center gap-4">
                <div className="w-10 h-10 bg-white rounded-xl shadow-sm border border-blue-100 flex items-center justify-center text-blue-500">
                  <Dog size={22} />
                </div>
                <div>
                  <p className="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Atendiendo a:</p>
                  <h4 className="text-base font-bold text-gray-800">{selectedMascota?.nombre}</h4>
                </div>
              </div>
              <div className="text-right">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Responsable:</p>
                <p className="text-xs font-bold text-gray-600">{user?.name}</p>
              </div>
            </div>

            <div className="space-y-4">
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center">
                  <Package size={24} />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-gray-800">Añadidos a la Consulta</h3>
                  <p className="text-gray-500">Seleccione los procedimientos adicionales realizados durante esta atención.</p>
                </div>
              </div>
            </div>

            {/* Checkboxes */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <label className={`flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300 ${addVacunacion ? 'border-emerald-400 bg-emerald-50/50 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300'
                }`}>
                <input type="checkbox" checked={addVacunacion} onChange={e => setAddVacunacion(e.target.checked)} className="sr-only" />
                <div className={`w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all ${addVacunacion ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-gray-300'
                  }`}>
                  {addVacunacion && <Check size={14} strokeWidth={3} />}
                </div>
                <div>
                  <span className="font-bold text-gray-800 text-sm flex items-center gap-1.5"><Syringe size={14} className="text-emerald-500" /> Vacunación</span>
                </div>
              </label>

              <label className={`flex items-center gap-3 p-4 rounded-2xl border-2 cursor-not-allowed transition-all duration-300 border-gray-200 bg-gray-50 opacity-60`}>
                <div className="w-6 h-6 rounded-lg border-2 border-gray-300 flex items-center justify-center"></div>
                <div>
                  <span className="font-bold text-gray-500 text-sm flex items-center gap-1.5">🐛 Desparasitación</span>
                  <span className="text-[10px] text-gray-400 block">Próximamente</span>
                </div>
              </label>

              <label className={`flex items-center gap-3 p-4 rounded-2xl border-2 cursor-not-allowed transition-all duration-300 border-gray-200 bg-gray-50 opacity-60`}>
                <div className="w-6 h-6 rounded-lg border-2 border-gray-300 flex items-center justify-center"></div>
                <div>
                  <span className="font-bold text-gray-500 text-sm flex items-center gap-1.5">💊 Receta</span>
                  <span className="text-[10px] text-gray-400 block">Próximamente</span>
                </div>
              </label>
            </div>

            {/* Formulario Vacunación */}
            {addVacunacion && (
              <div className="bg-gradient-to-br from-emerald-50/40 to-white border border-emerald-200 rounded-[28px] p-6 space-y-6 animate-in fade-in slide-in-from-top-4 duration-500">
                <h4 className="text-sm font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-2">
                  <Syringe size={16} /> Registro de Vacunación
                </h4>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                  {/* Esquema Vacuna */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Esquema de Vacuna <span className="text-pink-500">*</span></label>
                    <select
                      value={vacunaData.esquema_vacuna_id}
                      onChange={e => setVacunaData({ ...vacunaData, esquema_vacuna_id: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-300"
                    >
                      <option value="">Seleccione un esquema...</option>
                      {esquemasVacunas.map((ev: any) => (
                        <option key={ev.id} value={ev.id}>{ev.nombre} - {ev.enfermedad || 'General'}</option>
                      ))}
                    </select>
                  </div>

                  {/* Medicamento */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Medicamento (Vacuna) <span className="text-pink-500">*</span></label>
                    <select
                      value={vacunaData.medicamento_id}
                      onChange={e => setVacunaData({ ...vacunaData, medicamento_id: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-300"
                    >
                      <option value="">Seleccione un medicamento...</option>
                      {medicamentos.map((m: any) => (
                        <option key={m.id} value={m.id}>{m.nombre}</option>
                      ))}
                    </select>
                  </div>

                  {/* Dosis */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Dosis <span className="text-pink-500">*</span></label>
                    <input
                      type="text"
                      value={vacunaData.dosis}
                      onChange={e => setVacunaData({ ...vacunaData, dosis: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                      placeholder="Ej: 1 ml"
                    />
                  </div>

                  {/* Cantidad */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Cantidad <span className="text-pink-500">*</span></label>
                    <input
                      type="number"
                      step="0.01"
                      min="0.01"
                      value={vacunaData.cantidad}
                      onChange={e => setVacunaData({ ...vacunaData, cantidad: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                      placeholder="1"
                    />
                  </div>

                  {/* Lote */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Lote <span className="text-pink-500">*</span></label>
                    <input
                      type="text"
                      value={vacunaData.lote}
                      onChange={e => setVacunaData({ ...vacunaData, lote: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                      placeholder="Ej: LOT-2026-001"
                    />
                  </div>

                  {/* Fabricante */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Fabricante</label>
                    <input
                      type="text"
                      value={vacunaData.fabricante}
                      onChange={e => setVacunaData({ ...vacunaData, fabricante: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                      placeholder="Ej: Laboratorio XYZ"
                    />
                  </div>

                  {/* Fecha Aplicación */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Fecha de Aplicación <span className="text-pink-500">*</span></label>
                    <input
                      type="date"
                      value={vacunaData.fecha_aplicacion}
                      onChange={e => setVacunaData({ ...vacunaData, fecha_aplicacion: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    />
                  </div>

                  {/* Fecha Próxima */}
                  <div>
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Fecha Próxima Dosis</label>
                    <input
                      type="date"
                      value={vacunaData.fecha_proxima}
                      onChange={e => setVacunaData({ ...vacunaData, fecha_proxima: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    />
                  </div>

                  {/* Observaciones */}
                  <div className="md:col-span-2">
                    <label className="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wider">Observaciones</label>
                    <textarea
                      rows={2}
                      value={vacunaData.observaciones}
                      onChange={e => setVacunaData({ ...vacunaData, observaciones: e.target.value })}
                      className="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 resize-none"
                      placeholder="Observaciones sobre la vacunación..."
                    />
                  </div>
                </div>
              </div>
            )}

            <div className="pt-8 flex justify-between border-t border-gray-100">
              <button
                onClick={handlePrevStep}
                className="px-8 py-4 rounded-[20px] font-bold text-gray-500 hover:bg-gray-100 transition-all flex items-center gap-2 hover:-translate-x-1"
              >
                <ChevronLeft size={20} strokeWidth={3} />
                Atrás
              </button>
              <button
                onClick={handleNextStep}
                className="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-[20px] font-bold shadow-xl shadow-blue-500/20 transition-all flex items-center gap-3 hover:-translate-y-1"
              >
                Siguiente
                <ChevronRight size={20} strokeWidth={3} />
              </button>
            </div>
          </div>
        )}

        {/* Step 5: Resumen y Finalizar */}
        {currentStep === 5 && (
          <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
            <div className="space-y-4 text-center">
              <div className="inline-flex p-3 bg-green-100 text-green-600 rounded-2xl mb-2">
                <Check size={32} strokeWidth={3} />
              </div>
              <h3 className="text-2xl font-black text-gray-800">Resumen de Atención</h3>
              <p className="text-gray-500 leading-relaxed max-w-xl mx-auto">
                Revise la información antes de guardarla permanentemente en el historial clínico de {selectedMascota?.nombre}.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {/* Bloque Mascota */}
              <div className="bg-gray-50/50 rounded-[28px] p-6 border border-gray-100">
                <h4 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Información del Paciente</h4>
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-500">
                    <Dog size={24} />
                  </div>
                  <div>
                    <p className="text-lg font-bold text-gray-800">{selectedMascota?.nombre}</p>
                    <p className="text-xs text-gray-500">{selectedMascota?.especie} • {selectedMascota?.raza}</p>
                  </div>
                </div>
              </div>

              {/* Bloque Clínico Rápido */}
              <div className="bg-gray-50/50 rounded-[28px] p-6 border border-gray-100">
                <h4 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Datos Clínicos</h4>
                <div className="flex items-center gap-6">
                  <div className="flex flex-col">
                    <span className="text-[10px] font-bold text-gray-400 uppercase">Peso</span>
                    <span className="text-lg font-black text-blue-600">{consultaData.peso_registrado} kg</span>
                  </div>
                  <div className="w-px h-10 bg-gray-200"></div>
                  <div className="flex flex-col">
                    <span className="text-[10px] font-bold text-gray-400 uppercase">Fecha</span>
                    <span className="text-sm font-bold text-gray-700">{new Date().toLocaleDateString()}</span>
                  </div>
                </div>
              </div>

              <div className="md:col-span-2 space-y-4">
                <div className="bg-white border border-gray-100 rounded-[28px] p-6 shadow-sm">
                  <h4 className="text-[11px] font-bold text-blue-600 uppercase tracking-widest mb-2">Motivo de Consulta</h4>
                  <p className="text-gray-700 text-sm italic">"{consultaData.motivo}"</p>
                </div>

                <div className="bg-white border border-gray-100 rounded-[28px] p-6 shadow-sm">
                  <h4 className="text-[11px] font-bold text-blue-600 uppercase tracking-widest mb-2">Diagnóstico</h4>
                  <p className="text-gray-700 text-sm font-medium">{consultaData.diagnostico}</p>
                </div>

                {(consultaData.tratamiento || consultaData.observaciones) && (
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {consultaData.tratamiento && (
                      <div className="bg-white border border-gray-100 rounded-[28px] p-6 shadow-sm">
                        <h4 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tratamiento</h4>
                        <p className="text-gray-700 text-sm">{consultaData.tratamiento}</p>
                      </div>
                    )}
                    {consultaData.observaciones && (
                      <div className="bg-white border border-gray-100 rounded-[28px] p-6 shadow-sm">
                        <h4 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Observaciones</h4>
                        <p className="text-gray-700 text-sm">{consultaData.observaciones}</p>
                      </div>
                    )}
                  </div>
                )}
              </div>

              {/* Bloque Alergias y Condiciones Resumen */}
              {(alergiasList.length > 0 || condicionesList.length > 0) && (
                <div className="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  {alergiasList.length > 0 && (
                    <div className="bg-red-50/50 border border-red-100 rounded-[28px] p-6">
                      <h4 className="text-[11px] font-bold text-red-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <AlertTriangle size={14} /> Alergias ({alergiasList.length})
                      </h4>
                      <div className="space-y-2">
                        {alergiasList.map(a => (
                          <div key={a.alergia_id} className="flex items-center justify-between text-sm">
                            <span className="font-medium text-gray-700">{a.nombre}</span>
                            <div className="flex gap-2">
                              <span className={`px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase ${a.severidad === 'severo' ? 'bg-red-100 text-red-700' : a.severidad === 'moderado' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'}`}>{a.severidad}</span>
                              <span className="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase">{a.estado_clinico}</span>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                  {condicionesList.length > 0 && (
                    <div className="bg-amber-50/50 border border-amber-100 rounded-[28px] p-6">
                      <h4 className="text-[11px] font-bold text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <Shield size={14} /> Condiciones ({condicionesList.length})
                      </h4>
                      <div className="space-y-2">
                        {condicionesList.map(c => (
                          <div key={c.condicion_id} className="flex items-center justify-between text-sm">
                            <span className="font-medium text-gray-700">{c.nombre}</span>
                            <div className="flex gap-2">
                              <span className={`px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase ${c.severidad === 'severo' ? 'bg-red-100 text-red-700' : c.severidad === 'moderado' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'}`}>{c.severidad}</span>
                              <span className="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase">{c.estado_clinico}</span>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              )}

              {/* Bloque Vacunación Resumen */}
              {addVacunacion && (
                <div className="md:col-span-2 bg-emerald-50/50 border border-emerald-100 rounded-[28px] p-6">
                  <h4 className="text-[11px] font-bold text-emerald-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <Syringe size={14} /> Vacunación
                  </h4>
                  <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                    <div>
                      <span className="text-[10px] font-bold text-gray-400 uppercase block">Esquema</span>
                      <span className="font-medium text-gray-700">{esquemasVacunas.find(e => e.id === vacunaData.esquema_vacuna_id)?.nombre || 'N/A'}</span>
                    </div>
                    <div>
                      <span className="text-[10px] font-bold text-gray-400 uppercase block">Medicamento</span>
                      <span className="font-medium text-gray-700">{medicamentos.find(m => m.id === vacunaData.medicamento_id)?.nombre || 'N/A'}</span>
                    </div>
                    <div>
                      <span className="text-[10px] font-bold text-gray-400 uppercase block">Dosis</span>
                      <span className="font-medium text-gray-700">{vacunaData.dosis}</span>
                    </div>
                    <div>
                      <span className="text-[10px] font-bold text-gray-400 uppercase block">Lote</span>
                      <span className="font-medium text-gray-700">{vacunaData.lote}</span>
                    </div>
                  </div>
                </div>
              )}
            </div>

            <div className="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100">
              <button
                onClick={handlePrevStep}
                disabled={submitting}
                className="w-full sm:w-auto px-8 py-4 rounded-[20px] font-bold text-gray-500 hover:bg-gray-100 transition-all flex items-center justify-center gap-2"
              >
                <ChevronLeft size={20} strokeWidth={3} />
                Corregir datos
              </button>

              <div className="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <button
                  onClick={() => router.push('/dashboard')}
                  disabled={submitting}
                  className="w-full sm:w-auto px-8 py-4 rounded-[20px] font-bold text-pink-500 hover:bg-pink-50 transition-all"
                >
                  Cancelar
                </button>
                <button
                  onClick={handleSubmit}
                  disabled={submitting}
                  className="w-full sm:w-auto bg-[#2ecc71] hover:bg-[#27ae60] text-white px-10 py-4 rounded-[20px] font-bold shadow-xl shadow-[#2ecc71]/20 transition-all flex items-center justify-center gap-3 hover:-translate-y-1 disabled:opacity-50"
                >
                  {submitting ? <Loader2 className="animate-spin" size={20} /> : <HeartPulse size={20} />}
                  Registrar consulta
                </button>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Info helper */}
      <div className="flex items-center justify-center gap-12 text-gray-400 pt-4">
        <div className="flex items-center gap-3">
          <div className="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
            <Calendar size={16} />
          </div>
          <span className="text-xs font-bold uppercase tracking-widest">Registro automático</span>
        </div>
        <div className="flex items-center gap-3">
          <div className="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
            <HeartPulse size={16} />
          </div>
          <span className="text-xs font-bold uppercase tracking-widest">Control clínico</span>
        </div>
      </div>

      {isMascotaModalOpen && (
        <MascotaModal
          onClose={() => setIsMascotaModalOpen(false)}
          onSuccess={handleMascotaSuccess}
        />
      )}
    </div>
  );
}
