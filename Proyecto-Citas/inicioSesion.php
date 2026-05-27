<?php

session_start();
include("includes/conexion.php");

if(isset($_POST['entrar'])){

    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $resultado = $conexion->query($sql);

    if($resultado->num_rows > 0){

        $usuario = $resultado->fetch_assoc();

        if(password_verify($contrasena, $usuario['contraseña'])){

            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['nombre'] = $usuario['nombre_completo'];

            if($usuario['rol'] == 'admin')
            {
                header("Location: admin/panelAdmin.php");
            }
            else
            {
                header("Location: client/panelCliente.php");
            }
            exit();

        }else{
            $_SESSION['mensaje'] = "Contraseña Incorrecta";
            $_SESSION['tipo'] = "warning";
        }

    }else{
        $_SESSION['mensaje'] = "Usuario no encontrado";
        $_SESSION['tipo'] = "warning";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo">

    <?php if(isset($_SESSION['mensaje'])) { ?>
    <div id="toast" class="toast <?php echo $_SESSION['tipo'] ?? 'success'; ?>">
        <?php echo $_SESSION['mensaje']; ?>
    </div>
    <script>
        setTimeout(function(){
            let toast = document.getElementById("toast");
            if(toast){
                toast.classList.add("hide");
            }
        }, 2000);
    </script>
    <?php
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo']);
    ?>
    <?php } ?>
    <button class="volver"><a href="index.php">⬅</a></button>
    
    <div class="auth-container">
        <div class="auth-card">
            <h2>Iniciar sesión</h2>
            <form action="" method="POST">
                <label>Correo:</label>
                <input type="email" name="correo" required><br><br>

                <label>Contraseña:</label>
                <input type="password" name="contrasena" required><br><br>

                <button type="submit" name="entrar">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>