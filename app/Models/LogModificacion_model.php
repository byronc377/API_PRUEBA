<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/API_PRUEBA/core/Database.php';

class LogModificacion_model{
    public $idDetalleCliente;
    public $usuarioCreated;

    public function setIdDetalleCliente($idDetalleCliente){
        $this->idDetalleCliente = $idDetalleCliente;
        return $this->idDetalleCliente;
    }

    public function setUsuarioCreated($usuarioCreated){
        $this->usuarioCreated = $usuarioCreated;
        return $this->usuarioCreated;
    }

    public function insertLogModificacion(){
        $db = Database::connect(); 
        $sql = "INSERT INTO OC_LOG_MODIFICACION(ID_DETALLE_CLIENTE, USUARIO_CREATED) VALUES(:ID_DETALLE_CLIENTE, :USUARIO_CREATED)";
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':ID_DETALLE_CLIENTE', $this->idDetalleCliente);
        $stmt->bindParam(':USUARIO_CREATED', $usuarioCreated);
        $stmt->execute();
        $lastInsertId = ($db->lastInsertId()) ?? 0;
        return $lastInsertId;
    }
}