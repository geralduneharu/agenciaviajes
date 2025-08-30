<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $sql = "SELECT r.id_reserva, c.nombre as cliente, 
                   v.origen, v.destino, v.fecha as fecha_vuelo,
                   h.nombre as hotel, r.total, r.estado
            FROM RESERVA r
            JOIN CLIENTE c ON r.id_cliente = c.id_cliente
            JOIN DETALLE_RESERVA_VUELO drv ON r.id_reserva = drv.id_reserva
            JOIN VUELO v ON drv.id_vuelo = v.id_vuelo
            JOIN DETALLE_RESERVA_HOTEL drh ON r.id_reserva = drh.id_reserva
            JOIN HOTEL h ON drh.id_hotel = h.id_hotel
            ORDER BY r.fecha_reserva DESC";
    
    $stmt = $conn->query($sql);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Listado de Reservas</h2>";
    echo "<table border='1'>";
    echo "<thead><tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Fecha Vuelo</th>
            <th>Hotel</th>
            <th>Total</th>
            <th>Estado</th>
          </tr></thead>";
    echo "<tbody>";
    
    foreach ($reservas as $reserva) {
        echo "<tr>";
        echo "<td>".$reserva['id_reserva']."</td>";
        echo "<td>".htmlspecialchars($reserva['cliente'])."</td>";
        echo "<td>".htmlspecialchars($reserva['origen'])."</td>";
        echo "<td>".htmlspecialchars($reserva['destino'])."</td>";
        echo "<td>".date('d/m/Y H:i', strtotime($reserva['fecha_vuelo']))."</td>";
        echo "<td>".htmlspecialchars($reserva['hotel'])."</td>";
        echo "<td>$".number_format($reserva['total'], 0, ',', '.')."</td>";
        echo "<td>".$reserva['estado']."</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";

} catch (PDOException $e) {
    echo "Error al listar reservas: " . $e->getMessage();
}
?>