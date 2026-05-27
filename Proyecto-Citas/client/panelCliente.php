<?php
session_start();

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'cliente'){
    header("Location: ../index.php");
    exit();
}
include("../includes/conexion.php");

$usuario_id = $_SESSION['id_usuario'];

// lunes de la semana actual
$hoy = date('Y-m-d');
$diaSemana = date('N'); // 1 (lunes) - 7 (domingo)

$inicioSemana = date('Y-m-d', strtotime($hoy . ' -'.($diaSemana-1).' days'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="cliente-panel">

    <div class="cliente-overlay">
        <div class="cliente-botones" id="cerrarSesionCliente"><a href="../cerrarSesion.php">🚪 Cerrar sesión</a></div>

        <h1>Glow Hair Studio ✂</h1>

        <p class="saludo">
            Bienvenido, <?php echo $_SESSION['nombre']; ?> 
        </p>

        <p class="subtexto">
            Gestiona tus citas fácilmente
        </p>

        <div class="cliente-botones">
            <a href="crearCita.php">➕ Reservar cita</a>
            <a href="misCitas.php">📅 Mis citas</a>
        </div>

        <!-- CALENDARIO SEMANAL -->
        <div class="semana-box">
            <h3>Tu semana</h3>
            <div class="semana-grid">
                <?php
                    for($i = 0; $i < 7; $i++)
                    {
                        $fecha = date('Y-m-d', strtotime($inicioSemana . " +$i days"));
                        $nombreDia = date('D', strtotime($fecha));

                        // buscar citas de ese día
                        $sql = "SELECT * FROM citas WHERE usuario_id='$usuario_id' AND DATE(fecha_cita)='$fecha' AND estado != 'cancelada'";

                        $res = $conexion->query($sql);

                        $numCitas = $res->num_rows;
                ?>
                <div class="dia-box">
                    <strong><?php echo $nombreDia; ?></strong><br>
                    <small><?php echo $fecha; ?></small>
                    <div class="citas-info">
                        <?php if($numCitas > 0){ ?>
                        <span class="con-cita"><?php echo $numCitas; ?> cita(s)</span>
                        <?php } else { ?>
                        <span class="sin-cita">Sin citas</span>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="servicios-box">
            <h3>Servicios disponibles</h3>

            <div class="servicios-grid">

                <div class="servicio-card">✂ Corte</div>

                <div class="servicio-card">💇 Peinado</div>

                <div class="servicio-card">🎨 Coloración</div>

                <div class="servicio-card">💆 Tratamientos</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
