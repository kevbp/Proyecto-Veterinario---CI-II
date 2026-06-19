import api from './api';

export interface Campania {
  id: string;
  nombre: string;
  descripcion?: string;
  lugar?: string;
  fecha_hora_inicio?: string;
  fecha_hora_fin?: string;
  estado: 'PLANIFICADA' | 'EN_CURSO' | 'FINALIZADA' | 'CANCELADA';
  responsable?: {
    id: string;
    nombre: string;
  };
  estadisticas?: {
    total_vacunas: number;
    total_desparasitaciones: number;
  };
  created_at: string;
}

export interface FinalizarCampaniaData {
  insumos_consumidos: Array<{
    medicamento_id: string;
    cantidad: number;
  }>;
}

export const campaniaService = {
  getAll: async () => {
    const response = await api.get('/campanias');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getById: async (id: string) => {
    const response = await api.get(`/campanias/${id}`);
    return response.data.data || response.data;
  },

  create: async (data: any) => {
    const response = await api.post('/campanias', data);
    return response.data;
  },

  update: async (id: string, data: any) => {
    const response = await api.put(`/campanias/${id}`, data);
    return response.data;
  },

  delete: async (id: string) => {
    const response = await api.delete(`/campanias/${id}`);
    return response.data;
  },

  iniciar: async (id: string) => {
    const response = await api.patch(`/campanias/${id}/iniciar`);
    return response.data;
  },

  cancelar: async (id: string) => {
    const response = await api.patch(`/campanias/${id}/cancelar`);
    return response.data;
  },

  finalizar: async (id: string, data: FinalizarCampaniaData) => {
    const response = await api.post(`/campanias/${id}/finalizar`, data);
    return response.data;
  },

  getEstadisticas: async (id: string) => {
    const response = await api.get(`/campanias/${id}/estadisticas`);
    return response.data;
  }
};
