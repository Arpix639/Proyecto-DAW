<?php

session_start();
include("includes/conexion.php");

if(isset($_POST['registrar'])){

    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $nombre = $_POST['nombre_completo'];

    $sql = "INSERT INTO usuarios (nombre_completo, correo, contraseña)
            VALUES ('$nombre', '$correo', '$contrasena')";

    if($conexion->query($sql)){
        $_SESSION['mensaje'] = "✔ Usuario Registrado Correctamente. Inicie Sesión ✔";
        $_SESSION['tipo'] = "success";
        header("Location: inicioSesion.php");
        exit();
    }else{
        echo "Error: " . $conexion->error;
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo">
    <button class="volver"><a href="index.php">⬅</a></button>
    <div class="auth-container">

    <div class="auth-card">
        
        <h2>Registrarse</h2>

        <form action="" method="POST">

        <label>Nombre completo:</label>
        <input type="text" name="nombre_completo" required><br><br>

        <label>Correo:</label>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit" name="registrar">Registrarse</button>

    </form>

    </div>

    </div>

    

</body>
</html>