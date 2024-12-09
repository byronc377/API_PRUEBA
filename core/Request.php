<?php

class Request {
    public function getPath(): string {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Debug para verificar qué ruta está interpretando
    error_log("Request Path: " . $path);

    // Remover "index.php" de la ruta si está presente
    $path = str_replace('/index.php', '', $path);
    $path = str_replace('/API_PRUEBA', '', $path);
    

    // Devolver la ruta limpia
    return rtrim($path, '/') ?: '/';
}

    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }
}
