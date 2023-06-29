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
    $id_paciente = $_POST['crearOE'];
}
?>
<script>
    function nuevaOE() {
        return confirm("¿Crear orden de estudio?");
    }
    function cancelarOE() {
        return confirm("¿Cancelar orden de estudio?");
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
            Crear orden de estudio.
        </div>
        <div class="CrOE">
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div>
                    <a>Tipo de estudio: </a>
                </div>
                <div>
                    <select name="tipoEstudio">
                        <option value="Radiografia">Radiografía</option>
                        <option value="Tomografia">Tomografía</option>
                        <option value="Ultrasonido">Ultrasonido</option>
                    </select>
                </div>
                <div>
                    <a>Especificaciones del estudio: </a>
                </div>
                <div style="margin-bottom: 20px;">
                    <textarea class="areaOE" name="especificaciones" required></textarea>
                </div>
                <div>
                    <button type="submit" name="creaOrdEs" value=<?php echo $id_paciente ?> onclick="return nuevaOE()"> Crear Orden </button>
                </div>
            </form>
            <form action="tablaExpediente.php" method="post">
                <button class="cancelar" type="submit" name="verPaciente" value=<?php echo $id_paciente ?> onclick="return cancelarOE()">Cancelar</button>
            </form>
        </div>
    </div>
</body>
</html>