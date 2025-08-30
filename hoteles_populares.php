<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Consulta avanzada: Hoteles con más de 2 reservas
    $sql = "SELECT h.id_hotel, h.nombre, h.ubicacion, 
                   COUNT(r.id_reserva) as total_reservas,
                   SUM(r.total) as ingresos_totales
            FROM HOTEL h
            JOIN DETALLE_RESERVA_HOTEL drh ON h.id_hotel = drh.id_hotel
            JOIN RESERVA r ON drh.id_reserva = r.id_reserva
            WHERE r.estado = 'confirmada'
            GROUP BY h.id_hotel, h.nombre, h.ubicacion
            HAVING COUNT(r.id_reserva) > 2
            ORDER BY total_reservas DESC, ingresos_totales DESC";
    
    $stmt = $conn->query($sql);
    $hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Hoteles Más Populares (con más de 2 reservas)</h2>";
    
    if (empty($hoteles)) {
        echo "<p>No hay hoteles con más de 2 reservas confirmadas</p>";
    } else {
        echo "<table border='1'>";
        echo "<thead><tr>
                <th>Hotel</th>
                <th>Ubicación</th>
                <th>Total Reservas</th>
                <th>Ingresos Totales</th>
              </tr></thead>";
        echo "<tbody>";
        
        foreach ($hoteles as $hotel) {
            echo "<tr>";
            echo "<td>".htmlspecialchars($hotel['nombre'])."</td>";
            echo "<td>".htmlspecialchars($hotel['ubicacion'])."</td>";
            echo "<td>".$hotel['total_reservas']."</td>";
            echo "<td>$".number_format($hotel['ingresos_totales'], 0, ',', '.')."</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    }

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>