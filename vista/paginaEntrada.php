<?php
session_start();
include "../conexion.php";
$usuario = $_SESSION['username'];
if ($usuario == "") {
    header("Location:index.php");
} else {
    $revisar = "SELECT estado FROM usuario WHERE id_usuario = '$usuario'";
    $resultado = mysqli_query($conexionP, $revisar);
    $rol = $_SESSION['rolUs'];
    if (isset($resultado)) {
        if (mysqli_fetch_array($resultado)[0] == 1) {
            header("Location:index.php");
            mysqli_close($conexionP);
        }
    } else {
        header("Location:index.php");
    }
}
?>
<script>
    function confirmarEliminar() {
        return confirm("¿Elimnar su cuenta?");
    }
    function confirmarCerrarSesion() {
        return confirm("¿Cerrar la sesión?");
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/diseño.css">
    <title>GAIMP</title>
</head>
<body>
    <div class="contenedorPadre">
        <div class="tituloNav">
            Gestor y Administrador de Imagenología Pulmonar.<br />
            Página principal.
        </div>
        <div class="navegacion">
            <form action="../funciones.php" method="post">
                <?php if ($rol == "MG") { ?>
                    <button type="submit" name="abrirTablaPacientes">Pacientes</button>
                    <button type="submit" name="abrirTablaConsulta">Consultas</button>
                    <button type="submit" name="abrirTablaExpediente">Expedientes</button>
                    <button type="submit" name="abrirTablaOrdenEstudio">Ordenes de estudio</button>
                <?php } elseif ($rol == "ES") { ?>
                    <button type="submit" name="abrirTablaPacientes">Pacientes</button>
                    <button type="submit" name="abrirTablaOrdenEstudio">Ordenes de estudio</button>
                <?php } elseif ($rol == "MT") { ?>
                    <button type="submit" name="abrirTablaOrdenEstudio">Ordenes de estudio</button>
                <?php } ?>
                <button class="cerrarSesion" type="submit" name="cerrarSesion" onclick="return confirmarCerrarSesion()">cerrar sesion</button>
            </form>
        </div>
        <table border="1">
            <tr>
                <th>id usuario</th>
                <th>nombre</th>
                <th>apellido paterno</th>
                <th>apellido materno</th>
                <th>foto de identificación</th>
                <th>rol</th>
                <th>opciones</th>
            </tr>
            <?php
            $consulta = "SELECT * FROM usuario WHERE id_usuario = '$usuario'";
            $resultado = mysqli_query($conexionP, $consulta);
            while ($mostrar = mysqli_fetch_array($resultado)) {
            ?>
                <tr>
                    <td><?php echo $mostrar['id_usuario'] ?></td>
                    <td><?php echo $mostrar['nom_usuario'] ?></td>
                    <td><?php echo $mostrar['ape_paterno'] ?></td>
                    <td><?php echo $mostrar['ape_materno'] ?></td>
                    <td><img height="80px" src="data:image/jpg;base64,<?php echo base64_encode($mostrar['foto_identificacion']); ?>"></td>
                    <td><?php
                        $rol = $mostrar['rol'];
                        if ($rol == 'MG') {
                            echo 'Medico General';
                        } elseif ($rol == 'ES') {
                            echo 'Especialista';
                        } elseif ($rol == 'MT') {
                            echo 'Medico Tecnico';
                        } ?>
                    </td>
                    <td>
                        <form action="../funciones.php" method="post">
                            <div>
                                <button class="botonesTabla" type="submit" name="modificarUsVen">Editar Cuenta</button>
                            </div>
                            <div>
                                <button class="eliminar" type="submit" name="eliminarUsuario" onclick="return confirmarEliminar()">Eliminar Cuenta</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        <?php
        if ($rol == "MG") {
            $consulta = "SELECT * FROM medico_general WHERE id_usuario = '$usuario'";
        } elseif ($rol == "MT") {
            $consulta = "SELECT * FROM medico_tecnico WHERE id_usuario = '$usuario'";
        } elseif ($rol == "ES") {
            $consulta = "SELECT * FROM especialista WHERE id_usuario = '$usuario'";
        }
        $resultado = mysqli_fetch_array(mysqli_query($conexionP, $consulta));
        if ($rol == "MG") { ?>
            <p><?php echo "Pacientes atendidos: " . $resultado['num_pac_aten'] . " Expedientes realizados: " . $resultado['num_casos_rea']; ?></p>
        <?php } elseif ($rol == "MT") { ?>
            <p><?php echo "Radiografias realizadas: " . $resultado['num_radio'] . " Tomografias realizadas: " . $resultado['num_tomo'] . " Ultrasonidos realizados: " . $resultado['num_ultra']; ?></p>
        <?php } elseif ($rol == "ES") { ?>
            <p><?php echo "Casos recibidos: " . $resultado['num_casos_reci'] . " Diagnosticos realizados: " . $resultado['num_diag_rea']; ?></p>
        <?php } ?>
    </div>
</body>
</html>