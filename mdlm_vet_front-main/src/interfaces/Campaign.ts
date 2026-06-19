/** Espejo del JSON de /api/public/campanias-activas */
export interface Campaign {
  nombre: string;
  descripcion: string;
  lugar: string;
  fecha_inicio: string;
  fecha_fin: string;
  estado: string;
}

export interface CampaignResponse {
  data: Campaign[];
}
