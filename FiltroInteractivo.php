<?php
class FiltroInteractivo {
    // Propiedades del filtro
    private $ciudad;
    private $pais;
    private $nombreHotel;
    private $fechaViaje;
    private $duracion;
    private $rangoPrecio;
    
    /**
     * Constructor de la clase
     * @param array $datos Los datos del formulario de búsqueda
     */
    public function __construct($datos = []) {
        // Inicializamos las propiedades con los valores del formulario o valores por defecto
        $this->ciudad = $datos['ciudad'] ?? '';
        $this->pais = $datos['pais'] ?? '';
        $this->nombreHotel = $datos['nombre_hotel'] ?? '';
        $this->fechaViaje = $datos['fecha_viaje'] ?? '';
        $this->duracion = $datos['duracion'] ?? 1;
        $this->rangoPrecio = $datos['price-range'] ?? '';
    }
    
    /**
     * Valida si el filtro está vacío (sin criterios de búsqueda)
     * @return bool
     */
    public function estaVacio() {
        return empty($this->ciudad) && 
               empty($this->pais) && 
               empty($this->nombreHotel) && 
               empty($this->fechaViaje) && 
               empty($this->rangoPrecio);
    }
    
    /**
     * Filtra un array de viajes según los criterios actuales
     * @param array $viajes Array de viajes a filtrar
     * @return array Viajes que coinciden con los filtros
     */
    public function filtrar($viajes) {
        return array_filter($viajes, function($viaje) {
            // Verifica coincidencia con la ciudad (si se especificó)
            $coincideCiudad = empty($this->ciudad) || 
                             stripos($viaje['ciudad'], $this->ciudad) !== false;
            
            // Verifica coincidencia con el país (si se especificó)
            $coincidePais = empty($this->pais) || 
                           stripos($viaje['pais'], $this->pais) !== false;
            
            // Verifica coincidencia con el hotel (si se especificó)
            $coincideHotel = empty($this->nombreHotel) || 
                             stripos($viaje['hotel'], $this->nombreHotel) !== false;
            
            // Verifica coincidencia con la fecha (si se especificó)
            $coincideFecha = empty($this->fechaViaje) || 
                            $viaje['fecha'] === $this->fechaViaje;
            
            // Verifica coincidencia con el rango de precio (si se especificó)
            $coincidePrecio = true;
            if (!empty($this->rangoPrecio)) {
                switch ($this->rangoPrecio) {
                    case 'economy':
                        $coincidePrecio = $viaje['precio'] <= 500000;
                        break;
                    case 'medium':
                        $coincidePrecio = $viaje['precio'] > 500000 && $viaje['precio'] <= 2000000;
                        break;
                    case 'premium':
                        $coincidePrecio = $viaje['precio'] > 3000000;
                        break;
                }
            }
            
            // Todos los criterios deben coincidir
            return $coincideCiudad && $coincidePais && $coincideHotel && 
                   $coincideFecha && $coincidePrecio;
        });
    }
    
    /**
     * Genera un mensaje descriptivo de los filtros aplicados
     * @return string
     */
    public function getMensajeFiltros() {
        if ($this->estaVacio()) {
            return "Mostrando todos los destinos disponibles";
        }
        
        $mensaje = "Resultados para: ";
        $filtros = [];
        
        if (!empty($this->ciudad)) {
            $filtros[] = "ciudad '{$this->ciudad}'";
        }
        
        if (!empty($this->pais)) {
            $filtros[] = "país '{$this->pais}'";
        }
        
        if (!empty($this->nombreHotel)) {
            $filtros[] = "hotel '{$this->nombreHotel}'";
        }
        
        if (!empty($this->fechaViaje)) {
            $filtros[] = "fecha '{$this->fechaViaje}'";
        }
        
        if (!empty($this->rangoPrecio)) {
            $rango = [
                'economy' => 'económico',
                'medium' => 'medio',
                'premium' => 'premium'
            ];
            $filtros[] = "rango {$rango[$this->rangoPrecio]}";
        }
        
        return $mensaje . implode(', ', $filtros);
    }
    
    // Getters para acceder a las propiedades
    public function getCiudad() { return $this->ciudad; }
    public function getPais() { return $this->pais; }
    public function getNombreHotel() { return $this->nombreHotel; }
    public function getFechaViaje() { return $this->fechaViaje; }
    public function getDuracion() { return $this->duracion; }
    public function getRangoPrecio() { return $this->rangoPrecio; }
}
?>