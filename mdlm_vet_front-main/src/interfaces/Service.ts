// Define el tipo de dato para tus servicios
export interface Service {
  id: string;
  name: string;
  description: string;
  price?: number;
}

export interface ServiceState {
  services: Service[];
  isLoading: boolean;
  error: string | null;
  fetchServices: () => Promise<void>;
}