<?php
require_once dirname(__DIR__) . '/Models/DetalleCliente_model.php';
require_once dirname(__DIR__) . '/Models/LogModificacion_model.php';
require_once dirname(__DIR__) . '/Models/LogDetalle_model.php';

    class DetalleClienteController{
     public function insertDetalleCliente(){
        $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
        if(!empty($data)){
            if(isset($data["idCliente"], $data["dui"], $data["direccion"])){
                $detalleCliente = new DetalleCliente_model();
                $detalleCliente->setIdCliente($data["idCliente"]);
                $detalleCliente->setDui($data["dui"]);
                $detalleCliente->setDireccion($data["direccion"]);
                $detalleInsert = $detalleCliente->insertDetalleCliente();
                if($detalleInsert){
                    echo json_encode(["result"=>"success", "message"=>"detalles del cliente insertados"]);
                }else{
                    echo json_encode(["result"=>"error", "message"=>"no se pudo insertar"]);
                }
            }else{
                echo json_encode(["result"=>"error", "message"=>"datos faltantes para la solicitud"]);
            }
        }else{
            echo json_encode(["result"=>"error", "message"=>"solicitud vacia"]);
        }
    }
    
    public function updateDetalle(){
        $fallas = $logId = 0;
        $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
        if(!empty($data)){
            if(count($data) > 1){
                foreach($data as $datos){
                    $detalleCliente = new DetalleCliente_model();
                    if($datos["parametro"] == "DUI"){
                        $detalleCliente->setDui($datos["valorNuevo"]);
                    }else{
                        $detalleCliente->setDireccion($datos["valorNuevo"]);
                    }
                    $detalleCliente->setIdDetalleCliente($datos["idDetalleCliente"]);
                    $detalleCliente->setUsuarioModificador($datos["usuario"]);
                    $detalleUpdate = $detalleCliente->updateDetalleCliente($datos["parametro"]);
                    if($detalleUpdate == 0){
                        $fallas++;
                    }else{
                        $clienteLog = new LogModificacion_model();
                        $clienteLog->setIdDetalleCliente($datos["idDetalleCliente"]);
                        $clienteLog->setUsuarioCreated($datos["usuario"]);
                        $clienteLogInsert = $clienteLog->insertLogModificacion();
                        if($clienteLogInsert > 0){
                            $logId = $clienteLogInsert;
                            $clienteDetalleLog = new LogDetalle_model();
                            $clienteDetalleLog->setIdLogModificacion($logId);
                            $clienteDetalleLog->setParametro($datos["parametro"]);
                            $clienteDetalleLog->setValorAnterior($datos["valorAnterior"]);
                            $clienteDetalleLog->setValorNuevo($datos["valorNuevo"]);
                            $detalleLogInsert = $clienteDetalleLog->insertDetalleLog();
                            if($detalleLogInsert == 0){
                                $fallas++;
                            }
                        }
                    }
                }
            }else{
                $detalleCliente = new DetalleCliente_model();
                if($data[0]["parametro"] == "DUI"){
                    $detalleCliente->setDui($data[0]["valorNuevo"]);
                }else{
                    $detalleCliente->setDireccion($data[0]["valorNuevo"]);
                }
                $detalleCliente->setIdDetalleCliente($data[0]["idDetalleCliente"]);
                $detalleCliente->setUsuarioModificador($data[0]["usuario"]);
                $detalleUpdate = $detalleCliente->updateDetalleCliente($data[0]["parametro"]);
                if($detalleUpdate == 0){
                    $fallas++;
                }else{
                    $clienteLog = new LogModificacion_model();
                    $clienteLog->setIdDetalleCliente($data[0]["idDetalleCliente"]);
                    $clienteLog->setUsuarioCreated($data[0]["usuario"]);
                    $clienteLogInsert = $clienteLog->insertLogModificacion();
                    if($clienteLogInsert > 0){
                        $logId = $clienteLogInsert;
                        $clienteDetalleLog = new LogDetalle_model();
                        $clienteDetalleLog->setIdLogModificacion($logId);
                        $clienteDetalleLog->setParametro($data[0]["parametro"]);
                        $clienteDetalleLog->setValorAnterior($data[0]["valorAnterior"]);
                        $clienteDetalleLog->setValorNuevo($data[0]["valorNuevo"]);
                        $detalleLogInsert = $clienteDetalleLog->insertDetalleLog();
                        if($detalleLogInsert == 0){
                            $fallas++;
                        }
                    }
                }
            }
            if($fallas == 0){
                echo json_encode(["result"=>"success", "message"=>"se actualizo el registro"]);
            }else{
                echo json_encode(["result"=>"error", "message"=>"no se pudo actualizar el registro"]);
            }
        }else{
            echo json_encode(["result"=>"error", "message"=>"hay datos incompletos en la peticion"]);
        }
    }

    public function updateEstadoDetalle(){
        $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
        $fallas = 0;
        $response = [];
        if(!empty($data)){
            if (isset($data["idDetalle"], $data["estado"]) && !empty($data["idDetalle"]) && !empty($data["estado"])) {
                $detalleCliente = new DetalleCliente_model();
                $detalleCliente->setIdDetalleCliente($data["idDetalle"]);
                $detalleCliente->setEstadoCliente($data["estado"]);
                $detalleEstadoUpdate = $detalleCliente->updateEstadoDetalle();
                if($detalleEstadoUpdate == 0){
                    $fallas++;
                }else{
                    $estados = ($data["estado"] == "activo") ? 'inactivo': 'activo';
                    $clienteLog = new LogModificacion_model();
                    $clienteLog->setIdDetalleCliente($data["idDetalle"]);
                    $clienteLog->setUsuarioCreated($data["usuario"]);
                    $clienteLogInsert = $clienteLog->insertLogModificacion();
                    if($clienteLogInsert > 0){
                        $logId = $clienteLogInsert;
                        $clienteDetalleLog = new LogDetalle_model();
                        $clienteDetalleLog->setIdLogModificacion($logId);
                        $clienteDetalleLog->setParametro("ESTADO");
                        $clienteDetalleLog->setValorAnterior($estados);
                        $clienteDetalleLog->setValorNuevo($data["estado"]);
                        $detalleLogInsert = $clienteDetalleLog->insertDetalleLog();
                        if($detalleLogInsert == 0){
                            $fallas++;
                        }
                    }else{
                        $fallas++;
                    }
                }
            }else{
                $fallas++;
            }
        }else{
            $fallas++;
        }
        if($fallas > 0){
            $response["status"] = "error";
            $response["message"] = "no se pudo realizar la accion";
        }else{
            $response["status"] = "exito";
            $response["message"] = "la accion se realizo correctamente";
        }
        echo json_encode($response);
    }
}