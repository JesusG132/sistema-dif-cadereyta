<?php
// validar_login.php - Lógica para autenticar al usuario
session_start();
include 'conexion.php';

if (isset($_POST['entrar'])) {
    // Limpiamos espacios accidentales
    $usuario = trim($_POST['usuario']);
    $contrasena_plana = trim($_POST['contrasena']);

    $sql = "SELECT id_usuario, usuario, contraseña, rol FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();
            $hash_almacenado = $fila['contraseña']; 

            // Comparación en texto plano
            if ($contrasena_plana === $hash_almacenado) {
                $_SESSION['id_usuario'] = $fila['id_usuario'];
                $_SESSION['usuario'] = $fila['usuario'];
                $_SESSION['rol'] = $fila['rol'];

                header("Location: panel.php");
                exit();
            } else {
                $_SESSION['login_error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['login_error'] = "El usuario no existe.";
        }
        $stmt->close();
    } else {
        $_SESSION['login_error'] = "Error en la consulta a la base de datos.";
    }

    // REDIRECCIÓN A index.php (AQUÍ ESTABA EL DETALLE)
    header("Location: index.php");
    exit();
}

$conexion->close();
?>