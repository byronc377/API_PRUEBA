<?php
require_once dirname(__DIR__) . '/Models/Cliente_model.php';
    class ClienteController{
        public function insertNuevoCliente(){
            $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
            if(!empty($data)){
                if (isset($data['nombreCliente'], $data['fechaNacimiento'], $data['usuarioCreador'])) {
                    $cliente = new Cliente_model();
                    $cliente->setNombreCliente($data['nombreCliente']);
                    $cliente->setFechaNacimiento($data['fechaNacimiento']);
                    $cliente->setUsuarioCreador($data['usuarioCreador']);
                    $clienteInsert = $cliente->insertNuevoCliente();
                    if($clienteInsert > 0){
                        echo json_encode(["status"=>"exito", "message"=>"nuevo cliente creado", "numeroCliente"=>$clienteInsert]);
                    }
                }else{
                    echo json_encode(["status"=>"fracaso", "message"=>"datos faltantes", "numeroCliente"=>0]);
                }
            }else{
                echo json_encode(["status"=>"fracaso", "message"=>"solicitud vacia", "numeroCliente"=>0]);
            }
        }

        public function getCliente(){
            $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
            if(!empty($data)){
                if(isset($data["idCliente"])){
                    $clienteModel = new Cliente_model();
                    $cliente = $clienteModel->getClienteById($data["idCliente"]);
                    $detalleCliente = $clienteModel->getDetalleClienteById($data["idCliente"]);
                    echo json_encode(["result"=>"success","message"=>"busqueda Exitosa", "cliente"=>$cliente, "detalleCliente"=>$detalleCliente]);
                }else{
                    echo json_encode(["result"=>"error","message"=>"error en la busqueda", "cliente"=>[], "detalleCliente"=>[]]);
                }
            }else{
                echo json_encode(["result"=>"error","message"=>"error en la busqueda", "cliente"=>[], "detalleCliente"=>[]]);
            }
        }

        public function getClienteByFechaCreacion(){
            $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
            if(!empty($data)){
                if(isset($data["fechaInicio"], $data["fechaFin"])  && !empty($data["fechaInicio"]) && !empty($data["fechaFin"])){
                    $clienteModel = new Cliente_model();
                    $cliente = $clienteModel->getClientesByFecha($data["fechaInicio"], $data["fechaFin"]);
                    echo json_encode(["result"=>"success","message"=>"busqueda Exitosa", "cliente"=>$cliente]);
                }else{
                    echo json_encode(["result"=>"error","message"=>"error en la busqueda", "cliente"=>[]]);
                }
            }else{
                echo json_encode(["result"=>"error","message"=>"error en la busqueda", "cliente"=>[]]);
            }
        }
    }
?>