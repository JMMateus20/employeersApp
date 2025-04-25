<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación a evento</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .header {
            text-align: center;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            padding: 20px;
            border-radius: 12px 12px 0 0;
            color: white;
        }
        .header h2 {
            margin: 0;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .event-details {
            background-color: #f1f5f9;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>¡Has sido asignado a un nuevo evento!</h2>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $asistente->nombre }}</strong>,</p>
            <p>Te informamos que has sido asignado al siguiente evento:</p>

            <div class="event-details">
                <p><strong>📌 Nombre:</strong> {{ $evento->titulo }}</p>
                <p><strong>📅 Fecha:</strong> {{ $evento->fecha }}</p>
                <p><strong>⏰ Hora de inicio:</strong> {{ $evento->hora_inicio }}</p>
                <p><strong>⏳ Hora de fin:</strong> {{ $evento->hora_fin }}</p>
                <p><strong>📝 Descripción:</strong> {{ $evento->descripcion }}</p>
            </div>

            <p>Por favor revisa los detalles y prepárate para asistir o participar según corresponda.</p>

        </div>

        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>

