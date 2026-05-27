<?php
session_start();
include("../includes/conexion.php");

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

// Aplicar el estado seleccionado a la cita
if(isset($_GET['estado']) && isset($_GET['id']))
{
    $estado = $_GET['estado'];
    $id = $_GET['id'];

    $sql = "UPDATE citas SET estado='$estado' WHERE id='$id'";
    $conexion->query($sql);

    if($estado == "confirmada"){
        $_SESSION['mensaje'] = "✔ Cita confirmada correctamente";
        $_SESSION['tipo'] = "success";
    }

    if($estado == "cancelada"){
        $_SESSION['mensaje'] = "❌ Cita cancelada correctamente";
        $_SESSION['tipo'] = "error";
    }

    header("Location: gestionarCitas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Citas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="fondo-grad">

    <button class="volver"><a href="panelAdmin.php">⬅</a></button><br><br>

    <h1>Gestión de citas</h1>

    <section class="filtros">
        <div class="filter-bar" onclick="toggleFiltros()">Filtros</div>

        <div id="filtros" class="filtro-form oculto">

            <form method="GET">

                <input type="date" name="fecha" value="<?php echo $_GET['fecha'] ?? ''; ?>">

                <input type="text" name="cliente" placeholder="Nombre cliente"
                       value="<?php echo $_GET['cliente'] ?? ''; ?>">

                <button type="submit">Aplicar</button>
                <button><a href="gestionarCitas.php">Reset</a></button>
            </form>

        </div>
    </section>
    
    <div class="citas-container">
        <?php
        $where = [];

        if(!empty($_GET['fecha'])){
            $fecha = $_GET['fecha'];
            $where[] = "DATE(fecha_cita) = '$fecha'";
        }

        if(!empty($_GET['cliente'])){
            $cliente = $_GET['cliente'];
            $where[] = "usuarios.nombre_completo LIKE '%$cliente%'";
        }

        $sql = "SELECT citas.*, usuarios.nombre_completo, servicios.nombre AS servicio FROM citas
            INNER JOIN usuarios ON citas.usuario_id = usuarios.id
            INNER JOIN servicios ON citas.servicio_id = servicios.id";

        if(count($where) > 0){
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY citas.fecha_cita ASC";

        $resultado = $conexion->query($sql);

        if($resultado->num_rows > 0)
        {
            while($fila = $resultado->fetch_assoc())
            {
                ?>
                <div class="cita-admin-card <?php echo $fila['estado']; ?>">

                    <p><b>Cliente:</b> <?php echo $fila['nombre_completo']; ?></p>
                    <p><b>Servicio:</b> <?php echo $fila['servicio']; ?></p>
                    <p><b>Fecha:</b> <?php echo $fila['fecha_cita']; ?></p>
                    <p><b>Estado:</b> <?php echo $fila['estado']; ?></p>

                    <?php if($fila['estado'] == 'pendiente'){ ?>
                    <div class="admin-actions">
                        <a href="#" onclick="abrirModal('¿Confirmar cita?', 'gestionarCitas.php?estado=confirmada&id=<?php echo $fila['id']; ?>')">✔ Confirmar</a>
                        <a href="#" onclick="abrirModal('¿Cancelar cita?', 'gestionarCitas.php?estado=cancelada&id=<?php echo $fila['id']; ?>')">❌ Cancelar</a>
                    </div>
                    <?php } ?>
                </div>
                <?php
            }
        }
        else
        {
            echo "<p>No hay citas</p>";
        }

        include("../includes/modal.php");
        ?>
    </div>
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

    <script>
        // Script para la barra de los filtros
        function toggleFiltros()
        {
            let filtros = document.getElementById("filtros");
            filtros.classList.toggle("oculto");
        }
    </script>
</body>
</html>
