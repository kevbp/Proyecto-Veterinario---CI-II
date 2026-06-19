'use client';

import { useState } from 'react';

interface ServiceItem {
  title: string;
  description: string;
  cost: string;
  icon: string;
}

const services: ServiceItem[] = [
  {
    title: 'Vacunaciones',
    description:
      'Protege a tu mascota con nuestro programa completo de vacunación. Incluye vacunas antirrábica, polivalente y refuerzos anuales, aplicadas por veterinarios certificados.',
    cost: 'S/ 25.00',
    icon: '💉',
  },
  {
    title: 'Desparasitaciones',
    description:
      'Tratamiento integral contra parásitos internos y externos. Realizamos un diagnóstico previo para aplicar el desparasitante más adecuado según peso y edad.',
    cost: 'S/ 15.00',
    icon: '🛡️',
  },
  {
    title: 'Esterilizaciones',
    description:
      'Cirugía de esterilización segura con anestesia general, monitoreo constante y seguimiento post-operatorio. Contribuye al control responsable de la población animal.',
    cost: 'S/ 50.00',
    icon: '🏥',
  },
  {
    title: 'Consultas',
    description:
      'Atención veterinaria general para evaluación clínica, diagnóstico y tratamiento. Incluye revisión física completa, control de peso y orientación nutricional.',
    cost: 'S/ 10.00',
    icon: '🩺',
  },
];

interface ServiceCatalogProps {
  onClose: () => void;
}

export default function ServiceCatalog({ onClose }: ServiceCatalogProps) {
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  const toggle = (idx: number) => {
    setOpenIndex(openIndex === idx ? null : idx);
  };

  return (
    <div className="w-full bg-white rounded-2xl shadow-2xl p-6 max-h-[520px] overflow-y-auto animate-fade-in">
      {/* Header */}
      <div className="flex items-center justify-between mb-5">
        <h2 className="text-gray-800 font-bold text-lg">Catálogo de Servicios</h2>
        <button
          onClick={onClose}
          className="text-gray-400 hover:text-gray-700 transition-colors text-2xl leading-none cursor-pointer"
          aria-label="Cerrar"
        >
          ×
        </button>
      </div>

      {/* Accordion */}
      <div className="space-y-2">
        {services.map((service, idx) => {
          const isOpen = openIndex === idx;
          return (
            <div
              key={idx}
              className="border border-gray-100 rounded-xl overflow-hidden transition-shadow duration-200 hover:shadow-md"
            >
              {/* Header del acordeón */}
              <button
                onClick={() => toggle(idx)}
                className="w-full flex items-center justify-between px-4 py-3.5 bg-gray-50/80 
                           hover:bg-gray-100/80 transition-colors duration-200 cursor-pointer"
              >
                <div className="flex items-center gap-3">
                  <span className="text-xl">{service.icon}</span>
                  <span className="text-gray-800 font-semibold text-sm">{service.title}</span>
                </div>
                <div className="flex items-center gap-3">
                  <span className="text-[#015f33] font-bold text-sm">{service.cost}</span>
                  <svg
                    className={`w-4 h-4 text-gray-400 transition-transform duration-300 ${isOpen ? 'rotate-180' : ''}`}
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    strokeWidth={2}
                  >
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </button>

              {/* Contenido colapsable con animación suave */}
              <div
                className="transition-all duration-300 ease-in-out overflow-hidden"
                style={{
                  maxHeight: isOpen ? '200px' : '0px',
                  opacity: isOpen ? 1 : 0,
                }}
              >
                <div className="px-4 py-3 border-t border-gray-100">
                  <p className="text-gray-500 text-xs leading-relaxed">{service.description}</p>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
