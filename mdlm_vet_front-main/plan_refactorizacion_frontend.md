# Plan de Refactorización para el Frontend (Next.js) - Integración SSO

Este documento detalla los cambios necesarios en el aplicativo frontend (Next.js) para completar la integración con el inicio de sesión único (SSO) y abandonar la autenticación local (contraseñas en base de datos local).

> [!IMPORTANT]
> El backend ya fue completamente actualizado. Los métodos de autenticación local han sido eliminados. El backend ahora espera siempre un `Bearer Token` emitido por el SSO, el cual será validado asimétricamente usando una llave pública (`jwt-public.key`).

---

## 1. Configuración de Variables de Entorno
Asegurar que el entorno incluya las rutas exactas tanto de nuestra API local como del portal de SSO central:
```env
# .env.local
NEXT_PUBLIC_API_URL=http://veterinaria.test/api
NEXT_PUBLIC_SSO_URL=http://sso.test/login
```

---

## 2. Peticiones API e Interceptor (Axios)
Cualquier petición hacia el backend de Veterinaria debe estar autenticada con el JWT proveído por el SSO.
**Archivo sugerido:** `src/utils/api.ts`

- **Acción a realizar:** Configurar el `api.interceptors.request` para que adjunte automáticamente el token JWT al header `Authorization`.
- **Manejo de errores:** Configurar un `api.interceptors.response` para detectar errores `401 Unauthorized`. Si esto ocurre, limpiar el `localStorage` y forzar un redireccionamiento al inicio de sesión del SSO.

---

## 3. Fase 2: Consumo y Sincronización del Token

### A. Botón de Ingreso
El botón o formulario local debe desaparecer. El inicio de sesión es ahora una simple redirección al SSO.
- Modificar el botón "Ingresar" en `src/app/page.tsx` (o equivalente) para que redirija mediante un `<Link href={process.env.NEXT_PUBLIC_SSO_URL}>` o `window.location.href`.

### B. Recepción del Token (Callback Page)
El SSO redirigirá de regreso al frontend incluyendo el token (por lo general como `?token=...` en la URL de callback).
- **Acción a realizar:** Crear una página o componente dedicado (ej: `src/app/auth/callback/page.tsx`).
- **Flujo del Callback:**
  1. Capturar el token de la URL.
  2. Guardarlo en `localStorage.setItem('access_token', token)`.
  3. **Paso Crítico:** Realizar una petición a `GET /api/auth/me`. 
     *Nota: Al llamar a este endpoint, el backend realizará el "sync-on-the-fly" del usuario.*
  4. Guardar los datos devueltos (perfil y **roles** de Spatie) en el estado global o localStorage (`user_info`).
  5. Redirigir al usuario a la vista segura (ej. `/dashboard`).

---

## 4. Limpieza de Formularios Obsoletos (Contraseñas)

### A. Login y Registro Estándar
- **Eliminar** todas las pantallas, componentes y estados de frontend relacionados con crear cuentas locales o recuperar/digitar contraseñas.
- El endpoint `POST /api/auth/login` ya **no existe** en el backend. 

### B. Flujo de Invitaciones (Cliente / Personal)
Los endpoints `registrar-cliente` y `registrar-personal` siguen existiendo, pero han sido refactorizados:
- **Ya no esperan un `password` ni devuelven un JWT local.**
- Las pantallas de "Aceptar Invitación" en el frontend ahora **solo deben mostrar información y un botón de confirmación**, pidiendo internamente el envío del `token` de invitación al backend.
- Cuando el endpoint devuelva `201 Created` o `200 OK`, informar al usuario que su cuenta se vinculó con éxito y redirigirlo al SSO para que inicie sesión con normalidad.

---

## 5. Cierre de Sesión (Logout)
- Modificar la acción del botón "Cerrar Sesión".
- **Flujo:**
  1. Opcional pero recomendado: Hacer un `POST /api/auth/logout`. Este endpoint ahora solo devuelve un `{ "message": "Sesión cerrada" }` vacío en el backend de veterinaria.
  2. Limpiar el frontend: `localStorage.removeItem('access_token')` y `user_info`.
  3. Redirigir a la URL de *Logout Central* del SSO (para invalidar el token real), o simplemente a la página de inicio `/`.

---

## 6. Control de Acceso Local (Roles)
- Consumir el arreglo de roles provisto por `/api/auth/me`.
- Validar las vistas de manera condicional (e.g., Si `roles.includes('propietario')`, restringir menú de empleados y mostrar el módulo de *Mis Mascotas*).
- Aprovechar las respuestas `403 Forbidden` de la API como señal para mostrar pantallas amistosas de "Acceso Denegado".
