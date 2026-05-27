<?php
session_start();

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="admin-panel">
        <div class="admin-overlay">
            <h1>💇‍♀️ Panel de administración</h1>
            <p class="admin-saludo">
                Bienvenido, <?php echo $_SESSION['nombre']; ?>
            </p>
            <div class="admin-grid">
                <a class="admin-card" href="gestionarServicios.php">
                    🛠<br>
                    Gestionar servicios
                </a>
                <a class="admin-card" href="gestionarCitas.php">
                    📅<br>
                    Gestionar citas
                </a>
            </div>
            <?php
                include("../includes/conexion.php");
                $hoy = date('Y-m-d');
            ?>

            <div class="admin-semana-box">
                <h2>📅 Agenda semanal</h2>
                <div class="admin-semana-grid">
                    <?php
                        $hoy = date('Y-m-d');

                        for($i = 0; $i < 7; $i++)
                        {
                            $fecha = date('Y-m-d', strtotime($hoy . " +$i day"));
                            $sql = "SELECT COUNT(*) as total FROM citas WHERE DATE(fecha_cita) = '$fecha'";
                            $res = $conexion->query($sql);
                            $data = $res->fetch_assoc();
                            $isToday = ($fecha == $hoy);
                            ?>

                            <div class="admin-dia-box <?php echo $isToday ? 'hoy' : ''; ?>">
                                <div class="admin-dia-fecha">
                                    <?php echo date('D d/m', strtotime($fecha)); ?>
                                </div>
                                <div class="admin-dia-numero">
                                    <?php echo $data['total']; ?> citas
                                </div>
                            </div>
                        <?php 
                        } 
                        ?>
                </div>
            </div>
            <a class="admin-logout" href="../cerrarSesion.php">
                🚪 Cerrar sesión
            </a>
        </div>
    </div>
</body>
</html>
