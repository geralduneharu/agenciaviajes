<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->connect();

    // 1. Insertar clientes de prueba si no existen
    $clientes = [
        ['nombre' => 'Juan', 'apellido' => 'Pérez', 'email' => 'juan@example.com', 'password_hash' => password_hash('123456', PASSWORD_DEFAULT)],
        ['nombre' => 'María', 'apellido' => 'Gómez', 'email' => 'maria@example.com', 'password_hash' => password_hash('123456', PASSWORD_DEFAULT)],
        ['nombre' => 'Carlos', 'apellido' => 'López', 'email' => 'carlos@example.com', 'password_hash' => password_hash('123456', PASSWORD_DEFAULT)],
        ['nombre' => 'Ana', 'apellido' => 'Martínez', 'email' => 'ana@example.com', 'password_hash' => password_hash('123456', PASSWORD_DEFAULT)],
        ['nombre' => 'Pedro', 'apellido' => 'Rodríguez', 'email' => 'pedro@example.com', 'password_hash' => password_hash('123456', PASSWORD_DEFAULT)]
    ];

    foreach ($clientes as $cliente) {
        $stmt = $conn->prepare("INSERT INTO CLIENTE (nombre, apellido, email, password_hash) 
                               VALUES (:nombre, :apellido, :email, :password_hash)
                               ON CONFLICT (email) DO NOTHING");
        $stmt->execute($cliente);
    }

    // 2. Insertar vuelos de prueba si no existen
    $vuelos = [
        ['origen' => 'Santiago', 'destino' => 'Buenos Aires', 'fecha' => '2023-12-15 08:00:00', 'plazas_disponibles' => 150, 'precio' => 200000, 'aerolinea' => 'LATAM', 'codigo_vuelo' => 'LA123', 'duracion_minutos' => 120],
        ['origen' => 'Lima', 'destino' => 'Santiago', 'fecha' => '2023-12-16 10:30:00', 'plazas_disponibles' => 180, 'precio' => 180000, 'aerolinea' => 'Sky', 'codigo_vuelo' => 'SK456', 'duracion_minutos' => 90],
        ['origen' => 'Bogotá', 'destino' => 'Lima', 'fecha' => '2023-12-17 14:15:00', 'plazas_disponibles' => 200, 'precio' => 220000, 'aerolinea' => 'Avianca', 'codigo_vuelo' => 'AV789', 'duracion_minutos' => 110],
        ['origen' => 'São Paulo', 'destino' => 'Bogotá', 'fecha' => '2023-12-18 16:45:00', 'plazas_disponibles' => 170, 'precio' => 240000, 'aerolinea' => 'GOL', 'codigo_vuelo' => 'GL012', 'duracion_minutos' => 130],
        ['origen' => 'Buenos Aires', 'destino' => 'São Paulo', 'fecha' => '2023-12-19 20:00:00', 'plazas_disponibles' => 160, 'precio' => 210000, 'aerolinea' => 'Azul', 'codigo_vuelo' => 'AZ345', 'duracion_minutos' => 140]
    ];

    foreach ($vuelos as $vuelo) {
        $stmt = $conn->prepare("INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio, aerolinea, codigo_vuelo, duracion_minutos) 
                               VALUES (:origen, :destino, :fecha, :plazas_disponibles, :precio, :aerolinea, :codigo_vuelo, :duracion_minutos)
                               ON CONFLICT (codigo_vuelo) DO NOTHING");
        $stmt->execute($vuelo);
    }

    // 3. Insertar hoteles de prueba si no existen
    $hoteles = [
        ['nombre' => 'Hotel Plaza', 'ubicacion' => 'Santiago, Chile', 'habitaciones_disponibles' => 50, 'tarifa_noche' => 80000],
        ['nombre' => 'Gran Hotel', 'ubicacion' => 'Buenos Aires, Argentina', 'habitaciones_disponibles' => 70, 'tarifa_noche' => 95000],
        ['nombre' => 'Hotel Central', 'ubicacion' => 'Lima, Perú', 'habitaciones_disponibles' => 60, 'tarifa_noche' => 75000]
    ];

    foreach ($hoteles as $hotel) {
        $stmt = $conn->prepare("INSERT INTO HOTEL (nombre, ubicacion, habitaciones_disponibles, tarifa_noche) 
                               VALUES (:nombre, :ubicacion, :habitaciones_disponibles, :tarifa_noche)
                               ON CONFLICT (nombre, ubicacion) DO NOTHING");
        $stmt->execute($hotel);
    }

    // 4. Ahora insertar las reservas
    $reservas = [
        ['id_cliente' => 1, 'id_vuelo' => 1, 'id_hotel' => 1, 'total' => 1200000, 'metodo_pago' => 'tarjeta'],
        ['id_cliente' => 2, 'id_vuelo' => 2, 'id_hotel' => 2, 'total' => 950000, 'metodo_pago' => 'transferencia'],
        ['id_cliente' => 3, 'id_vuelo' => 3, 'id_hotel' => 3, 'total' => 1500000, 'metodo_pago' => 'tarjeta'],
        ['id_cliente' => 1, 'id_vuelo' => 4, 'id_hotel' => 1, 'total' => 1800000, 'metodo_pago' => 'efectivo'],
        ['id_cliente' => 4, 'id_vuelo' => 5, 'id_hotel' => 2, 'total' => 850000, 'metodo_pago' => 'tarjeta'],
        ['id_cliente' => 5, 'id_vuelo' => 1, 'id_hotel' => 3, 'total' => 1100000, 'metodo_pago' => 'transferencia'],
        ['id_cliente' => 2, 'id_vuelo' => 2, 'id_hotel' => 1, 'total' => 1300000, 'metodo_pago' => 'tarjeta'],
        ['id_cliente' => 3, 'id_vuelo' => 3, 'id_hotel' => 2, 'total' => 950000, 'metodo_pago' => 'efectivo'],
        ['id_cliente' => 4, 'id_vuelo' => 4, 'id_hotel' => 3, 'total' => 1600000, 'metodo_pago' => 'tarjeta'],
        ['id_cliente' => 5, 'id_vuelo' => 5, 'id_hotel' => 1, 'total' => 1250000, 'metodo_pago' => 'transferencia']
    ];

    foreach ($reservas as $reserva) {
        // Verificar que existan los IDs
        $stmt = $conn->prepare("SELECT 1 FROM CLIENTE WHERE id_cliente = ?");
        $stmt->execute([$reserva['id_cliente']]);
        if (!$stmt->fetch()) continue;
        
        $stmt = $conn->prepare("SELECT 1 FROM VUELO WHERE id_vuelo = ?");
        $stmt->execute([$reserva['id_vuelo']]);
        if (!$stmt->fetch()) continue;
        
        $stmt = $conn->prepare("SELECT 1 FROM HOTEL WHERE id_hotel = ?");
        $stmt->execute([$reserva['id_hotel']]);
        if (!$stmt->fetch()) continue;

        // Insertar reserva
        $sql = "INSERT INTO RESERVA (id_cliente, fecha_reserva, estado, total, metodo_pago) 
                VALUES (:id_cliente, NOW(), 'confirmada', :total, :metodo_pago)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_cliente' => $reserva['id_cliente'],
            ':total' => $reserva['total'],
            ':metodo_pago' => $reserva['metodo_pago']
        ]);
        
        $id_reserva = $conn->lastInsertId();
        
        // Insertar detalles
        $sql_vuelo = "INSERT INTO DETALLE_RESERVA_VUELO (id_reserva, id_vuelo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_vuelo);
        $stmt->execute([$id_reserva, $reserva['id_vuelo']]);
        
        $sql_hotel = "INSERT INTO DETALLE_RESERVA_HOTEL (id_reserva, id_hotel) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_hotel);
        $stmt->execute([$id_reserva, $reserva['id_hotel']]);
    }

    echo "Datos de prueba insertados correctamente: 5 clientes, 5 vuelos, 3 hoteles y 10 reservas";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>