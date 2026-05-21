import api from './api';

export interface Medicamento {
  id: string;
  nombre: string;
  stock: number;
  unidad_medida: string;
}

export const inventarioService = {
  getAllMedicamentos: async () => {
    const response = await api.get('/inventario/medicamentos');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  }
};
