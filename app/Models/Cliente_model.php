<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/API_PRUEBA/core/Database.php';

class Cliente_model{
    public $nombreCliente;
    public $fechaNacimiento;
    public $usuarioCreador;

    public function setNombreCliente($nombreCliente){
        $this->nombreCliente = $nombreCliente;
        return $this->nombreCliente;
    }

    public function setFechaNacimiento($fechaNacimiento){
        $this->fechaNacimiento = $fechaNacimiento;
        return $this->fechaNacimiento;
    }

    public function setUsuarioCreador($usuarioCreador){
        $this->usuarioCreador = $usuarioCreador;
        return $this->usuarioCreador;
    }


    public function insertNuevoCliente() {
        $db = Database::connect();
        $query = "INSERT INTO OC_CLIENTES (NOMBRE_CLIENTE, FECHA_NACIMIENTO, USUARIO_CREADOR) 
                VALUES (:NOMBRE_CLIENTE, STR_TO_DATE(:FECHA_NACIMIENTO, '%Y-%m-%d'), :USUARIO_CREADOR)";
        $stmt = $db->prepare($query); 
        $stmt->bindParam(':NOMBRE_CLIENTE', $this->nombreCliente);
        $stmt->bindParam(':FECHA_NACIMIENTO', $this->fechaNacimiento);
        $stmt->bindParam(':USUARIO_CREADOR', $this->usuarioCreador);
        $stmt->execute();
        $lastInsertId = $db->lastInsertId();
        return $lastInsertId;
    }

    public function getClienteById($id){
        $db = Database::connect();
        $query = "SELECT CL.NOMBRE_CLIENTE, CL.FECHA_NACIMIENTO, CL.ID_CLIENTE FROM OC_CLIENTES CL
        WHERE CL.ID_CLIENTE = :ID_CLIENTE";
        $stmt = $db->prepare($query); 
        $stmt->bindParam(':ID_CLIENTE', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetalleClienteById($id){
        $db = Database::connect();
        $query = "SELECT CL.NOMBRE_CLIENTE, CL.FECHA_NACIMIENTO, DCL.ID_CLIENTE, DCL.ID_DETALLE_CLIENTE,
        DCL.DUI, DCL.DIRECCION, DCL.CREATED_AT AS FECHA_CREACION, DCL.ESTADO_DETALLE
        FROM OC_CLIENTES CL
        LEFT JOIN OC_DETALLE_CLIENTE DCL ON CL.ID_CLIENTE = DCL.ID_CLIENTE
        WHERE DCL.ID_CLIENTE = :ID_CLIENTE";
        $stmt = $db->prepare($query); 
        $stmt->bindParam(':ID_CLIENTE', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientesByFecha($fechaInicio, $fechaFin) {
        $db = Database::connect();
            $query = "SELECT CL.NOMBRE_CLIENTE, CL.FECHA_NACIMIENTO, CL.ID_CLIENTE 
                  FROM OC_CLIENTES CL
                  WHERE CL.CREATED_AT BETWEEN STR_TO_DATE(:fechaInicio, '%Y-%m-%d') AND STR_TO_DATE(:fechaFin, '%Y-%m-%d')";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}