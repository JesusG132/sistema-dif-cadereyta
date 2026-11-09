<?php
// conexion.php - Archivo para establecer la conexión a la base de datos

// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
$host = "localhost";
$usuario_db = "root"; // Usuario por defecto de WAMP
$contrasena_db = ""; // Contraseña por defecto de WAMP (probablemente vacía)
$nombre_db = "sistema_dif"; 

// Crear conexión
$conexion = new mysqli($host, $usuario_db, $contrasena_db, $nombre_db);

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
$conexion->set_charset("utf8");

// NOTA DE SEGURIDAD: En un entorno real, las contraseñas no van en texto plano
?>