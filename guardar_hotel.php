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
    'nombre_hotel' => trim($_POST['nombre_hotel'] ?? ''),
    'ubicacion' => trim($_POST['ubicacion'] ?? ''),
    'habitaciones' => filter_input(INPUT_POST, 'habitaciones', FILTER_VALIDATE_INT),
    'tarifa' => filter_input(INPUT_POST, 'tarifa', FILTER_VALIDATE_FLOAT),
    'descripcion' => trim($_POST['descripcion_hotel'] ?? '')
];

// Validaciones básicas
if (empty($datos['nombre_hotel'])) $errores[] = "El nombre del hotel es requerido";
if (empty($datos['ubicacion'])) $errores[] = "La ubicación es requerida";
if ($datos['habitaciones'] === false || $datos['habitaciones'] < 1) $errores[] = "Número de habitaciones inválido";
if ($datos['tarifa'] === false || $datos['tarifa'] <= 0) $errores[] = "Tarifa inválida";

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    session_start();
    $_SESSION['errores_hotel'] = $errores;
    $_SESSION['datos_hotel'] = $datos;
    header('Location: admin.php');
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Preparar consulta SQL
    $sql = "INSERT INTO HOTEL (nombre, ubicacion, habitaciones_disponibles, tarifa_noche) 
            VALUES (:nombre, :ubicacion, :habitaciones, :tarifa)";
    
    // Ejecutar consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $datos['nombre_hotel']);
    $stmt->bindParam(':ubicacion', $datos['ubicacion']);
    $stmt->bindParam(':habitaciones', $datos['habitaciones'], PDO::PARAM_INT);
    $stmt->bindParam(':tarifa', $datos['tarifa']);
    
    $stmt->execute();
    
    // Redirigir con mensaje de éxito
    session_start();
    $_SESSION['exito_hotel'] = "Hotel registrado exitosamente";
    header('Location: admin.php');
    
} catch (PDOException $e) {
    // Registrar error y redirigir
    error_log("Error al guardar hotel: " . $e->getMessage());
    session_start();
    $_SESSION['errores_hotel'] = ["Ocurrió un error al registrar el hotel. Por favor intente nuevamente."];
    header('Location: admin.php');
}
?>