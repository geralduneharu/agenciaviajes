<?php
require_once 'database.php';

// Validar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

// Validar y sanitizar los datos del formulario
$errores = [];
$datos = [
    'origen' => trim($_POST['origen'] ?? ''),
    'destino' => trim($_POST['destino'] ?? ''),
    'fecha' => trim($_POST['fecha'] ?? ''),
    'plazas' => filter_input(INPUT_POST, 'plazas', FILTER_VALIDATE_INT),
    'precio' => filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT),
    'aerolinea' => trim($_POST['aerolinea'] ?? ''),
    'codigo' => trim($_POST['codigo'] ?? ''),
    'duracion' => filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT)
];

// Validaciones básicas
if (empty($datos['origen'])) $errores[] = "El origen es requerido";
if (empty($datos['destino'])) $errores[] = "El destino es requerido";
if (empty($datos['fecha'])) $errores[] = "La fecha es requerida";
if ($datos['plazas'] === false || $datos['plazas'] < 1) $errores[] = "Plazas disponibles inválidas";
if ($datos['precio'] === false || $datos['precio'] <= 0) $errores[] = "Precio inválido";
if (empty($datos['aerolinea'])) $errores[] = "La aerolínea es requerida";
if (empty($datos['codigo'])) $errores[] = "El código de vuelo es requerido";
if ($datos['duracion'] === false || $datos['duracion'] < 1) $errores[] = "Duración inválida";

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    session_start();
    $_SESSION['errores_vuelo'] = $errores;
    $_SESSION['datos_vuelo'] = $datos;
    header('Location: admin.php');
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Preparar consulta SQL
    $sql = "INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio, aerolinea, codigo_vuelo, duracion_minutos) 
            VALUES (:origen, :destino, :fecha, :plazas, :precio, :aerolinea, :codigo, :duracion)";
    
    // Ejecutar consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':origen', $datos['origen']);
    $stmt->bindParam(':destino', $datos['destino']);
    $stmt->bindParam(':fecha', $datos['fecha']);
    $stmt->bindParam(':plazas', $datos['plazas'], PDO::PARAM_INT);
    $stmt->bindParam(':precio', $datos['precio']);
    $stmt->bindParam(':aerolinea', $datos['aerolinea']);
    $stmt->bindParam(':codigo', $datos['codigo']);
    $stmt->bindParam(':duracion', $datos['duracion'], PDO::PARAM_INT);
    
    $stmt->execute();
    
    // Redirigir con mensaje de éxito
    session_start();
    $_SESSION['exito_vuelo'] = "Vuelo registrado exitosamente";
    header('Location: admin.php');
    
} catch (PDOException $e) {
    // Registrar error y redirigir
    error_log("Error al guardar vuelo: " . $e->getMessage());
    session_start();
    $_SESSION['errores_vuelo'] = ["Ocurrió un error al registrar el vuelo. Por favor intente nuevamente."];
    header('Location: admin.php');
}
?>