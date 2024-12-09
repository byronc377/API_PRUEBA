<?php

class Database {
    private static $connection;

    // Conectar a la base de datos
    public static function connect() {
        if (!self::$connection) {
            self::$connection = new PDO('mysql:host=localhost;dbname=appprueba', 'root', '');
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }

    // Cerrar la conexión
    public static function close() {
        self::$connection = null;
    }

    // Iniciar una transacción
    public static function beginTransaction() {
        self::$connection->beginTransaction();
    }

    // Confirmar la transacción (commit)
    public static function commit() {
        self::$connection->commit();
    }

    // Revertir la transacción (rollback)
    public static function rollback() {
        self::$connection->rollBack();
    }
}
?>
