<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido al equipo</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8fafc; padding: 20px; border-radius: 5px; text-align: center;">
        <h2 style="color: #2563eb;">¡Bienvenido al Equipo, {{ $personal->nombre }}!</h2>
    </div>

    <div style="padding: 20px 0;">
        <p>Hola <strong>{{ $personal->nombre }} {{ $personal->paterno }}</strong>,</p>
        
        <p>Has sido registrado en nuestro sistema interno. Para comenzar a trabajar y acceder a tu perfil como <em>{{ ucfirst($personal->rol_sistema) }}</em>, por favor configura tu contraseña.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $registrationUrl }}" 
               style="background-color: #2563eb; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
               Configurar mi Acceso
            </a>
        </div>

        <p style="font-size: 14px; color: #666; background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 10px;">
            ⚠️ <strong>Importante:</strong> Este enlace de invitación expirará en 72 horas por motivos de seguridad.
        </p>

        <p style="font-size: 14px; color: #666;">
            Si tienes problemas con el botón, puedes copiar y pegar el siguiente enlace en tu navegador:<br>
            <a href="{{ $registrationUrl }}" style="color: #2563eb; word-break: break-all;">{{ $registrationUrl }}</a>
        </p>
    </div>

    <div style="text-align: center; font-size: 12px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
        Este es un correo automático, por favor no respondas a esta dirección.
    </div>
</body>
</html>
