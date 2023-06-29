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
        if ($rol == "MT" ||  $rol == "ES") {
            header("Location:index.php");
            mysqli_close($conexionP);
        }
    } else {
        header("Location:index.php");
    }
    $idPaciente = $_POST['crearAntecedentes'];
}
?>

<script>
    function nuevoExpediente() {
        return confirm("¿Crear nuevo expediente?");
    }

    function cancelarExpediente() {
        return confirm("¿Cancelar nuevo expediente?");
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
            Crear expediente.
        </div>
        <div class="CrConsulta">
            <form action="../funciones.php" method="post">
                <div>
                    <a>ID del paciente:</a>
                </div>
                <div style="margin-bottom: 20px;">
                    <input type="text" name="idPaciente" value=<?php echo $idPaciente ?> readonly>
                </div>
                <div>
                    <a>Ingrese Padecimiento:</a>

                </div>
                <div style="margin-bottom: 20px;">
                    <textarea class="areaConsulta" name="padecimiento"></textarea>
                </div>
                <div>
                    <a>Ingrese tratamiento:</a>

                </div>
                <div style="margin-bottom: 20px;">
                    <textarea class="areaConsulta" name="tratamiento"></textarea>
                </div>
                <div>
                    <button type="submit" name="GuardarExpediente" onclick="return nuevoExpediente()">guardar expediente</button>
                </div>
            </form>
            <form action="../funciones.php" method="post">
                <button class="cancelar" type="submit" name="abrirTablaPacientes" onclick="return cancelarExpediente()">Cancelar</button>
            </form>
        </div>
    </div>
</body>

</html>