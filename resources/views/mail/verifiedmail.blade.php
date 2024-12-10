<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido(a)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
      .header {
    background-color: #87c8a4; 
    color: #ffffff;
    text-align: center;
    padding: 20px 15px;
}

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            margin: 10px 0;
        }
        .content strong {
            color: #007bff;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }
        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 15px 10px;
            font-size: 14px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        .logo {
            max-width: 100px;
            margin: 0 auto 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA" class="logo">
            <h1>¡Bienvenido(a) a FOLLOWUP!</h1>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $user->name }} {{ $user->last_name }}</strong>,</p>
            <p>Nos complace informarte que has sido registrado exitosamente en nuestra plataforma.</p>
            <p>Tu correo registrado es: <strong>{{ $user->email }}</strong></p>
            <p>Tu contraseña inicial es: <strong>sena@2024</strong></p>
            <p>Por favor, cambia tu contraseña al iniciar sesión por primera vez para garantizar la seguridad de tu cuenta.</p>
           
        </div>
        <div class="footer">
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p>&copy; {{ date('Y') }} - SENAFOLLOWUP. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
