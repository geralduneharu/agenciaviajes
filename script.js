// Clase para representar cada paquete turístico con sus propiedades y métodos
class PaqueteTuristico {
    constructor(id, nombre, fecha, precio, tipo, descripcion) {
        // Propiedades básicas del paquete
        this.id = id;                       // Identificador único
        this.nombre = nombre;               // Nombre del destino (formato "Ciudad, País")
        this.fecha = fecha;                 // Fecha del viaje (formato YYYY-MM-DD)
        this.precio = precio;               // Precio base del paquete
        this.tipo = tipo;                   // Tipo: economy, medium o premium
        this.descripcion = descripcion;     // Descripción detallada
        this.disponible = true;             // Estado de disponibilidad
        this.oferta = null;                 // Información de ofertas (inicialmente null)
    }

    // Método para aplicar una oferta al paquete
    aplicarOferta(nombreOferta, porcentajeDescuento, fechaExpiracion) {
        this.oferta = {
            nombre: nombreOferta,                          // Nombre de la oferta
            descuento: porcentajeDescuento,               // Porcentaje de descuento
            expiracion: fechaExpiracion,                  // Fecha de expiración
            precioOriginal: this.precio,                  // Guarda el precio original
            precioConDescuento: this.precio * (1 - porcentajeDescuento / 100)  // Calcula precio con descuento
        };
        return this;  // Permite encadenamiento de métodos
    }

    // Verifica si el paquete tiene una oferta activa (no expirada)
    tieneOfertaActiva() {
        if (!this.oferta) return false;  // Si no hay oferta, retorna falso
        
        // Compara la fecha de expiración con la fecha actual
        const hoy = new Date().toISOString().split('T')[0];
        return this.oferta.expiracion >= hoy;
    }

    // Genera el HTML para mostrar el paquete como una tarjeta en la interfaz
    generarTarjeta() {
        const fechaFormateada = this.formatearFecha();
        
        // Extraer ciudad y país del nombre (asumiendo formato "Ciudad, País")
        const [ciudad, pais] = this.nombre.includes(',') ? 
            this.nombre.split(',').map(item => item.trim()) : 
            [this.nombre, '']; // Fallback si no hay coma
        
        // Decide qué precio mostrar (original o con descuento)
        const precioMostrado = this.tieneOfertaActiva() 
            ? `<span class="original-price">$${this.precio.toLocaleString('es-CL')}</span>
               <span class="discount-price">$${this.oferta.precioConDescuento.toLocaleString('es-CL', {maximumFractionDigits: 0})}</span>
               <span class="discount-badge">-${this.oferta.descuento}%</span>`
            : `<span class="price">$${this.precio.toLocaleString('es-CL', {maximumFractionDigits: 0})}</span>`;

        // Plantilla HTML de la tarjeta actualizada
        return `
            <div class="travel-result" data-id="${this.id}">
                ${this.tieneOfertaActiva() ? `<div class="offer-tag">${this.oferta.nombre}</div>` : ''}
                <div class="info">
                    <h3>${ciudad}${pais ? ', ' + pais : ''}</h3>
                    <p class="details">${this.descripcion}</p>
                    <p class="details">Fecha: ${fechaFormateada}</p>
                    <div class="price-container">
                        ${precioMostrado}
                    </div>
                    <button class="btn-reservar" onclick="reservarPaquete(${this.id})">
                        ${this.disponible ? 'Reservar ahora' : 'Agotado'}
                    </button>
                </div>
            </div>
        `;
    }

    // Formatea la fecha para mostrarla de manera más legible
    formatearFecha() {
        const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(this.fecha).toLocaleDateString('es-ES', opciones);
    }
}

// Clase para gestionar la colección de paquetes turísticos
class GestorPaquetes {
    constructor() {
        this.paquetes = [];  // Almacena todos los paquetes
    }

    // Busca paquetes según los filtros proporcionados
    buscarPaquetes(filtros = {}) {
        return this.paquetes.filter(paquete => {
            // Filtrado por destino (búsqueda por texto)
            const coincideDestino = filtros.destino 
                ? paquete.nombre.toLowerCase().includes(filtros.destino.toLowerCase())
                : true;
            
            // Filtrado por fecha exacta
            const coincideFecha = filtros.fecha 
                ? paquete.fecha === filtros.fecha
                : true;
            
            // Filtrado por tipo de paquete
            const coincideTipo = filtros.tipo 
                ? paquete.tipo === filtros.tipo
                : true;
            
            // Filtrado por precio máximo (considerando ofertas si existen)
            const coincidePrecioMax = filtros.precioMax 
                ? (paquete.tieneOfertaActiva() 
                    ? paquete.oferta.precioConDescuento 
                    : paquete.precio) <= filtros.precioMax
                : true;

            // Todos los filtros deben coincidir y el paquete debe estar disponible
            return coincideDestino && coincideFecha && coincideTipo && coincidePrecioMax && paquete.disponible;
        });
    }

    // Carga datos iniciales de ejemplo
    cargarDatosIniciales() {
        const datosIniciales = [
            {
                id: 1,
                nombre: "Santiago, Chile",
                fecha: "2025-08-10",
                precio: 200000,
                tipo: "economy",
                descripcion: "Paquete todo incluido con vuelo, hotel 5 estrellas y tours guiados."
            },
            {
                id: 2,
                nombre: "Cancún, México",
                fecha: "2025-12-10",
                precio: 850000,
                tipo: "medium",
                descripcion: "Vuelo + hotel todo incluido en zona hotelera."
            },
            {
                id: 3,
                nombre: "Buenos Aires, Argentina",
                fecha: "2025-09-20",
                precio: 450000,
                tipo: "economy",
                descripcion: "Vuelo + hotel 3 estrellas en centro de la ciudad."
            },
            {
                id: 4,
                nombre: "Tokio, Japón",
                fecha: "2025-09-05",
                precio: 5000000,
                tipo: "premium",
                descripcion: "Experiencia cultural completa con alojamiento en ryokan tradicional."
            },
            {
                id: 5,
                nombre: "Cartagena, Colombia",
                fecha: "2025-08-12",
                precio: 400000,
                tipo: "economy",
                descripcion: "Vuelo + hotel en el centro histórico."
            }
        ];

        // Convierte los datos en instancias de PaqueteTuristico
        this.paquetes = datosIniciales.map(dest => new PaqueteTuristico(
            dest.id,
            dest.nombre,
            dest.fecha,
            dest.precio,
            dest.tipo,
            dest.descripcion
        ));
        
        // Aplica ofertas especiales a algunos paquetes
        this.paquetes[0].aplicarOferta('Oferta Especial Chile', 15, '2025-08-10');
        this.paquetes[3].aplicarOferta('Promo Tokio', 10, '2025-09-05');
        
        return this;  // Permite encadenamiento de métodos
    }
}

// Clase para manejar notificaciones en la interfaz
class Notificador {
    constructor() {
        this.notificationBanner = document.getElementById('notification-banner');
        this.notificationQueue = [];    // Cola de notificaciones pendientes
        this.isShowingNotification = false;  // Estado actual
    }

    // Añade una notificación a la cola
    show(message, isError = false, duration = 5000) {
        const notification = {
            message,
            isError,
            duration
        };
        
        this.notificationQueue.push(notification);
        
        // Si no se está mostrando ninguna notificación, procesa la cola
        if (!this.isShowingNotification) {
            this.processQueue();
        }
    }

    // Procesa las notificaciones en la cola una por una
    processQueue() {
        if (this.notificationQueue.length === 0) {
            this.isShowingNotification = false;
            return;
        }

        this.isShowingNotification = true;
        const current = this.notificationQueue.shift();
        
        this.displayNotification(current.message, current.isError);
        
        // Oculta la notificación después de la duración especificada
        setTimeout(() => {
            this.hideNotification();
            // Procesa la siguiente notificación después de un pequeño retraso
            setTimeout(() => this.processQueue(), 500);
        }, current.duration);
    }

    // Muestra la notificación en el banner
    displayNotification(message, isError) {
        this.notificationBanner.textContent = message;
        this.notificationBanner.className = isError ? 'error-notification' : 'success-notification';
        this.notificationBanner.style.display = 'block';
        this.notificationBanner.style.opacity = '1';
    }

    // Oculta la notificación con animación
    hideNotification() {
        this.notificationBanner.style.opacity = '0';
        setTimeout(() => {
            this.notificationBanner.style.display = 'none';
        }, 500);
    }
}

// Variables globales
const gestorPaquetes = new GestorPaquetes().cargarDatosIniciales();
const notificador = new Notificador();

// Modifica la función mostrarResultados en tu script.js
function mostrarResultados(paquetes) {
    const contenedor = document.getElementById('results-container');
    contenedor.innerHTML = '';
    
    if (paquetes.length === 0) {
        notificador.show('No se encontraron resultados', true);
        contenedor.innerHTML = '<p class="no-results">No hay paquetes disponibles con esos filtros</p>';
        return;
    }
    
    paquetes.forEach(paquete => {
        contenedor.insertAdjacentHTML('beforeend', paquete.generarTarjeta());
    });
}

// Procesa la reserva de un paquete
function reservarPaquete(id) {
    const paquete = gestorPaquetes.paquetes.find(p => p.id === id);
    
    if (paquete && paquete.disponible) {
        paquete.disponible = false;
        mostrarResultados(gestorPaquetes.buscarPaquetes(getFiltrosActuales()));
        notificador.show(`¡Reserva confirmada para ${paquete.nombre.split(',')[0].trim()}!`, false);
        
        // Simula que vuelve a haber disponibilidad después de 30 segundos
        setTimeout(() => {
            paquete.disponible = true;
            notificador.show(`¡Nuevas plazas disponibles para ${paquete.nombre.split(',')[0].trim()}!`, false);
            mostrarResultados(gestorPaquetes.buscarPaquetes(getFiltrosActuales()));
        }, 30000);
    } else {
        notificador.show('Este paquete no está disponible', true);
    }
}

// Obtiene los valores actuales de los filtros del formulario
function getFiltrosActuales() {
    return {
        destino: document.getElementById('destination').value,
        fecha: document.getElementById('travel-date').value,
        tipo: document.getElementById('price-range').value
    };
}

// Realiza una búsqueda con los filtros actuales
function search() {
    const filtros = getFiltrosActuales();
    const resultados = gestorPaquetes.buscarPaquetes(filtros);
    mostrarResultados(resultados);
}

// Simula actualizaciones en tiempo real (ofertas y disponibilidad)
function simularActualizacionesTiempoReal() {
    // Oferta especial cada 2 minutos
    setInterval(() => {
        const ofertas = [
            "¡Oferta especial! 20% de descuento en paquetes a Europa",
            "Últimas plazas disponibles para el verano 2025",
            "Promoción relámpago: 15% de descuento en hoteles premium"
        ];
        const ofertaAleatoria = ofertas[Math.floor(Math.random() * ofertas.length)];
        notificador.show(ofertaAleatoria, false);
    }, 120000);

    // Actualización de disponibilidad cada minuto
    setInterval(() => {
        const paquetes = gestorPaquetes.paquetes;
        const paqueteAleatorio = paquetes[Math.floor(Math.random() * paquetes.length)];
        
        if (Math.random() > 0.5) {
            paqueteAleatorio.disponible = !paqueteAleatorio.disponible;
            
            const ciudad = paqueteAleatorio.nombre.split(',')[0].trim();
            const mensaje = paqueteAleatorio.disponible 
                ? `¡Nuevas plazas disponibles para ${ciudad}!` 
                : `¡Últimas plazas para ${ciudad}!`;
            
            notificador.show(mensaje, !paqueteAleatorio.disponible);
            mostrarResultados(gestorPaquetes.buscarPaquetes(getFiltrosActuales()));
        }
    }, 60000);
}

// Inicialización del sistema cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
    // Configura la fecha mínima para seleccionar como hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('travel-date').min = hoy;
    
    // Muestra todos los paquetes iniciales
    mostrarResultados(gestorPaquetes.paquetes);
    notificador.show("Bienvenido a nuestra agencia de viajes", false);
    
    // Configura event listeners
    document.getElementById('destination').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') search();
    });
    
    document.getElementById('search-btn').addEventListener('click', search);
    document.getElementById('travel-date').addEventListener('change', search);
    document.getElementById('price-range').addEventListener('change', search);
    
    // Inicia las actualizaciones en tiempo real
    simularActualizacionesTiempoReal();
});
