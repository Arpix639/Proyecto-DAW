<?php
session_start();
include("../includes/conexion.php");

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

// Crear servicio
if(isset($_POST['crear'])){

    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO servicios (nombre, duracion, precio)
            VALUES ('$nombre', '$duracion', '$precio')";

    if($conexion->query($sql)){
        $_SESSION['mensaje'] = "✔ Servicio creado correctamente";
    }else{
        $_SESSION['mensaje'] = "Error al crear servicio";
    }
}

// Eliminar servicio
if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    $sql = "DELETE FROM servicios WHERE id='$id'";

    if($conexion->query($sql)){
        $_SESSION['mensaje'] = "✔ Servicio eliminado correctamente";
        header("Location: gestionarServicios.php");
        exit();
    }else{
        $_SESSION['mensaje'] = "Error al eliminar servicio";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Servicios</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="fondo-grad">

    <div class="admin-panel">
        <div class="admin-overlay">

            <h1>🛠 Gestión de servicios</h1>

            <button><a class="admin-back" href="panelAdmin.php">⬅ Volver al inicio</a></button>

            <div class="servicios-layout">

                <!-- FORMULARIO -->
                <div class="servicio-form-card">

                    <h3>Crear servicio</h3>

                    <form method="POST">

                        <label>Nombre:</label>
                        <input type="text" name="nombre" required>

                        <label>Duración (minutos):</label>
                        <input type="number" name="duracion" required>

                        <label>Precio:</label>
                        <input type="number" step="0.01" name="precio" required>

                        <button type="submit" name="crear">Crear servicio</button>

                    </form>

                </div>

                <!-- LISTADO -->
                <div class="servicios-list">

                    <h3>Servicios registrados</h3>
                    <?php
                    $sql = "SELECT * FROM servicios";
                    $resultado = $conexion->query($sql);

                    if($resultado->num_rows > 0){

                        while($fila = $resultado->fetch_assoc()){
                    ?>

                    <div class="servicio-card-admin">
                        <div class="servicio-info">
                            <b><?php echo $fila['nombre']; ?></b>
                            <p><?php echo $fila['duracion']; ?> minutos</p>
                            <p><?php echo $fila['precio']; ?> €</p>
                        </div>
                        <a class="btn-delete"
                        href="#"
                        onclick="abrirModal('¿Eliminar este servicio?', 'gestionarServicios.php?eliminar=<?php echo $fila['id']; ?>')">
                            Eliminar
                        </a>
                    </div>

                    <?php
                        }
                    } else {
                        echo "<p>No hay servicios registrados</p>";
                    }
                    ?>
                </div>
            </div>
            <?php include("../includes/modal.php"); ?>
            <?php if(isset($_SESSION['mensaje'])) { ?>
            <div id="toast" class="toast success">
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
            <?php unset($_SESSION['mensaje']); } ?>
        </div>
    </div>
</body>
</html>