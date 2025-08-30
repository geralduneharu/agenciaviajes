<?php
class Database {
    // Configuración de la conexión a la BD
    private $host = 'localhost';
    private $db_name = 'Agencia3';
    private $username = 'postgres';
    private $password = '';
    private $port = '5432'; // Puerto por defecto de PostgreSQL
    private $conn;

    // Método para conectar a la base de datos
    public function connect() {
        $this->conn = null;

        try {
            // Configuración DSN para PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            
            // Opciones de PDO para conexión segura
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Retornar arrays asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                 // Usar prepared statements nativos
                PDO::ATTR_PERSISTENT         => false,                 // No usar conexiones persistentes
            ];
            
            // Crear instancia PDO
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
            // Forzar codificación UTF-8
            $this->conn->exec("SET NAMES 'UTF8'");

        } catch(PDOException $e) {
            // En producción, registrar el error en un archivo de log
            error_log("Error de conexión: " . $e->getMessage());
            
            // Mostrar un mensaje genérico al usuario
            throw new Exception("Error al conectar con la base de datos. Por favor, inténtelo más tarde.");
        }

        return $this->conn;
    }

    // Método para ejecutar consultas preparadas de forma segura
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->connect()->prepare($sql);
            
            // Validar y ejecutar con parámetros
            if (!empty($params)) {
                foreach ($params as $key => &$value) {
                    if (is_int($value)) {
                        $stmt->bindParam($key, $value, PDO::PARAM_INT);
                    } else {
                        $stmt->bindParam($key, $value, PDO::PARAM_STR);
                    }
                }
            }
            
            $stmt->execute($params);
            return $stmt;
            
        } catch(PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage() . "\nConsulta: " . $sql);
            throw new Exception("Error al procesar la solicitud.");
        }
    }

    // Método para obtener el último ID insertado
    public function lastInsertId() {
        return $this->connect()->lastInsertId();
    }

    // Método para iniciar una transacción
    public function beginTransaction() {
        return $this->connect()->beginTransaction();
    }

    // Método para confirmar una transacción
    public function commit() {
        return $this->connect()->commit();
    }

    // Método para revertir una transacción
    public function rollBack() {
        return $this->connect()->rollBack();
    }
}
?>