import { create } from 'zustand';
import api from '@/utils/api';
import { User } from '@/interfaces/User';

/** Extrae el User del payload de /auth/me (puede venir como array o como objeto) */
function normalizeUserPayload(data: unknown): User {
  if (Array.isArray(data)) return data[0] as User;
  return data as User;
}
interface AuthState {
  user: User | null;
  token: string | null;
  isLoading: boolean;
  error: string | null;

  /** Setea el usuario y token tras el callback del SSO */
  setAuth: (user: User, token: string) => void;

  /** Cierra sesión */
  logout: () => Promise<void>;

  /** Restaura la sesión desde localStorage (al cargar la app) */
  hydrate: () => void;

  /** Verificar UN permiso */
  canAccess: (permission: string) => boolean;

  /** Verificar UN rol */
  hasRole: (role: string) => boolean;

  /** Verificar TODOS los permisos (AND) */
  canAccessMultiple: (permissions: string[]) => boolean;

  /** Verificar AL MENOS UN permiso (OR) */
  canAccessAny: (permissions: string[]) => boolean;
}

export const useAuthStore = create<AuthState>((set, get) => ({
  user: null,
  token: null,
  isLoading: false,
  error: null,

  setAuth: (user: User, token: string) => {
    set({ user, token, error: null });
  },

  logout: async () => {
    try {
      // Opcional: Avisar al backend de veterinaria
      await api.post('/auth/logout').catch(() => { });
    } finally {
      localStorage.removeItem('access_token');
      localStorage.removeItem('user_info');
      set({ user: null, token: null, error: null });

      // Redirigir al inicio o al logout central del SSO si estuviera configurado
      window.location.href = '/';
    }
  },

  hydrate: () => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('access_token');

      if (token) {
        set({ token, isLoading: true });

        // Hidratar temporalmente con lo que haya en localStorage para evitar
        // un parpadeo, pero SIEMPRE revalidar contra /auth/me
        const cached = localStorage.getItem('user_info');
        if (cached) {
          try {
            set({ user: JSON.parse(cached) });
          } catch { /* cache corrupta, se ignora */ }
        }

        // Revalidar roles & permissions desde el backend (fuente de verdad)
        api
          .get('/auth/me')
          .then(({ data }) => {
            const user = normalizeUserPayload(data);
            localStorage.setItem('user_info', JSON.stringify(user));
            set({ user, isLoading: false });
          })
          .catch(() => {
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_info');
            set({ token: null, user: null, isLoading: false });
          });
      }
    }
  },
  // ✅ Verificar UN permiso
  canAccess: (permission: string) => {
    const { user } = get();
    return user?.permissions?.includes(permission) ?? false;
  },

  // ✅ Verificar UN rol
  hasRole: (role: string) => {
    const { user } = get();
    return user?.roles?.includes(role) ?? false;
  },

  // ✅ Verificar TODOS los permisos (AND)
  canAccessMultiple: (permissions: string[]) => {
    const { user } = get();
    return permissions.every(p => user?.permissions?.includes(p) ?? false);
  },

  // ✅ Verificar AL MENOS UN permiso (OR)
  canAccessAny: (permissions: string[]) => {
    const { user } = get();
    return permissions.some(p => user?.permissions?.includes(p) ?? false);
  },
}
)
);
