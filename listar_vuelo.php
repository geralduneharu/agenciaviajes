<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $sql = "SELECT * FROM VUELO ORDER BY fecha DESC";
    $stmt = $conn->query($sql);
    $vuelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($vuelos)) {
        echo "<p>No hay vuelos registrados</p>";
    } else {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Código</th>";
        echo "<th>Origen</th>";
        echo "<th>Destino</th>";
        echo "<th>Fecha</th>";
        echo "<th>Plazas</th>";
        echo "<th>Precio</th>";
        echo "<th>Aerolínea</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($vuelos as $vuelo) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($vuelo['codigo_vuelo']) . "</td>";
            echo "<td>" . htmlspecialchars($vuelo['origen']) . "</td>";
            echo "<td>" . htmlspecialchars($vuelo['destino']) . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($vuelo['fecha'])) . "</td>";
            echo "<td>" . $vuelo['plazas_disponibles'] . "</td>";
            echo "<td>$" . number_format($vuelo['precio'], 0, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($vuelo['aerolinea']) . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p>Error al cargar los vuelos: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>