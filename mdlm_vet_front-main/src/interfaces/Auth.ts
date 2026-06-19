import { User } from './User';

/** Credenciales para el login */
export interface LoginCredentials {
  email: string;
  password: string;
}

/** Respuesta del endpoint POST /api/auth/login — espejo de AuthTokenDTO */
export interface AuthTokenResponse {
  access_token: string;
  token_type: string;
  expires_in: number;
  user: User;
}
