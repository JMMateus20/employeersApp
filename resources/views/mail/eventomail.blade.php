<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Eventos próximos</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #333333;">📅 ¡Tienes eventos próximos!</h2>
    <p style="color: #555555;">Hola, te recordamos que los siguientes eventos están próximos a realizarse:</p>

    <!-- Aquí comienza la lista de eventos -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr style="background-color: #007BFF; color: white;">
          <th style="padding: 10px; text-align: left;">Título</th>
          <th style="padding: 10px; text-align: left;">Fecha</th>
          <th style="padding: 10px; text-align: left;">Hora</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($eventos as $e)
            <tr style="border-bottom: 1px solid #dddddd;">
            <td style="padding: 10px;">{{ $e->titulo }}</td>
            <td style="padding: 10px;">{{ $e->fecha }}</td>
            <td style="padding: 10px;">{{ $e->hora_inicio }} - {{ $e->hora_fin }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>

    <p style="margin-top: 30px; color: #555;">¡No olvides asistir! 📌</p>

    <div style="margin-top: 40px; font-size: 12px; color: #999999; border-top: 1px solid #dddddd; padding-top: 15px;">
      Este es un correo automático, por favor no respondas a este mensaje.
    </div>
  </div>
</body>
</html>

