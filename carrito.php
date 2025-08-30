<?php
session_start();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar':
                if (isset($_POST['id_paquete'])) {
                    agregarAlCarrito($_POST['id_paquete'], $_POST);
                }
                break;
            case 'eliminar':
                if (isset($_POST['indice'])) {
                    eliminarDelCarrito($_POST['indice']);
                }
                break;
            case 'vaciar':
                vaciarCarrito();
                break;
        }
    }
    // Redirigir para evitar reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Funciones del carrito
function agregarAlCarrito($id, $datosPaquete) {
    $paquete = [
        'id' => $id,
        'ciudad' => $datosPaquete['ciudad'] ?? '',
        'pais' => $datosPaquete['pais'] ?? '',
        'hotel' => $datosPaquete['hotel'] ?? '',
        'fecha' => $datosPaquete['fecha'] ?? '',
        'precio' => $datosPaquete['precio'] ?? 0,
        'descripcion' => $datosPaquete['descripcion'] ?? ''
    ];
    
    $_SESSION['carrito'][] = $paquete;
}

function eliminarDelCarrito($indice) {
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        // Reindexar el array
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

function vaciarCarrito() {
    $_SESSION['carrito'] = [];
}

function calcularTotal() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Agencia de Viajes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .cart-container {
            margin-top: 30px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            margin-bottom: 10px;
        }
        .item-info {
            flex: 2;
        }
        .item-price {
            flex: 1;
            text-align: right;
            font-weight: bold;
            color: #0066cc;
        }
        .item-actions {
            flex: 1;
            text-align: right;
        }
        .cart-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            color: #009933;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            color: white;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        .empty-cart {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.1em;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>
    
    <div class="cart-container">
        <?php if (empty($_SESSION['carrito'])): ?>
            <div class="empty-cart">
                <p>Tu carrito está vacío</p>
                <a href="buscar_viajes.php" class="btn btn-primary">Buscar paquetes turísticos</a>
            </div>
        <?php else: ?>
            <?php foreach ($_SESSION['carrito'] as $indice => $item): ?>
                <div class="cart-item">
                    <div class="item-info">
                        <h3><?= htmlspecialchars($item['ciudad']) ?>, <?= htmlspecialchars($item['pais']) ?></h3>
                        <p><strong>Hotel:</strong> <?= htmlspecialchars($item['hotel']) ?></p>
                        <p><strong>Fecha:</strong> <?= htmlspecialchars($item['fecha']) ?></p>
                        <p><?= htmlspecialchars($item['descripcion']) ?></p>
                    </div>
                    <div class="item-price">
                        $<?= number_format($item['precio'], 0, ',', '.') ?>
                    </div>
                    <div class="item-actions">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="indice" value="<?= $indice ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="cart-summary">
                <h2>Resumen de Compra</h2>
                <p>Total de items: <?= count($_SESSION['carrito']) ?></p>
                <p class="total">Total a pagar: $<?= number_format(calcularTotal(), 0, ',', '.') ?></p>
                
                <div style="margin-top: 20px;">
                    <form method="post" style="display: inline-block; margin-right: 10px;">
                        <input type="hidden" name="accion" value="vaciar">
                        <button type="submit" class="btn btn-danger">Vaciar Carrito</button>
                    </form>
                    <a href="checkout.php" class="btn btn-success">Proceder al Pago</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>