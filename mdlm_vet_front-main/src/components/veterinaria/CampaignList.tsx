'use client';

import { Campaign } from '@/interfaces/Campaign';

/** Badge de color según el estado de la campaña */
function EstadoBadge({ estado }: { estado: string }) {
  const styles: Record<string, string> = {
    en_curso: 'bg-green-100 text-green-700',
    planificada: 'bg-yellow-100 text-yellow-700',
    finalizada: 'bg-gray-100 text-gray-500',
  };

  const labels: Record<string, string> = {
    en_curso: 'En curso',
    planificada: 'Planificada',
    finalizada: 'Finalizada',
  };

  return (
    <span
      className={`text-xs font-semibold px-2.5 py-1 rounded-full ${styles[estado] || 'bg-gray-100 text-gray-600'}`}
    >
      {labels[estado] || estado}
    </span>
  );
}

interface CampaignListProps {
  campaigns: Campaign[];
  isLoading: boolean;
  error: string | null;
  onClose: () => void;
}

export default function CampaignList({ campaigns, isLoading, error, onClose }: CampaignListProps) {
  return (
    <div className="w-full bg-white rounded-2xl shadow-2xl p-6 max-h-[520px] overflow-y-auto animate-fade-in">
      {/* Header */}
      <div className="flex items-center justify-between mb-4">
        <h2 className="text-gray-800 font-bold text-lg">Cronograma de Campañas</h2>
        <button
          onClick={onClose}
          className="text-gray-400 hover:text-gray-700 transition-colors text-2xl leading-none cursor-pointer"
          aria-label="Cerrar"
        >
          ×
        </button>
      </div>

      {/* Loading */}
      {isLoading && (
        <div className="flex justify-center py-12">
          <div className="w-8 h-8 border-4 border-[#2ecc71] border-t-transparent rounded-full animate-spin" />
        </div>
      )}

      {/* Error */}
      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 text-center">
          {error}
        </div>
      )}

      {/* Cards */}
      {!isLoading && !error && campaigns.length === 0 && (
        <p className="text-gray-400 text-center py-8">No hay campañas activas.</p>
      )}

      <div className="space-y-3">
        {campaigns.map((campaign, idx) => (
          <div
            key={idx}
            className="border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow duration-200 bg-gray-50/50"
          >
            <div className="flex items-start justify-between gap-3 mb-2">
              <h3 className="text-gray-800 font-semibold text-sm leading-tight">
                {campaign.nombre}
              </h3>
              <EstadoBadge estado={campaign.estado} />
            </div>

            <div 
              className="text-gray-500 text-xs mb-3 leading-relaxed campaign-description"
              dangerouslySetInnerHTML={{ __html: campaign.descripcion }}
            />

            <div className="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
              <span className="flex items-center gap-1">
                📍 {campaign.lugar}
              </span>
              <span className="flex items-center gap-1">
                📅 {campaign.fecha_inicio}
              </span>
              <span className="flex items-center gap-1">
                🏁 {campaign.fecha_fin}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
