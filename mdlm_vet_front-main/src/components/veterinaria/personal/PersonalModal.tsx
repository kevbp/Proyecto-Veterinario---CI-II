import { UserPlus, Mail, Loader2 } from 'lucide-react';

interface PersonalModalProps {
  isOpen: boolean;
  onClose: () => void;
  onConfirm: () => void;
  formData: {
    nombre: string;
    paterno: string;
    materno: string;
    tipo_doc_id: string;
    nro_doc: string;
    rol_sistema: string;
    email: string;
    especialidad: string;
    celular?: string;
  };
  loading?: boolean;
}

export default function PersonalModal({ isOpen, onClose, onConfirm, formData, loading }: PersonalModalProps) {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
      <div className="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl animate-in fade-in zoom-in-95 duration-200 border border-gray-100">
        <div className="flex flex-col items-center text-center mb-6">
          <div className="w-16 h-16 bg-[#11ba82]/10 text-[#11ba82] rounded-full flex items-center justify-center mb-4">
            <UserPlus size={32} strokeWidth={1.5} />
          </div>
          <h3 className="text-xl font-bold text-gray-800 mb-2">Confirmar Registro</h3>
          <p className="text-gray-600 mb-5 text-[14px]">
            Revisa los datos antes de confirmar la creación del nuevo personal.
          </p>
          
          <div className="w-full text-left bg-gray-50 p-5 rounded-2xl border border-gray-100 mb-5 space-y-3 text-[14px]">
            <div className="grid grid-cols-3 gap-2">
              <span className="font-semibold text-gray-500 col-span-1">Nombre:</span> 
              <span className="text-gray-800 font-medium col-span-2">{formData.nombre} {formData.paterno} {formData.materno}</span>
            </div>
            <div className="grid grid-cols-3 gap-2">
              <span className="font-semibold text-gray-500 col-span-1">Documento:</span> 
              <span className="text-gray-800 font-medium col-span-2">{formData.nro_doc}</span>
            </div>
            <div className="grid grid-cols-3 gap-2">
              <span className="font-semibold text-gray-500 col-span-1">Rol:</span> 
              <span className="text-gray-800 font-medium col-span-2 capitalize">{formData.rol_sistema || '-'}</span>
            </div>
          </div>

          <div className="flex gap-3 bg-blue-50 text-blue-700 p-4 rounded-2xl border border-blue-100/50 text-left">
            <Mail className="shrink-0 mt-0.5 text-blue-500" size={20} />
            <p className="text-[13px] font-medium leading-relaxed">
              Se enviará automáticamente un correo electrónico a <strong className="text-blue-800">{formData.email || 'la dirección especificada'}</strong> con las instrucciones para que el usuario valide sus credenciales y configure su acceso.
            </p>
          </div>
        </div>
        
        <div className="flex justify-between gap-3">
          <button
            type="button"
            onClick={onClose}
            disabled={loading}
            className="flex-1 px-6 py-2.5 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-colors disabled:opacity-50"
          >
            Volver y editar
          </button>
          <button
            type="button"
            onClick={onConfirm}
            disabled={loading}
            className="flex-1 px-6 py-2.5 bg-gradient-to-r from-[#015f33] to-[#2ecc71] text-white font-bold rounded-xl hover:opacity-90 transition-opacity shadow-sm shadow-[#2ecc71]/30 flex items-center justify-center gap-2 disabled:opacity-50"
          >
            {loading ? <Loader2 size={18} className="animate-spin" /> : null}
            Confirmar y enviar
          </button>
        </div>
      </div>
    </div>
  );
}
