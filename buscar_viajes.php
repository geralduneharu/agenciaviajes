<?php
// Se incluye la clase FiltroInteractivo
require_once 'FiltroInteractivo.php';

// Datos de ejemplo (simulando una base de datos)
$viajes = [
    [
        'id' => 1,
        'ciudad' => 'París',
        'pais' => 'Francia',
        'hotel' => 'Hotel Eiffel',
        'fecha' => '2025-09-15',
        'precio' => 1800000,
        'descripcion' => 'Paquete premium con vuelo directo y hotel 5 estrellas',
        'disponible' => true
    ],
    [
        'id' => 2,
        'ciudad' => 'Roma',
        'pais' => 'Italia',
        'hotel' => 'Hotel Coliseo',
        'fecha' => '2025-08-20',
        'precio' => 1500000,
        'descripcion' => 'Experiencia cultural con tours guiados',
        'disponible' => true
    ],
    [
        'id' => 3,
        'ciudad' => 'Barcelona',
        'pais' => 'España',
        'hotel' => 'Hotel Gaudí',
        'fecha' => '2025-07-10',
        'precio' => 950000,
        'descripcion' => 'Paquete medio con alojamiento céntrico',
        'disponible' => true
    ],
    [
        'id' => 4,
        'ciudad' => 'París',
        'pais' => 'Francia',
        'hotel' => 'Hotel Louvre',
        'fecha' => '2025-09-20',
        'precio' => 3500000,
        'descripcion' => 'Experiencia premium con suite con vista al Louvre',
        'disponible' => true
    ]
];

// Recuperación de los datos del formulario
$datosFormulario = $_POST;

// Creación de instancia del filtro con los datos recibidos
$filtro = new FiltroInteractivo($datosFormulario);

// Aplicación del filtro
$resultados = $filtro->filtrar($viajes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Marcos fecrnández: Se deja comentario para que este trozo de código se cambie el nombre para el HTML de resultado de busqueda -->
    <title>Busquedas encontradas</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .result-container { margin-top: 30px; }
        .travel-card {
            border: 1px solid #ddd; border-radius: 8px; padding: 15px; 
            margin-bottom: 15px; background: #f9f9f9;
        }
        .travel-card h3 { color: #0066cc; margin-top: 0; }
        .price { font-weight: bold; color: #009933; font-size: 18px; }
        .no-results { color: #cc0000; font-size: 18px; text-align: center; }
        .search-summary { background: #e6f2ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Resultados de tu búsqueda</h1>
    
    <div class="search-summary">
        <h3><?= htmlspecialchars($filtro->getMensajeFiltros()) ?></h3>
    </div>
    
    <div class="result-container">
        <?php if (empty($resultados)): ?>
            <p class="no-results">No se encontraron viajes con los criterios seleccionados</p>
        <?php else: ?>
            <?php foreach ($resultados as $viaje): ?>
                <!-- Dentro del foreach que muestra los resultados, modificar la tarjeta de viaje: -->
<div class="travel-card">
    <h3><?= htmlspecialchars($viaje['ciudad']) ?>, <?= htmlspecialchars($viaje['pais']) ?></h3>
    <p><strong>Hotel:</strong> <?= htmlspecialchars($viaje['hotel']) ?></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($viaje['fecha'])) ?></p>
    <p><?= htmlspecialchars($viaje['descripcion']) ?></p>
    <p class="price">$<?= number_format($viaje['precio'], 0, ',', '.') ?></p>
    
    <form method="post" action="carrito.php">
        <input type="hidden" name="accion" value="agregar">
        <input type="hidden" name="id_paquete" value="<?= $viaje['id'] ?>">
        <input type="hidden" name="ciudad" value="<?= htmlspecialchars($viaje['ciudad']) ?>">
        <input type="hidden" name="pais" value="<?= htmlspecialchars($viaje['pais']) ?>">
        <input type="hidden" name="hotel" value="<?= htmlspecialchars($viaje['hotel']) ?>">
        <input type="hidden" name="fecha" value="<?= htmlspecialchars($viaje['fecha']) ?>">
        <input type="hidden" name="precio" value="<?= htmlspecialchars($viaje['precio']) ?>">
        <input type="hidden" name="descripcion" value="<?= htmlspecialchars($viaje['descripcion']) ?>">
        <button type="submit">Agregar al carrito</button>
    </form>
</div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <script>
        function reservar(id) {
            alert('Reserva solicitada para el viaje ID: ' + id);
        }
    </script>
</body>
</html>
