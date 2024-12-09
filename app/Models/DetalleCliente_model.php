<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/API_PRUEBA/core/Database.php';

class DetalleCliente_model{
    public $idCliente;
    public $dui;
    public $direccion;
    public $usuarioModificador;
    public $idDetalleCliente;
    public $estadoCliente;

    public function setIdCliente($idCliente){
        $this->idCliente = $idCliente;
        return $this->idCliente;
    }

    public function setDui($dui){
        $this->dui = $dui;
        return $this->dui;
    }

    public function setDireccion($direccion){
        $this->direccion = $direccion;
        return $this->direccion; 
    }

    public function setUsuarioModificador($usuarioModificador){
        $this->usuarioModificador = $usuarioModificador;
        return $this->usuarioModificador;
    }

    public function setIdDetalleCliente($idDetalleCliente){
        $this->idDetalleCliente = $idDetalleCliente;
        return $this->idDetalleCliente;
    }

    public function setEstadoCliente($estadoCliente){
        $this->estadoCliente = $estadoCliente;
        return $this->estadoCliente;
    }

    public function insertDetalleCliente(){
        $db = Database::connect();
        $sql = "INSERT INTO OC_DETALLE_CLIENTE(ID_CLIENTE, DUI, DIRECCION) VALUES(:ID_CLIENTE, :DUI, :DIRECCION)";
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':ID_CLIENTE', $this->idCliente);
        $stmt->bindParam(':DUI', $this->dui);
        $stmt->bindParam(':DIRECCION', $this->direccion);
        $stmt->execute();
        $lastInsertId = $db->lastInsertId();
        return $lastInsertId;
    }

    public function updateDetalleCliente($param){
        $valor = "";
        $db = Database::connect();
        if($param == "DUI"){
            $sql = "UPDATE OC_DETALLE_CLIENTE SET DUI = :PRMTRO, USUARIO_MODIFICADOR =:USUARIO WHERE ID_DETALLE_CLIENTE = :ID_DETALLE_CLIENTE";
            $valor = $this->dui;
        }else{
            $sql = "UPDATE OC_DETALLE_CLIENTE SET DIRECCION = :PRMTRO, USUARIO_MODIFICADOR = :USUARIO WHERE ID_DETALLE_CLIENTE = :ID_DETALLE_CLIENTE";
            $valor = $this->direccion;
        }
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':PRMTRO', $valor);
        $stmt->bindParam(':ID_DETALLE_CLIENTE', $this->idDetalleCliente); 
        $stmt->bindParam(':USUARIO', $this->usuarioModificador); 
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateEstadoDetalle(){
        $db = Database::connect();
        $sql = "UPDATE OC_DETALLE_CLIENTE SET ESTADO_DETALLE = :ESTADO WHERE ID_DETALLE_CLIENTE = :ID_DETALLE_CLIENTE";
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':ID_DETALLE_CLIENTE', $this->idDetalleCliente); 
        $stmt->bindParam(':ESTADO', $this->estadoCliente); 
        $stmt->execute();
        return $stmt->rowCount();
    }
}