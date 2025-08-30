<?php
// Validar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gestion_vuelos.php');
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
$origen = trim($_POST['origen']);
$destino = trim($_POST['destino']);
$fecha = $_POST['fecha'];
$plazas = intval($_POST['plazas']);
$precio = floatval($_POST['precio']);
$aerolinea = trim($_POST['aerolinea']);
$codigo = trim($_POST['codigo']);
$duracion = intval($_POST['duracion']);

// Validaciones adicionales en el servidor
$errores = [];

if (empty($origen)) {
    $errores[] = "El origen es obligatorio";
}

if (empty($destino)) {
    $errores[] = "El destino es obligatorio";
} elseif (strtolower($origen) === strtolower($destino)) {
    $errores[] = "El destino no puede ser igual al origen";
}

if (empty($fecha)) {
    $errores[] = "La fecha es obligatoria";
} else {
    $fechaVuelo = new DateTime($fecha);
    $ahora = new DateTime();
    if ($fechaVuelo <= $ahora) {
        $errores[] = "La fecha debe ser futura";
    }
}

if ($plazas < 1) {
    $errores[] = "Debe haber al menos 1 plaza disponible";
}

if ($precio <= 0) {
    $errores[] = "El precio debe ser mayor a 0";
}

if (empty($aerolinea)) {
    $errores[] = "La aerolínea es obligatoria";
}

if (empty($codigo) || !preg_match('/^[A-Z]{2,3}\d{3,4}$/', $codigo)) {
    $errores[] = "El código de vuelo no tiene un formato válido";
}

if ($duracion < 1) {
    $errores[] = "La duración debe ser al menos 1 minuto";
}

// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    session_start();
    $_SESSION['errores_vuelo'] = $errores;
    $_SESSION['datos_vuelo'] = $_POST;
    header('Location: gestion_vuelos.php');
    exit;
}

// Insertar el vuelo en la base de datos
try {
    $query = "INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio, aerolinea, codigo_vuelo, duracion_minutos)
              VALUES (:origen, :destino, :fecha, :plazas, :precio, :aerolinea, :codigo, :duracion)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':origen', $origen);
    $stmt->bindParam(':destino', $destino);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':plazas', $plazas, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':aerolinea', $aerolinea);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':duracion', $duracion, PDO::PARAM_INT);
    
    $stmt->execute();
    
    // Redirigir con mensaje de éxito
    session_start();
    $_SESSION['mensaje_exito'] = "Vuelo registrado exitosamente";
    header('Location: gestion_vuelos.php');
    exit;
    
} catch (PDOException $e) {
    // Manejar error de base de datos
    session_start();
    $_SESSION['errores_vuelo'] = ["Error al registrar el vuelo: " . $e->getMessage()];
    $_SESSION['datos_vuelo'] = $_POST;
    header('Location: gestion_vuelos.php');
    exit;
}
?>