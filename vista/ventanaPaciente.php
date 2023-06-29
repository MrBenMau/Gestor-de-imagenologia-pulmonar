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
        if ($rol == "MT") {
            header("Location:index.php");
            mysqli_close($conexionP);
        }
    } else {
        header("Location:index.php");
    }
    $id_paciemte = $_POST['verPaciente'];
}
?>
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
            Paciente.
        </div>
        <?php
        $consulta = "SELECT * FROM paciente WHERE id_paciente = '$id_paciemte'";
        $nombrepac = mysqli_fetch_array(mysqli_query($conexionP, $consulta));
        mysqli_close($conexionP);
        ?>
        <table class="tablaIdPac" border="1">
            <tr>
                <th><?php echo $nombrepac['id_paciente'] ?></th>
            </tr>
            <tr>
                <td><img height="80px" src="data:image/jpg;base64,<?php echo base64_encode($nombrepac['foto_ident_pac']); ?>"></td>
            </tr>
            <tr>
                <td>Nombre: <?php echo $nombrepac['nomb_pac'] ?></td>
            </tr>
            <tr>
                <td>Apellido paterno: <?php echo $nombrepac['ape_pa_pac'] ?></td>
            </tr>
            <tr>
                <td>Apellido materno: <?php echo $nombrepac['ape_ma_pac'] ?></td>
            </tr>
            <tr>
                <td>Turno: <?php echo $nombrepac['turno_aten'] ?></td>
            </tr>
            <tr>
                <td>Estado: <?php
                            if ($nombrepac['estado_paciente'] == 0) {
                                echo "activo";
                            } else {
                                echo "inactivo";
                            }
                            ?></td>
            </tr>
        </table>
        <?php if ($rol == "MG") { ?>
            <div class="navegacion">
                <form action="editarPaciente.php" method="post">
                    <button type="submit" value=<?php echo $id_paciemte; ?> name="venEditarPa">Editar</button>
                </form>
            </div>
            <div class="navegacion">
                <form action="../funciones.php" method="post">
                    <button type="submit" value=<?php echo $id_paciemte; ?> name="crearCred">Crear credencial</button>
                </form>
            </div>
        <?php } ?>
        <form action="tablaPacientes.php">
            <button class="color2" type="submit">Volver</button>
        </form>
    </div>
</body>
</html>