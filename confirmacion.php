<?php
session_start();

// Redirigir si no hay compra reciente
if (!isset($_SESSION['ultima_compra'])) {
    header('Location: buscar_viajes.php');
    exit;
}

$compra = $_SESSION['ultima_compra'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra - Agencia de Viajes para Turismo Santiago</title> <!--Marcos: Se deja comentario debido a que se cambia nombre por Agencia de Viajes para turismo Santiago -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .confirmation-container {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            color: #2ecc71;
            font-size: 50px;
            margin-bottom: 20px;
        }
        .order-details {
            text-align: left;
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            color: #009933;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-icon">✓</div>
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu reserva ha sido confirmada y los detalles han sido enviados a tu correo electrónico.</p>
        <p>Número de confirmación: #<?= substr(md5($compra['fecha']), 0, 8) ?></p>
        
        <div class="order-details">
            <h2>Detalles de tu reserva</h2>
            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($compra['fecha'])) ?></p>
 <!-- parquetes de viaje reservados -->
            <h3>Paquetes reservados:</h3>
            <?php foreach ($compra['items'] as $item): ?>
                <div class="order-item">
                    <span><?= htmlspecialchars($item['ciudad']) ?>, <?= htmlspecialchars($item['pais']) ?></span>
                    <span>$<?= number_format($item['precio'], 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>
            
            <div class="total">
                Total pagado: $<?= number_format($compra['total'], 0, ',', '.') ?>
            </div>
        </div>
        
        <a href="buscar_viajes.php" class="btn">Volver a la búsqueda</a>
    </div>
</body>
</html>
