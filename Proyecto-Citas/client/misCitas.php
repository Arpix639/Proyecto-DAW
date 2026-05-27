<?php
    session_start();
    include("../includes/conexion.php");

    if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'cliente'){
        header("Location: ../index.php");
        exit();
    }

    // Cancelar Citas
    if(isset($_GET['cancelar']))
    {

        $id = $_GET['cancelar'];
        $usuario_id = $_SESSION['id_usuario'];

        $sql = "UPDATE citas 
                SET estado='cancelada' 
                WHERE id='$id' AND usuario_id='$usuario_id'";

        $conexion->query($sql);

        $_SESSION['mensaje'] = "✔ Cita cancelada correctamente";

        header("Location: misCitas.php");
        exit();
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="citas-fondo">
    <button class="volver"><a href="panelCliente.php">⬅</a></button><br><br>
    
    <h1>Mis citas</h1>

    <?php
        // Llamamos a las citas del usuario
    
        $usuario_id = $_SESSION['id_usuario'];

        $sql = "
        SELECT citas.*, servicios.nombre, servicios.precio, servicios.duracion
        FROM citas
        INNER JOIN servicios ON citas.servicio_id = servicios.id
        WHERE citas.usuario_id = $usuario_id
        ORDER BY citas.fecha_cita ASC
        ";

        $resultado = $conexion->query($sql);

        if($resultado->num_rows > 0)
        {
            echo "<div class='citas-grid'>";

            while($fila = $resultado->fetch_assoc())
            {
                $estado = $fila['estado'];

                echo "<div class='cita-card $estado'>";

                echo "<h3>✂ " . $fila['nombre'] . "</h3>";

                echo "<p>📅 " . $fila['fecha_cita'] . "</p>";
                echo "<p>⏱ " . $fila['duracion'] . " min</p>";
                echo "<p>💶 " . $fila['precio'] . " €</p>";

                echo "<p class='estado'>Estado: " . ucfirst($estado) . "</p>";

                if($estado != 'cancelada')
                {
                    echo "<a href='#' onclick=\"abrirModal(¿Cancelar esta cita?', 'misCitas.php?cancelar=".$fila['id']."')\">❌ Cancelar</a>";
                }

                echo "</div>";
            }

            echo "</div>";

        }
        else
        {
            echo "No tienes citas todavía";
        }

        include("../includes/modal.php");
    ?>

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

    <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo']); ?>

    <?php } ?>
</body>
</html>

