import { create } from 'zustand';
import api from '@/utils/api';
import { Service, ServiceState } from '@/interfaces/Service';

// Define el store para gestionar servicios
export const useServiceStore = create<ServiceState>((set) => ({
  services: [],
  isLoading: false,
  error: null,
  fetchServices: async () => {
    set({ isLoading: true, error: null });
    try {
      // Usamos la instancia de Axios para la llamada
      const response = await api.get<Service[]>('/services');
      set({ services: response.data, isLoading: false });
    } catch (err: any) {
      set({ error: err.message || 'Error cargando servicios', isLoading: false });
    }
  },
}));