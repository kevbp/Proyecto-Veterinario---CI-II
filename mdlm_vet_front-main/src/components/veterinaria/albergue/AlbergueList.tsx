'use client';

import { useEffect, useState } from 'react';
import { Search, Dog, HeartHandshake, Loader2, FileText } from 'lucide-react';
import AdopcionFormModal from './AdopcionFormModal';
import { useRouter } from 'next/navigation';
import { mascotaService } from '@/utils/mascotaService';
import { Mascota } from '@/interfaces/Mascota';

export default function AlbergueList() {
  const router = useRouter();
  const [selectedPet, setSelectedPet] = useState<{ id: string, nombre: string } | null>(null);
  const [pets, setPets] = useState<Mascota[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAlberguePets();
  }, []);

  const fetchAlberguePets = async () => {
    try {
      setLoading(true);
      const data = await mascotaService.getAllAnimals({ albergue: true });
      setPets(data);
    } catch (err) {
      console.error('Error fetching albergue pets:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleRowClick = (id: string) => {
    router.push(`/mascotas?edit=${id}`);
  };

  return (
    <>
      <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-7 shadow-sm border border-white/60">
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
          <div>
            <h2 className="text-xl font-bold text-gray-800">Mascotas en Albergue</h2>
            <p className="text-sm text-gray-500 mt-1">Gestiona las mascotas del albergue listas para adopción</p>
          </div>
          
          <div className="flex items-center gap-3 w-full sm:w-auto">
            <div className="relative flex-1 sm:min-w-[250px]">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
              <input 
                type="text" 
                placeholder="Buscar mascota..." 
                className="w-full pl-10 pr-4 py-2.5 bg-white/60 border border-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm placeholder:text-gray-400"
              />
            </div>
          </div>
        </div>
        
        <div className="overflow-x-auto">
          <table className="w-full text-[13px] text-left text-gray-500">
            <thead className="text-[11px] text-gray-400 uppercase font-bold tracking-wider">
              <tr className="border-b border-gray-200/50">
                <th scope="col" className="px-4 py-3 pb-4">Acción</th>
                <th scope="col" className="px-4 py-3 pb-4">Mascota</th>
                <th scope="col" className="px-4 py-3 pb-4">Especie / Raza</th>
                <th scope="col" className="px-4 py-3 pb-4">Sexo</th>
                <th scope="col" className="px-4 py-3 pb-4 text-center">Estado</th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-4 py-8 text-center">
                    <div className="flex flex-col items-center gap-2">
                      <Loader2 className="animate-spin text-[#2ecc71]" size={24} />
                      <span className="text-gray-400">Cargando mascotas del albergue...</span>
                    </div>
                  </td>
                </tr>
              ) : pets.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-4 py-8 text-center text-gray-400">
                    No hay mascotas en el albergue.
                  </td>
                </tr>
              ) : (
                pets.map((pet) => (
                  <tr 
                    key={pet.id} 
                    onClick={() => handleRowClick(pet.id)}
                    className="border-b border-gray-200/50 last:border-0 hover:bg-white/40 transition-colors cursor-pointer"
                  >
                    <td className="px-4 py-4.5" onClick={(e) => e.stopPropagation()}>
                      <div className="flex items-center gap-2">
                        <button 
                          onClick={() => setSelectedPet({ id: pet.id, nombre: pet.nombre })}
                          className="flex items-center space-x-1.5 bg-pink-100 text-pink-600 hover:bg-pink-500 hover:text-white px-3 py-1.5 rounded-full text-[11px] font-bold transition-colors shadow-sm"
                        >
                          <HeartHandshake size={14} />
                          <span>¡Adoptado!</span>
                        </button>
                        <button 
                          title="Ver Historial Clínico"
                          onClick={() => router.push(`/mascotas/${pet.id}/historial`)}
                          className="p-1.5 text-gray-400 hover:text-[#015f33] hover:bg-[#015f33]/10 rounded-full transition-all"
                        >
                          <FileText size={18} strokeWidth={2} />
                        </button>
                      </div>
                    </td>
                    <td className="px-4 py-4.5 font-bold text-gray-800 flex items-center space-x-3">
                      <div className="w-9 h-9 rounded-full bg-white shadow-sm flex items-center justify-center text-[#015f33] shrink-0">
                        <Dog size={16} />
                      </div>
                      <span>{pet.nombre}</span>
                    </td>
                    <td className="px-4 py-4.5">
                      <div className="font-medium text-gray-800">{pet.especie || 'N/A'}</div>
                      <div className="text-[11px] text-gray-500">{pet.raza || 'N/A'}</div>
                    </td>
                    <td className="px-4 py-4.5 font-medium">{pet.sexo}</td>
                    <td className="px-4 py-4.5 text-center">
                      <span className="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide bg-[#2ecc71]/20 text-[#015f33]">
                        Activo
                      </span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {selectedPet && (
        <AdopcionFormModal 
          petId={selectedPet.id} 
          petName={selectedPet.nombre}
          onClose={() => setSelectedPet(null)} 
          onSuccess={() => {
            setSelectedPet(null);
            fetchAlberguePets();
          }}
        />
      )}
    </>
  );
}
