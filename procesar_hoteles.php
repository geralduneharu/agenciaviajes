<?php
// Validar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: hoteles.php');
    exit;
}

// Conexión a la base de datos
try {
    $conn = new PDO("pgsql:host=localhost;dbname=Agencia3", "postgres", "tu_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Validar y limpiar los datos del formulario
$nombre = trim($_POST['nombre']);
$ubicacion = trim($_POST['ubicacion']);
$habitaciones = intval($_POST['habitaciones']);
$tarifa = floatval($_POST['tarifa']);

// Validaciones adicionales en el servidor
$errores = [];

if (empty($nombre)) {
    $errores[] = "El nombre del hotel es obligatorio";
}

if (empty($ubicacion)) {
    $errores[] = "La ubicación es obligatoria";
} elseif (!strpos($ubicacion, ',')) {
    $errores[] = "La ubicación debe incluir ciudad y país (ej: Santiago, Chile)";
}

if ($habitaciones < 1) {
    $errores[] = "Debe haber al menos 1 habitación disponible";
}

if ($tarifa <= 0) {
    $errores[] = "La tarifa debe ser mayor a 0";
}

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    session_start();
    $_SESSION['errores_hotel'] = $errores;
    $_SESSION['datos_hotel'] = $_POST;
    header('Location: gestion_hoteles.php');
    exit;
}

// Insertar el hotel en la base de datos
try {
    $query = "INSERT INTO HOTEL (nombre, ubicacion, habitaciones_disponibles, tarifa_noche)
              VALUES (:nombre, :ubicacion, :habitaciones, :tarifa)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':habitaciones', $habitaciones, PDO::PARAM_INT);
    $stmt->bindParam(':tarifa', $tarifa);
    
    $stmt->execute();
    
    // Redirigir con mensaje de éxito
    session_start();
    $_SESSION['mensaje_exito'] = "Hotel registrado exitosamente";
    header('Location: gestion_hoteles.php');
    exit;
    
} catch (PDOException $e) {
    // Manejar error de base de datos
    session_start();
    $_SESSION['errores_hotel'] = ["Error al registrar el hotel: " . $e->getMessage()];
    $_SESSION['datos_hotel'] = $_POST;
    header('Location: gestion_hoteles.php');
    exit;
}
?>