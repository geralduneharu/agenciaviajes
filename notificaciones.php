<?php
header('Content-Type: application/json');

// ejemplo de notificaciones que podrían venir de una base de datos
$notificaciones = [
    [
        'mensaje' => '¡Oferta especial de verano! 20% de descuento en destinos caribeños',
        'tipo' => 'oferta',
        'fecha' => date('Y-m-d'),
        'prioridad' => 'alta'
    ],
    [
        'mensaje' => 'Nuevos destinos disponibles en Asia. ¡Reserva ahora!',
        'tipo' => 'novedad',
        'fecha' => date('Y-m-d'),
        'prioridad' => 'media'
    ],
    [
        'mensaje' => 'Promoción relámpago: Vuelos a Europa con 15% de descuento solo hoy',
        'tipo' => 'promocion',
        'fecha' => date('Y-m-d'),
        'prioridad' => 'alta'
    ]
];

//filtro de notificaciones de alta prioridad para mostrar al inicio
$notificacionesIniciales = array_filter($notificaciones, function($notif) {
    return $notif['prioridad'] === 'alta';
});

echo json_encode(array_values($notificacionesIniciales));
?>