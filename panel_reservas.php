<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h2 {
            color: #2c3e50;
            margin-top: 30px;
        }
        .section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h1>Panel de Gestión de Reservas</h1>
    
    <div class="section">
        <h2>Todas las Reservas</h2>
        <?php include 'listar_reservas.php'; ?>
    </div>
    
    <div class="section">
        <h2>Hoteles Más Demandados</h2>
        <?php include 'hoteles_populares.php'; ?>
    </div>
</body>
</html>