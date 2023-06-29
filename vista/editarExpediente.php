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
        if ($rol == "MT" || $rol == "ES") {
            header("Location:index.php");
            mysqli_close($conexionP);
        }
    } else {
        header("Location:index.php");
    }
    $idExpediente = $_POST['editarExpediente'];

    $consulta = "SELECT * FROM expediente WHERE id_antecedente = '$idExpediente'";
    $resultado = mysqli_fetch_array(mysqli_query($conexionP, $consulta));
}
?>

<script>
    function guardarCambios() {
        return confirm("¿Guardar cambios?");
    }

    function cancelarCambios() {
        return confirm("¿Cancelar cambios?");
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
            Editar expediente.
        </div>
        <div class="CrConsulta">
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    ID Expediente: <?php echo $resultado[0] ?>
                </div>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <a>Modifique el padecimiento:</a>
                </div>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <textarea class="areaConsulta" name="padecimiento"><?php echo $resultado[1] ?></textarea>
                </div>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <a>Modifique el tratamiento:</a>
                </div>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <textarea class="areaConsulta" name="tratamiento"><?php echo $resultado[2] ?></textarea>
                </div>
                <div>
                    <button type="submit" name="editAntecedente" value=<?php echo $idExpediente ?> onclick="return guardarCambios()">Guardar cambios</button>
                </div>
            </form>
            <form action="tablaExpediente.php" method="post">
                <button class="cancelar" type="submit" onclick="return cancelarCambios()">Cancelar</button>
            </form>
        </div>

    </div>
</body>

</html>