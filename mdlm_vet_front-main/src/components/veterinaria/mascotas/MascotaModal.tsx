'use client';

import { X } from 'lucide-react';
import MascotaForm from './MascotaForm';
import { Mascota } from '@/interfaces/Mascota';

interface MascotaModalProps {
  onClose: () => void;
  onSuccess: (mascota: Mascota) => void;
}

export default function MascotaModal({ onClose, onSuccess }: MascotaModalProps) {
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div 
        className="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"
        onClick={onClose}
      />
      
      {/* Modal Content */}
      <div className="relative bg-white rounded-[32px] shadow-2xl w-full max-w-5xl flex flex-col max-h-[90vh] overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        {/* Header */}
        <div className="px-8 py-6 flex items-center justify-between border-b border-gray-100 shrink-0 bg-gray-50/50">
          <div>
            <h2 className="text-xl font-extrabold text-gray-800">Registrar Nueva Mascota</h2>
            <p className="text-sm text-gray-500">Complete los datos para registrar a la mascota en el sistema</p>
          </div>
          <button 
            onClick={onClose}
            className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
          >
            <X size={24} />
          </button>
        </div>
        
        {/* Body (Scrollable) */}
        <div className="p-8 overflow-y-auto custom-scrollbar">
          <MascotaForm 
            onCancel={onClose} 
            onSuccess={(pet) => {
              onSuccess(pet);
              onClose();
            }}
          />
        </div>
      </div>
    </div>
  );
}
