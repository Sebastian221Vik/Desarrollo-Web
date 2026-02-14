<?php
// php/conexion.php
// Archivo que devuelve una conexión PDO lista para usar.
// Recomendación: este archivo se incluye donde se necesite la conexión.

$DB_HOST = 'localhost';
$DB_NAME = 'Eq8Plagas';
$DB_USER = 'root';
$DB_PASS = 'sebas1298_';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // lanzar excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // fetch por defecto en modo asociativo
    PDO::ATTR_EMULATE_PREPARES => false,               // usar prepares nativos cuando sea posible
];

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
    // si necesitas, puedes devolver $pdo o usar 'require' para acceder a la variable $pdo
} catch (PDOException $e) {
    // En producción no muestres errores completos. Aquí mostramos para desarrollo.
    http_response_code(500);
    echo "Error de conexión a la base de datos: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
