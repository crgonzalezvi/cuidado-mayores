<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Emergencia</title>
</head>
<body>
    <h2 style="color: red;">🚨 Alerta de Emergencia</h2>
    <p>Hola <strong>{{ $contact->name }}</strong>,</p>

    <p>Este mensaje es para informarte que tu familiar o contacto de emergencia 
    <strong>{{ $user->name }}</strong> ha presionado el botón de <span style="color: red;">EMERGENCIA</span> en su aplicación.</p>

    <p><strong>Detalles:</strong></p>
    <ul>
        <li>Nombre del usuario: {{ $user->name }}</li>
        <li>Correo del usuario: {{ $user->email }}</li>
        <li>Fecha y hora: {{ now()->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Por favor, intenta contactarlo lo antes posible.</p>

    <br>
    <p style="color: gray;">Este es un mensaje automático, no respondas a este correo.</p>
</body>
</html>
