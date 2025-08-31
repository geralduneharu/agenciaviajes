<?php
session_start();

// Redirigir si el carrito está vacío
if (empty($_SESSION['carrito'])) {
    header('Location: carrito.php');
    exit;
}

// Procesar el pago (simulado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Registrar la compra simulado ( podría guardarse en una BD)
    $_SESSION['ultima_compra'] = [
        'fecha' => date('Y-m-d H:i:s'),
        'total' => array_reduce($_SESSION['carrito'], function($sum, $item) {
            return $sum + $item['precio'];
        }, 0),
        'items' => $_SESSION['carrito']
    ];
    
    // Vaciar el carrito
    $_SESSION['carrito'] = [];
    
    // Redirigir a confirmación
    header('Location: confirmacion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Agencia de Viajes para Turismo</title> <!--Marcos: Se cambia nombre a página del checkout  -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .checkout-container {
            margin-top: 30px;
        }
        .payment-form {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .order-summary {
            background-color: #e6f2ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            color: #009933;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Finalizar Compra</h1>
    
    <div class="checkout-container">
        <div class="order-summary">
            <h2>Resumen de tu orden</h2>
            <?php foreach ($_SESSION['carrito'] as $item): ?>
                <div class="order-item">
                    <span><?= htmlspecialchars($item['ciudad']) ?>, <?= htmlspecialchars($item['pais']) ?></span>
                    <span>$<?= number_format($item['precio'], 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">
                Total: $<?= number_format(array_reduce($_SESSION['carrito'], function($sum, $item) {
                    return $sum + $item['precio'];
                }, 0), 0, ',', '.') ?>
            </div>
        </div>
        
        <div class="payment-form">
            <h2>Información de Pago</h2>
            <form method="post">
                <div class="form-group">
                    <label for="nombre">Nombre en la tarjeta</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="tarjeta">Número de tarjeta</label>
                    <input type="text" id="tarjeta" name="tarjeta" required>
                </div>
                
                <div class="form-row" style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label for="vencimiento">Fecha de vencimiento</label>
                        <input type="text" id="vencimiento" name="vencimiento" placeholder="MM/AA" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" required>
                    </div>
                </div>
                
                <button type="submit" style="background-color: #2ecc71; color: white; padding: 12px 20px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%; margin-top: 20px;">
                    Confirmar Pago
                </button>
            </form>
        </div>
    </div>
</body>
</html>
