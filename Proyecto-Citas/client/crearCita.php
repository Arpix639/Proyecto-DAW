<?php
session_start();
include("../includes/conexion.php");

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'cliente'){
    header("Location: ../index.php");
    exit();
}

// Crear cita
if(isset($_POST['reservar'])){

    $usuario_id = $_SESSION['id_usuario'];
    $servicio_id = $_POST['servicio_id'];
    $fecha_cita = $_POST['fecha_cita'];

    // comprobar si ya existe cita en esa misma fecha/hora
    $sql_check = "SELECT * FROM citas WHERE fecha_cita = '$fecha_cita' AND estado != 'cancelada'";

    $result_check = $conexion->query($sql_check);

    if($result_check->num_rows > 0){
        $_SESSION['mensaje'] = "❌ Ya existe una cita en esa fecha y hora";
        $_SESSION['tipo'] = "error";

        header("Location: crear_cita.php");
        exit();
    }

    // Reservar a partir de mañana
    $fecha_minima = date('Y-m-d\TH:i');

    if($fecha_cita <= $fecha_minima){
        $_SESSION['mensaje'] = "❌ Solo puedes reservar a partir de mañana";
        $_SESSION['tipo'] = "error";

        header("Location: crear_cita.php");
        exit();
    }

    $sql = "INSERT INTO citas (usuario_id, servicio_id, fecha_cita) VALUES ('$usuario_id', '$servicio_id', '$fecha_cita')";

    if($conexion->query($sql))
    {
        $_SESSION['mensaje'] = "✔ Operación realizada correctamente";
        $_SESSION['tipo'] = "success";
        header("Location: misCitas.php");
        exit();
    }
    else
    {
        $_SESSION['mensaje'] = "Error al crear la cita";
        $_SESSION['tipo'] = "error";
        header("Location: crear_cita.php");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cita</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="crear-cita-wrapper">
        
        <div class="crear-cita-box">
            <a class="volver-btn" href="panelCliente.php">⬅ Volver</a>
            <h1>Reservar cita ✂</h1>

            <p>Selecciona servicio y fecha</p>

            

            <form method="POST">
                <label>Servicio:</label>
                <select name="servicio_id" required>

                <?php
                $sql = "SELECT * FROM servicios";
                $result = $conexion->query($sql);

                while($servicio = $result->fetch_assoc()){
                    echo "<option value='".$servicio['id']."'>";
                    echo $servicio['nombre']." - ".$servicio['precio']."€";
                    echo "</option>";
                }
                ?>
                </select>
                <label>Fecha y hora:</label>
                <input type="datetime-local" name="fecha_cita" required min="<?php echo date('Y-m-d\T00:00', strtotime('+1 day')); ?>">

                <button id="reserva-button" type="submit" name="reservar">Reserva</button>
            </form>
        </div>
    </div>
</body>
</html>
