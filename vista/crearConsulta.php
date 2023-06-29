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
    $idPaciente = $_POST['crearCon'];
}
?>

<script>
    function consultaNueva() {
        return confirm("¿Crear nueva consulta?");
    }

    function cancelarCreacion() {
        return confirm("¿Cancelar nueva consulta?");
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
            Gestor y Administrador de Imagenología Pulmonar<br />
            Crear consulta.
        </div>
        <div class="CrConsulta">
            <form action="../funciones.php" method="post">

                <div>
                    <a>ID del paciente:</a>
                </div>
                <div>
                    <input type="text" name="idPaciente" value=<?php echo $idPaciente ?> readonly>
                </div>
                <div>
                    <a>ID del doctor:</a>
                </div>
                <div>
                    <input type="text" name="idDoctor" value=<?php echo $usuario ?> readonly>
                </div>
                <div>
                    <a>Ingrese sintomas observados:</a>
                </div>
                <div>
                    <textarea class="areaConsulta" name="sintomasObservados"></textarea>
                </div>
                <div>
                    <a>Ingrese sintomas descritos por el paciente:</a>
                </div>
                <div>
                    <textarea class="areaConsulta" name="sintomasAdscritos"></textarea>
                </div>
                <div>
                    <a>¿Cual es su diagnostico preliminar?</a>
                </div>
                <div>
                    <textarea class="areaConsulta" name="diagnosticoPreliminar"></textarea>
                </div>
                <div>
                    <button type="submit" name="GuardarConsulta" onclick="return consultaNueva()">guardar consulta</button>
                </div>
            </form>
            <form action="tablaPacientes.php" method="post">
                <button class="cancelar" type="submit" name="verPaciente" value=<?php echo $idPaciente ?> onclick="return cancelarCreacion()">Cancelar</button>
            </form>
        </div>
    </div>
</body>

</html>