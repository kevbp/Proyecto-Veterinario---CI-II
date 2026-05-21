'use client';

import { useState, useEffect } from 'react';
import { Plus, Search, Users, Trash2, Loader2, Mail, Phone } from 'lucide-react';
import PropietarioForm from './PropietarioForm';
import { mascotaService } from '@/utils/mascotaService';
import { Propietario } from '@/interfaces/Mascota';
import { useSearchParams, usePathname, useRouter } from 'next/navigation';

export default function PropietariosList() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const pathname = usePathname();

  const [owners, setOwners] = useState<Propietario[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const editId = searchParams.get('owner_edit');
  const isCreating = searchParams.get('new_owner') === 'true';

  useEffect(() => {
    fetchOwners();
  }, []);

  const fetchOwners = async () => {
    try {
      setLoading(true);
      const data = await mascotaService.getAllOwners();
      // Sort Albergue to the top
      const sortedData = [...data].sort((a, b) => {
        if (a.email === 'veterinaria@munimolina.gob.pe') return -1;
        if (b.email === 'veterinaria@munimolina.gob.pe') return 1;
        return 0;
      });
      setOwners(sortedData);
      setError(null);
    } catch (err) {
      console.error('Error fetching owners:', err);
      setError('No se pudieron cargar los propietarios.');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (e: React.MouseEvent, id: string) => {
    e.stopPropagation();
    if (confirm('¿Estás seguro de que deseas eliminar este propietario?')) {
      try {
        await mascotaService.deletePropietario(id);
        setOwners(owners.filter(o => o.id !== id));
      } catch (err) {
        alert('Error al eliminar el propietario');
      }
    }
  };

  const handleRowClick = (owner: Propietario) => {
    // Si es el albergue, no permitir edición
    if (owner.email === 'veterinaria@munimolina.gob.pe') return;

    const params = new URLSearchParams(searchParams.toString());
    params.set('owner_edit', owner.id);
    params.delete('new_owner');
    router.push(`${pathname}?${params.toString()}`);
  };

  const handleCreateClick = () => {
    const params = new URLSearchParams(searchParams.toString());
    params.set('new_owner', 'true');
    params.delete('owner_edit');
    router.push(`${pathname}?${params.toString()}`);
  };

  const handleCancel = () => {
    const params = new URLSearchParams(searchParams.toString());
    params.delete('owner_edit');
    params.delete('new_owner');
    router.push(`${pathname}?${params.toString()}`);
    fetchOwners();
  };

  const filteredOwners = owners.filter(owner => {
    const search = searchTerm.toLowerCase();
    return (
      owner.nombre.toLowerCase().includes(search) ||
      owner.paterno.toLowerCase().includes(search) ||
      owner.nro_doc.toString().includes(search) ||
      owner.email.toLowerCase().includes(search)
    );
  });

  // Sort logic for display (Albergue top unless searching)
  const displayedOwners = searchTerm
    ? filteredOwners
    : [...filteredOwners].sort((a, b) => {
      if (a.email === 'veterinaria@munimolina.gob.pe') return -1;
      if (b.email === 'veterinaria@munimolina.gob.pe') return 1;
      return 0;
    });

  if (isCreating || editId) {
    return <PropietarioForm onCancel={handleCancel} editId={editId || undefined} />;
  }

  return (
    <div className="bg-white/50 backdrop-blur-md rounded-[28px] p-7 shadow-sm border border-white/60">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
          <h2 className="text-xl font-bold text-gray-800">Directorio de Propietarios</h2>
          <p className="text-sm text-gray-500 mt-1">Gestiona los propietarios registrados en el sistema</p>
        </div>

        <div className="flex items-center gap-3 w-full sm:w-auto">
          <div className="relative flex-1 sm:min-w-[250px]">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
            <input
              type="text"
              placeholder="Buscar propietario..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 bg-white/60 border border-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2ecc71]/50 text-sm placeholder:text-gray-400"
            />
          </div>
          <button
            onClick={handleCreateClick}
            className="flex items-center space-x-2 bg-gradient-to-r from-[#015f33] to-[#2ecc71] hover:shadow-lg hover:shadow-[#2ecc71]/30 text-white px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap"
          >
            <Plus size={18} />
            <span>Crear Propietario</span>
          </button>
        </div>
      </div>

      <div className="overflow-x-auto">
        <table className="w-full text-[13px] text-left text-gray-500">
          <thead className="text-[11px] text-gray-400 uppercase font-bold tracking-wider">
            <tr className="border-b border-gray-200/50">
              <th scope="col" className="px-4 py-3 pb-4 w-16 text-center">Acciones</th>
              <th scope="col" className="px-4 py-3 pb-4">Propietario</th>
              <th scope="col" className="px-4 py-3 pb-4">Documento</th>
              <th scope="col" className="px-4 py-3 pb-4">Contacto</th>
              <th scope="col" className="px-4 py-3 pb-4 text-center">Estado</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr>
                <td colSpan={5} className="px-4 py-8 text-center">
                  <div className="flex flex-col items-center gap-2">
                    <Loader2 className="animate-spin text-[#2ecc71]" size={24} />
                    <span className="text-gray-400">Cargando propietarios...</span>
                  </div>
                </td>
              </tr>
            ) : displayedOwners.length === 0 ? (
              <tr>
                <td colSpan={5} className="px-4 py-8 text-center text-gray-400">
                  No se encontraron propietarios que coincidan con la búsqueda.
                </td>
              </tr>
            ) : (
              displayedOwners.map((owner) => {
                const isAlbergue = owner.email === 'veterinaria@munimolina.gob.pe';

                return (
                  <tr
                    key={owner.id}
                    onClick={() => handleRowClick(owner)}
                    className={`border-b border-gray-200/50 last:border-0 transition-colors cursor-pointer ${isAlbergue ? 'bg-green-50/70 hover:bg-green-100/70' : 'hover:bg-white/40'
                      }`}
                  >
                    <td className="px-4 py-4.5 text-center">
                      {!isAlbergue && (
                        <button
                          title="Eliminar registro"
                          onClick={(e) => handleDelete(e, owner.id)}
                          className="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all"
                        >
                          <Trash2 size={18} strokeWidth={2} />
                        </button>
                      )}
                    </td>
                    <td className="px-4 py-4.5 font-bold text-gray-800 flex items-center space-x-3">
                      <div className="w-9 h-9 rounded-full bg-white shadow-sm flex items-center justify-center text-[#015f33] shrink-0">
                        <Users size={16} />
                      </div>
                      <span>{owner.nombre} {owner.paterno}</span>
                    </td>
                    <td className="px-4 py-4.5 font-medium text-gray-800">{owner.nro_doc}</td>
                    <td className="px-4 py-4.5">
                      <div className="flex items-center gap-1.5 font-medium text-gray-800">
                        <Mail size={14} className="text-gray-400" />
                        {owner.email}
                      </div>
                      <div className="flex items-center gap-1.5 text-[11px] text-gray-500 mt-0.5">
                        <Phone size={14} className="text-gray-400" />
                        {owner.celular || 'N/A'}
                      </div>
                    </td>
                    <td className="px-4 py-4.5 text-center">
                      <span className={`px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide ${owner.celular ? 'bg-[#2ecc71]/20 text-[#015f33]' : 'bg-red-100 text-red-600'
                        }`}>
                        {owner.celular ? 'Verificado' : 'Pendiente'}
                      </span>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
