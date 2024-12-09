<?php
require_once dirname(__DIR__) . '/Models/LogModificacion_model.php';
require_once dirname(__DIR__) . '/Models/LogDetalle_model.php';

class LogController{
    public function consultarLog(){
        $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
        $response =[];
        if(!empty($data)){
            if(isset($data["idCliente"]) && !empty($data["idCliente"])){
                $log = new LogDetalle_model();
                $logCliente = $log->getLogByCliente($data["idCliente"]);
                if(!empty($logCliente)){
                    $response["result"] = "success";
                    $response["message"] = "datos encontrados";
                    $response["datos"] = json_encode($logCliente);
                }else{
                    $response["result"] = "error";
                    $response["message"] = "no se encontraron datos";
                    $response["datos"] = [];
                }
            }else{
                $response["result"] = "error";
                $response["message"] = "solicitud incompleta";
                $response["datos"] = [];
            }
        }else{
            $response["result"] = "error";
            $response["message"] = "solicitud vacia";
            $response["datos"] = [];
        }
        echo json_encode($response);
    }
}