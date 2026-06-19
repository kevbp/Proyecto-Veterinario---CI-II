export interface Mascota {
  id: string;
  nombre: string;
  especie_id: string;
  raza_id: string;
  sexo: string;
  color: string;
  especie: string; // From refactored resource
  raza: string;    // From refactored resource
  peligroso: boolean;
  esterilizacion: boolean;
  propietario_id: string;
  propietario: string; // Full name string from resource
  propietario_celular?: string;
  hogar: string;       // Address from resource
  alergias?: any[];
  condiciones?: any[];
  fecha_registro: string;
  fallecido: boolean;
  fecha_fallecimiento?: string;
}

export interface Propietario {
  id: string;
  nombre: string;
  paterno: string;
  materno?: string;
  nombre_completo?: string;
  tipo_doc?: string;
  nro_doc: number;
  email: string;
  celular?: string;
  nro_emergencia?: string | number;
  direccion: string;
  vivienda_latitud?: number;
  vivienda_longitud?: number;
}
