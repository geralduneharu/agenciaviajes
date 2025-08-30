<?php
// insertar_datos_ejemplo.php

try {
    $conn = new PDO("pgsql:host=localhost;dbname=Agencia3", "postgres", "tu_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Insertar 3 vuelos de ejemplo
    $vuelos = [
        [
            'origen' => 'Santiago',
            'destino' => 'Buenos Aires',
            'fecha' => '2025-09-15 08:30:00',
            'plazas' => 120,
            'precio' => 250000,
            'aerolinea' => 'LATAM',
            'codigo' => 'LA1234',
            'duracion' => 120
        ],
        [
            'origen' => 'Santiago',
            'destino' => 'Lima',
            'fecha' => '2025-10-20 14:15:00',
            'plazas' => 150,
            'precio' => 180000,
            'aerolinea' => 'Sky Airline',
            'codigo' => 'SK5678',
            'duracion' => 90
        ],
        [
            'origen' => 'Santiago',
            'destino' => 'Río de Janeiro',
            'fecha' => '2025-11-05 22:45:00',
            'plazas' => 80,
            'precio' => 350000,
            'aerolinea' => 'Gol',
            'codigo' => 'GL9012',
            'duracion' => 240
        ]
    ];
    
    foreach ($vuelos as $vuelo) {
        $query = "INSERT INTO VUELO (origen, destino, fecha, plazas_disponibles, precio, aerolinea, codigo_vuelo, duracion_minutos)
                  VALUES (:origen, :destino, :fecha, :plazas, :precio, :aerolinea, :codigo, :duracion)";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($vuelo);
    }
    
    // Insertar 3 hoteles de ejemplo
    $hoteles = [
        [
            'nombre' => 'Hotel Plaza',
            'ubicacion' => 'Buenos Aires, Argentina',
            'habitaciones' => 50,
            'tarifa' => 80000
        ],
        [
            'nombre' => 'Miraflores Grand',
            'ubicacion' => 'Lima, Perú',
            'habitaciones' => 75,
            'tarifa' => 65000
        ],
        [
            'nombre' => 'Copacabana Palace',
            'ubicacion' => 'Río de Janeiro, Brasil',
            'habitaciones' => 120,
            'tarifa' => 120000
        ]
    ];
    
    foreach ($hoteles as $hotel) {
        $query = "INSERT INTO HOTEL (nombre, ubicacion, habitaciones_disponibles, tarifa_noche)
                  VALUES (:nombre, :ubicacion, :habitaciones, :tarifa)";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($hotel);
    }
    
    echo "Datos de ejemplo insertados correctamente.";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>