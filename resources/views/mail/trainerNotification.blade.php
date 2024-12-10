<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Aprendiz</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; }
        .header { background-color: #4caf50; color: white; padding: 10px; text-align: center; }
        .content { margin-top: 20px; }
        .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Nuevo Aprendiz Asignado!</h1>
        </div>
        <div class="content">
            <!-- El nombre del entrenador -->
            <p>Hola <strong>{{ $trainerName }}</strong>,</p>
            <!-- El nombre del aprendiz asignado -->
            <p>Se te ha asignado el aprendiz <strong>{{ $apprenticeName }}</strong>.</p>
            <p>Por favor, verifica los detalles en la plataforma.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} - SENAFOLLOWUP. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
