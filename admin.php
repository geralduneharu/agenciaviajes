<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Agencia de Viajes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .admin-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }
        .form-section {
            flex: 1;
            background-color: white;
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
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .form-row .form-group {
            flex: 1;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .tabla-resultados {
            margin-top: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .nav-tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            border-bottom: 1px solid #ddd;
        }
        .nav-tabs li {
            margin-right: 10px;
        }
        .nav-tabs a {
            display: block;
            padding: 10px 20px;
            background-color: #f1f1f1;
            color: #333;
            text-decoration: none;
            border-radius: 5px 5px 0 0;
        }
        .nav-tabs a.active {
            background-color: #3498db;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <h1>Panel de Administración</h1>
    
    <ul class="nav-tabs">
        <li><a href="#formularios" class="active">Formularios</a></li>
        <li><a href="#registros">Registros</a></li>
    </ul>
    
    <div id="formularios" class="tab-content active">
        <div class="admin-container">
            <!-- Formulario para agregar vuelos -->
            <div class="form-section">
                <h2>Agregar Nuevo Vuelo</h2>
                <form id="form-vuelo" method="POST" action="guardar_vuelo.php" onsubmit="return validarVuelo()">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="origen">Origen</label>
                            <input type="text" id="origen" name="origen" required>
                        </div>
                        <div class="form-group">
                            <label for="destino">Destino</label>
                            <input type="text" id="destino" name="destino" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha">Fecha y Hora</label>
                            <input type="datetime-local" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="plazas">Plazas Disponibles</label>
                            <input type="number" id="plazas" name="plazas" min="1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="precio">Precio ($)</label>
                            <input type="number" id="precio" name="precio" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="aerolinea">Aerolínea</label>
                            <input type="text" id="aerolinea" name="aerolinea" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código de Vuelo</label>
                            <input type="text" id="codigo" name="codigo" required>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración (minutos)</label>
                            <input type="number" id="duracion" name="duracion" min="1" required>
                        </div>
                    </div>
                    
                    <button type="submit">Guardar Vuelo</button>
                </form>
            </div>
            
            <!-- Formulario para agregar hoteles -->
            <div class="form-section">
                <h2>Agregar Nuevo Hotel</h2>
                <form id="form-hotel" method="POST" action="guardar_hotel.php" onsubmit="return validarHotel()">
                    <div class="form-group">
                        <label for="nombre_hotel">Nombre del Hotel</label>
                        <input type="text" id="nombre_hotel" name="nombre_hotel" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ubicacion">Ubicación (Ciudad, País)</label>
                        <input type="text" id="ubicacion" name="ubicacion" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="habitaciones">Habitaciones Disponibles</label>
                            <input type="number" id="habitaciones" name="habitaciones" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="tarifa">Tarifa por Noche ($)</label>
                            <input type="number" id="tarifa" name="tarifa" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion_hotel">Descripción (Opcional)</label>
                        <textarea id="descripcion_hotel" name="descripcion_hotel" rows="3"></textarea>
                    </div>
                    
                    <button type="submit">Guardar Hotel</button>
                </form>
            </div>
        </div>
    </div>
    
    <div id="registros" class="tab-content">
        <div class="admin-container">
            <div class="form-section">
                <h2>Vuelos Registrados</h2>
                <div class="tabla-resultados">
                    <?php include 'listar_vuelo.php'; 
                    ?>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Hoteles Registrados</h2>
                <div class="tabla-resultados">
                    <?php include 'listar_hoteles.php'; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Validación del formulario de vuelos
        function validarVuelo() {
            const origen = document.getElementById('origen').value;
            const destino = document.getElementById('destino').value;
            const fecha = document.getElementById('fecha').value;
            const plazas = document.getElementById('plazas').value;
            const precio = document.getElementById('precio').value;
            
            if (!origen || !destino || !fecha || !plazas || !precio) {
                alert('Por favor complete todos los campos obligatorios');
                return false;
            }
            
            if (origen === destino) {
                alert('El origen y el destino no pueden ser iguales');
                return false;
            }
            
            if (plazas < 1) {
                alert('Debe haber al menos 1 plaza disponible');
                return false;
            }
            
            if (precio <= 0) {
                alert('El precio debe ser mayor a 0');
                return false;
            }
            
            return true;
        }
        
        // Validación del formulario de hoteles
        function validarHotel() {
            const nombre = document.getElementById('nombre_hotel').value;
            const ubicacion = document.getElementById('ubicacion').value;
            const habitaciones = document.getElementById('habitaciones').value;
            const tarifa = document.getElementById('tarifa').value;
            
            if (!nombre || !ubicacion || !habitaciones || !tarifa) {
                alert('Por favor complete todos los campos obligatorios');
                return false;
            }
            
            if (habitaciones < 1) {
                alert('Debe haber al menos 1 habitación disponible');
                return false;
            }
            
            if (tarifa <= 0) {
                alert('La tarifa debe ser mayor a 0');
                return false;
            }
            
            return true;
        }
        
        // Manejo de pestañas
        document.querySelectorAll('.nav-tabs a').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Ocultar todos los contenidos de pestañas
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Desactivar todas las pestañas
                document.querySelectorAll('.nav-tabs a').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Activar pestaña seleccionada
                this.classList.add('active');
                const target = this.getAttribute('href');
                document.querySelector(target).classList.add('active');
            });
        });
    </script>
</body>
</html>