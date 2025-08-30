<?php
// test_connection.php - Prueba básica de conexión a PostgreSQL

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la clase Database
require_once 'database.php';

// try {
    echo "<h1>Prueba Básica de Conexión</h1>";
    
    // 1. Crear instancia de Database
    $database = new Database();
    echo "<p>[Paso 1] Instancia de Database creada</p>";
    
    // 2. Intentar conectar
    $conn = $database->connect();
    echo "<p>[Paso 2] Intento de conexión realizado</p>";

    var_dump($conn);
    
    // 3. Verificar conexión
    if ($conn !== null) {
        echo "<div style='background: #dfd; padding: 10px;'>";
        echo "<h2>¡Conexión exitosa!</h2>";
        
        // 4. Obtener información básica
        $stmt = $conn->query("SELECT version() AS pg_version, current_database() AS db_name");
        $info = $stmt->fetch();
        
        echo "<p><strong>Versión PostgreSQL:</strong> ".htmlspecialchars($info['pg_version'])."</p>";
        echo "<p><strong>Base de datos:</strong> ".htmlspecialchars($info['db_name'])."</p>";
        echo "</div>";
    } else {
        throw new Exception("La conexión se estableció pero devolvió null");
    }
?>