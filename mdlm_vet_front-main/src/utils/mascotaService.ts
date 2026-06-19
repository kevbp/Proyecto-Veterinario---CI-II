import api from './api';
import { Mascota, Propietario } from '@/interfaces/Mascota';

export const mascotaService = {
  // Animales
  getAllAnimals: async (filters: { albergue?: boolean } = {}): Promise<Mascota[]> => {
    const params = new URLSearchParams();
    if (filters.albergue !== undefined) {
      params.append('albergue', filters.albergue ? '1' : '0');
    }

    const response = await api.get(`/animales${params.toString() ? '?' + params.toString() : ''}`);
    // Laravel usa paginación para mejorar el rendimiento en la carga de datos
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getAnimalById: async (id: string): Promise<Mascota> => {
    const response = await api.get(`/animales/${id}`);
    return response.data.data || response.data;
  },

  createAnimal: async (data: any): Promise<Mascota> => {
    const response = await api.post('/animales', data);
    return response.data.data || response.data;
  },

  updateAnimal: async (id: string, data: any): Promise<Mascota> => {
    const response = await api.put(`/animales/${id}`, data);
    return response.data.data || response.data;
  },

  deleteAnimal: async (id: string): Promise<void> => {
    await api.delete(`/animales/${id}`);
  },

  // Propietarios (needed for select)
  getAllOwners: async (): Promise<Propietario[]> => {
    const response = await api.get('/propietarios');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getPropietarioById: async (id: string): Promise<Propietario> => {
    const response = await api.get(`/propietarios/${id}`);
    return response.data.data || response.data;
  },

  createPropietario: async (data: any): Promise<Propietario> => {
    const response = await api.post('/propietarios', data);
    return response.data.data || response.data;
  },

  updatePropietario: async (id: string, data: any): Promise<Propietario> => {
    const response = await api.put(`/propietarios/${id}`, data);
    return response.data.data || response.data;
  },

  deletePropietario: async (id: string): Promise<void> => {
    await api.delete(`/propietarios/${id}`);
  },

  // Especies
  getAllSpecies: async (): Promise<any[]> => {
    const response = await api.get('/especies');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Razas
  getAllRazas: async (): Promise<any[]> => {
    const response = await api.get('/razas');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Tipo Documentos
  getTipoDocumentos: async (): Promise<any[]> => {
    const response = await api.get('/tipo-documentos');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Historial Clínico
  getAnimalTimeline: async (animalId: string): Promise<any[]> => {
    const response = await api.get(`/animales/${animalId}/historial`);
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Alergias de un animal
  getAnimalAlergias: async (animalId: string): Promise<any[]> => {
    const response = await api.get(`/animales/${animalId}/alergias`);
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Condiciones de un animal
  getAnimalCondiciones: async (animalId: string): Promise<any[]> => {
    const response = await api.get(`/animales/${animalId}/condiciones`);
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  // Consultas
  createConsulta: async (data: any): Promise<any> => {
    const response = await api.post('/consultas', data);
    return response.data.data || response.data;
  },

  // Crear alergia de animal
  createAnimalAlergia: async (animalId: string, data: any): Promise<any> => {
    const response = await api.post(`/animales/${animalId}/alergias`, data);
    return response.data.data || response.data;
  },

  // Crear condición de animal
  createAnimalCondicion: async (animalId: string, data: any): Promise<any> => {
    const response = await api.post(`/animales/${animalId}/condiciones`, data);
    return response.data.data || response.data;
  },

  // Registrar fallecimiento
  registrarFallecimiento: async (animalId: string): Promise<any> => {
    const response = await api.patch(`/animales/${animalId}/fallecimiento`);
    return response.data.data || response.data;
  },

  // Cliente (Portal de Propietarios)
  getClientePerfil: async (): Promise<Propietario> => {
    const response = await api.get('/cliente/perfil');
    return response.data.data || response.data;
  },

  getClienteMascotas: async (): Promise<Mascota[]> => {
    const response = await api.get('/cliente/mascotas');
    return Array.isArray(response.data) ? response.data : (response.data.data || []);
  },

  getClienteMascotaById: async (id: string): Promise<Mascota> => {
    const response = await api.get(`/cliente/mascotas/${id}`);
    return response.data.data || response.data;
  },
};
