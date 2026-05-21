import api from './api';

export const catalogoService = {
  // Catálogo de Condiciones
  getCondiciones: async (): Promise<any[]> => {
    const response = await api.get('/condiciones');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getCondicionById: async (id: string): Promise<any> => {
    const response = await api.get(`/condiciones/${id}`);
    return response.data.data || response.data;
  },

  // Catálogo de Alergias
  getAlergias: async (): Promise<any[]> => {
    const response = await api.get('/alergias');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getAlergiaById: async (id: string): Promise<any> => {
    const response = await api.get(`/alergias/${id}`);
    return response.data.data || response.data;
  },

  // Esquemas de Vacunas
  getEsquemasVacunas: async (): Promise<any[]> => {
    const response = await api.get('/esquema-vacunas');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Medicamentos
  getMedicamentos: async (): Promise<any[]> => {
    const response = await api.get('/medicamentos');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Crear vacuna de animal
  createVacunaAnimal: async (data: any): Promise<any> => {
    const response = await api.post('/vacunas-animales', data);
    return response.data.data || response.data;
  },
};

