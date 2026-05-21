import api from './api';

export interface AdopcionData {
  animal_id: string;
  propietario_nuevo_id: string;
  observaciones?: string;
  campania_id?: string;
}

export const adopcionService = {
  // Listar adopciones
  getAllAdoptions: async (filters: { campania_id?: string, fecha_inicio?: string, fecha_fin?: string } = {}) => {
    const params = new URLSearchParams();
    if (filters.campania_id) params.append('campania_id', filters.campania_id);
    if (filters.fecha_inicio) params.append('fecha_inicio', filters.fecha_inicio);
    if (filters.fecha_fin) params.append('fecha_fin', filters.fecha_fin);

    const response = await api.get(`/adopciones${params.toString() ? '?' + params.toString() : ''}`);
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Registrar adopción
  registrarAdopcion: async (animalId: string, data: AdopcionData) => {
    const response = await api.post(`/animales/${animalId}/adopciones`, data);
    return response.data;
  },

  // Obtener campañas para el select
  getAllCampaigns: async () => {
    const response = await api.get('/campanias');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Estadísticas (por si se necesitan en el listado)
  getEstadisticasByCampaign: async (campaignId: string) => {
    const response = await api.get(`/adopciones/estadisticas-adopcion-por-campania?campania_id=${campaignId}`);
    return response.data;
  },

  getEstadisticasByDates: async (fechaInicio: string, fechaFin: string) => {
    const response = await api.get(`/adopciones/estadisticas-adopcion-por-fechas?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
    return response.data;
  }
};
