<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $sql = "SELECT * FROM HOTEL ORDER BY nombre";
    $stmt = $conn->query($sql);
    $hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($hoteles)) {
        echo "<p>No hay hoteles registrados</p>";
    } else {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Nombre</th>";
        echo "<th>Ubicación</th>";
        echo "<th>Habitaciones</th>";
        echo "<th>Tarifa/Noche</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($hoteles as $hotel) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($hotel['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($hotel['ubicacion']) . "</td>";
            echo "<td>" . $hotel['habitaciones_disponibles'] . "</td>";
            echo "<td>$" . number_format($hotel['tarifa_noche'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p>Error al cargar los hoteles: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>