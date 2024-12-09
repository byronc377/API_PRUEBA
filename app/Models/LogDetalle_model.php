<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/API_PRUEBA/core/Database.php';

class LogDetalle_model{
    public $idLogModificacion;
    public $parametro;
    public $valorAnterior;
    public $valorNuevo;


    public function setIdLogModificacion($idLogModificacion){
        $this->idLogModificacion = $idLogModificacion;
        return $this->idLogModificacion;
    }

    public function setParametro($parametro){
        $this->parametro = $parametro;
        return $this->parametro;
    }
    
    public function setValorAnterior($valorAnterior){
        $this->valorAnterior = $valorAnterior;
        return $this->valorAnterior;
    }

    public function setValorNuevo($valorNuevo){
        $this->valorNuevo =$valorNuevo;
        return $this->valorNuevo;
    }


    public function getLogByCliente($idCliente){
        $db = Database::connect(); 
        $sql = "SELECT LG.ID_LOG_MODIFICACION, LG.ID_DETALLE_CLIENTE, LG.CREATED_AT, US.NOMBRE AS USUARIO, 
        GROUP_CONCAT(LGD.ID_DETALLE_LOG) AS DETALLES_LOG, GROUP_CONCAT(LGD.PARAMETRO) AS PARAMETROS, 
        GROUP_CONCAT(LGD.VALOR_ANTERIOR) AS VALORES_ANTERIORES, GROUP_CONCAT(LGD.VALOR_NUEVO) AS VALORES_NUEVOS 
        FROM OC_LOG_MODIFICACION LG INNER JOIN OC_DETALLE_LOG LGD ON LG.ID_LOG_MODIFICACION = LGD.ID_LOG_MODIFICACION 
        LEFT JOIN OC_DETALLE_CLIENTE OCD ON LG.ID_DETALLE_CLIENTE = OCD.ID_DETALLE_CLIENTE 
        LEFT JOIN OC_CLIENTES OCC ON OCD.ID_CLIENTE = OCC.ID_CLIENTE 
        LEFT JOIN USUARIOS US ON LG.USUARIO_CREATED = US.ID_USUARIO 
        WHERE OCC.ID_CLIENTE = :ID_CLIENTE GROUP BY LG.ID_LOG_MODIFICACION";
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':ID_CLIENTE', $idCliente);
        $stmt->execute();
        return ($stmt->fetchAll(PDO::FETCH_ASSOC)) ?? [];
    }

    public function insertDetalleLog(){
        $db = Database::connect(); 
        $sql = "INSERT INTO OC_DETALLE_LOG(ID_LOG_MODIFICACION, PARAMETRO, VALOR_ANTERIOR, VALOR_NUEVO) VALUES(:ID_LOG_MODIFICACION, :PARAMETRO, :VALOR_ANTERIOR, :VALOR_NUEVO)";
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':ID_LOG_MODIFICACION', $this->idLogModificacion);
        $stmt->bindParam(':PARAMETRO', $this->parametro);
        $stmt->bindParam(':VALOR_ANTERIOR', $this->valorAnterior);
        $stmt->bindParam(':VALOR_NUEVO', $this->valorNuevo);
        $stmt->execute();
        $lastInsertId = ($db->lastInsertId()) ?? 0;
        return $lastInsertId;
    }
}