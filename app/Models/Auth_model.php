<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/API_PRUEBA/core/Database.php';

class Auth_Model{

    public function getUsuario($usuario, $pwd){
        $db = Database::connect();
        $query = "SELECT ID_USUARIO, NOMBRE, CORREO, ID_PERFIL FROM USUARIOS WHERE UPPER(NOMBRE) = UPPER(:usuario) and PASSWORD = :password and STATUS_USUARIO = 'activo' LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':password', $pwd);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermisosByPerfil($idPerfil){
        $db = Database::connect();
        $query = "SELECT PRP.ID_PERMISO_PERFIL, PRP.ID_PERMISO, PER.NOMBRE_PERMISO, PER.RUTA_PERMISO, PER.ESTADO_PERMISO
        FROM PG_PERMISOS_PERFIL PRP INNER JOIN PG_PERMISOS PER ON PRP.ID_PERMISO = PER.ID_PERMISO
        WHERE PRP.ID_PERFIL = :ID_PERFIL AND PER.ESTADO_PERMISO = 'activo'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':ID_PERFIL', $idPerfil);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
