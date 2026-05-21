import api from './api';

export interface Personal {
  id: string;
  nro_doc: string;
  nombre: string;
  paterno: string;
  materno: string;
  nombre_completo: string;
  email: string;
  celular: string;
  profesion: string;
  rol_sistema: string;
  user?: {
    id: string;
    email: string;
  };
}

export const personalService = {
  getAll: async (params: any = {}): Promise<Personal[]> => {
    const response = await api.get('/personal', { params });
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getAllActive: async (): Promise<Personal[]> => {
    const response = await api.get('/personal?active=1');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getById: async (id: string): Promise<Personal> => {
    const response = await api.get(`/personal/${id}`);
    return response.data.data || response.data;
  },

  create: async (data: any): Promise<Personal> => {
    const response = await api.post('/personal', data);
    return response.data.data || response.data;
  },

  update: async (id: string, data: any): Promise<Personal> => {
    const response = await api.put(`/personal/${id}`, data);
    return response.data.data || response.data;
  },

  delete: async (id: string): Promise<void> => {
    await api.delete(`/personal/${id}`);
  }
};
