<?php
// php/procesar_cita.php
require_once __DIR__ . '/conexion.php'; // carga $pdo

// Helper: sanea y recorta texto
function limpiar($s) {
    return trim($s);
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit;
}

// Recibir y validar (validación básica)
$nombre = isset($_POST['nombre']) ? limpiar($_POST['nombre']) : '';
$telefono = isset($_POST['telefono']) ? limpiar($_POST['telefono']) : '';
$email = isset($_POST['email']) ? limpiar($_POST['email']) : '';
$fecha = isset($_POST['fecha']) ? limpiar($_POST['fecha']) : '';
$hora = isset($_POST['hora']) ? limpiar($_POST['hora']) : '';
$tipo_servicio = isset($_POST['tipo_servicio']) ? limpiar($_POST['tipo_servicio']) : '';
$urgencia = isset($_POST['urgencia']) ? limpiar($_POST['urgencia']) : '';
$mascotas = isset($_POST['mascotas']) ? $_POST['mascotas'] : []; // array
$comentarios = isset($_POST['comentarios']) ? limpiar($_POST['comentarios']) : '';

// Validaciones sencillas
$errores = [];
if ($nombre === '') $errores[] = "El nombre es obligatorio.";
if ($telefono === '') $errores[] = "El teléfono es obligatorio.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo electrónico inválido.";
if ($fecha === '') $errores[] = "La fecha es obligatoria.";
if ($hora === '') $errores[] = "La hora es obligatoria.";
if ($tipo_servicio === '') $errores[] = "Seleccione un tipo de servicio.";
if ($urgencia === '') $errores[] = "Seleccione la urgencia.";

if (!empty($errores)) {
    echo "<h2>Errores en la solicitud:</h2><ul>";
    foreach ($errores as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul><p><a href='../contacto.html'>Volver al formulario</a></p>";
    exit;
}

// Preparar datos para insertar
$mascotas_texto = '';
if (is_array($mascotas)) {
    // Sanear cada valor
    $mascotas_limpias = array_map('trim', $mascotas);
    $mascotas_texto = implode(',', $mascotas_limpias);
} else {
    $mascotas_texto = trim((string)$mascotas);
}

try {
    $sql = "INSERT INTO citas (nombre, telefono, email, fecha, hora, tipo_servicio, urgencia, mascotas, comentarios)
            VALUES (:nombre, :telefono, :email, :fecha, :hora, :tipo_servicio, :urgencia, :mascotas, :comentarios)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':telefono' => $telefono,
        ':email' => $email,
        ':fecha' => $fecha,
        ':hora' => $hora,
        ':tipo_servicio' => $tipo_servicio,
        ':urgencia' => $urgencia,
        ':mascotas' => $mascotas_texto,
        ':comentarios' => $comentarios
    ]);

    $id = $pdo->lastInsertId();

    // Mostrar confirmación (reporte simple)
    ?>
    <!doctype html>
    <html lang="es">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Solicitud registrada - ControlMax</title>
      <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
      <main class="seccion">
        <h1>Solicitud registrada</h1>
        <p>Gracias, su solicitud ha sido guardada correctamente.</p>
        <h2>Detalles de la cita</h2>
        <table>
          <tr><th>ID</th><td><?= htmlspecialchars($id) ?></td></tr>
          <tr><th>Nombre</th><td><?= htmlspecialchars($nombre) ?></td></tr>
          <tr><th>Teléfono</th><td><?= htmlspecialchars($telefono) ?></td></tr>
          <tr><th>Email</th><td><?= htmlspecialchars($email) ?></td></tr>
          <tr><th>Servicio</th><td><?= htmlspecialchars($tipo_servicio) ?></td></tr>
          <tr><th>Fecha</th><td><?= htmlspecialchars($fecha) ?></td></tr>
          <tr><th>Hora</th><td><?= htmlspecialchars($hora) ?></td></tr>
          <tr><th>Urgencia</th><td><?= htmlspecialchars($urgencia) ?></td></tr>
          <tr><th>Mascotas</th><td><?= htmlspecialchars($mascotas_texto) ?></td></tr>
          <tr><th>Comentarios</th><td><?= nl2br(htmlspecialchars($comentarios)) ?></td></tr>
        </table>
        <p><a href="../reporte.html">Ver reporte</a> | <a href="../index.html">Volver al inicio</a></p>
      </main>
    </body>
    </html>
    <?php

} catch (PDOException $e) {
    // En producción loguea en archivo y muestra mensaje genérico
    echo "Error al guardar la solicitud: " . htmlspecialchars($e->getMessage());
    exit;
}
