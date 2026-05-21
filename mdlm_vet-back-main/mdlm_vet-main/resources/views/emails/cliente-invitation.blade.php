<x-mail::message>
# ¡Hola, {{ $propietario->nombre }}!

Has sido registrado como cliente en nuestro sistema de veterinaria.

Para acceder a tu cuenta y ver la información de tus mascotas, citas y recetas, necesitas crear tus credenciales de acceso.

<x-mail::button :url="$registrationUrl" color="primary">
Registrar mi cuenta
</x-mail::button>

Este enlace expira en **72 horas**. Si el enlace expira, solicita uno nuevo a tu veterinario.

---

**Datos registrados:**
- **Nombre:** {{ $propietario->nombre }} {{ $propietario->paterno }} {{ $propietario->materno }}
- **Documento:** {{ $propietario->nro_doc }}
- **Email:** {{ $propietario->email }}

Si no solicitaste esta cuenta, puedes ignorar este correo.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
