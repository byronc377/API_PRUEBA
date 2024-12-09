<?php
require_once dirname(__DIR__) . '/Models/Auth_model.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


class AuthController {
    public function login() {
        // Accede a los datos enviados por POST
        $data = (json_decode(file_get_contents('php://input'), true)) ?? [];
        if(!empty($data)){
            if (isset($data['username'], $data['password'])) {
                $authModel = new Auth_model();
                $logVal = $authModel->getUsuario($data['username'], $data['password']);
                // Lógica para manejar login
                if (!empty($logVal)) {
                    $permisos = ($authModel->getPermisosByPerfil($logVal[0]["ID_PERFIL"])) ?? [];
                    echo json_encode(['success' => true, 'message' => 'Login exitoso',  "usuario"=>json_encode($logVal), "permisos"=>json_encode($permisos)]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas', "usuario"=>[], 'permisos'=>[]]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos', "usuario"=>[], 'permisos'=>[]]);
            }
        }else{
            echo json_encode(['success' => false, 'message' => 'peticion vacia', "usuario"=>[], 'permisos'=>[]]);
        }
    }
}
