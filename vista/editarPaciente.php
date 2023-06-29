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
    $id_paciente = $_POST['venEditarPa'];

    $consulta = "SELECT * FROM paciente WHERE id_paciente = '$id_paciente'";
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
            Editar paciente.
        </div>
        <div class="CrPaciente">
            <p>
                Llene unicamente los campos que desee cambiar <?php echo $id_paciente ?>:
            </p>
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="text" name="idPaciente" value=<?php echo $id_paciente ?> readonly>
                </div>
                <div>
                    <a>Nombre(s): </a>
                    <input type="text" name="nombreP" placeholder=<?php echo $resultado['nomb_pac'] ?>>
                </div>
                <div>
                    <a>apellido paterno: </a>
                    <input type="text" name="apellidoPaP" placeholder=<?php echo $resultado['ape_pa_pac'] ?>>
                </div>
                <div>
                    <a>apellido materno: </a>
                    <input type="text" name="apellidoMaP" placeholder=<?php echo $resultado['ape_ma_pac'] ?>>
                </div>
                <div>
                    <a>Seleccionar turno: </a>
                    <select name="turnoP">
                        <option value="matutino">Matutino</option>
                        <option value="vespertino">Vespertino</option>
                    </select>
                </div>
                <div>
                    <a>Seleccione una foto: </a>
                    <input type="file" name="imagen" multiple>
                </div>
                <div>
                    <button type="submit" name="modificarPas" onclick="return guardarCambios()">Guardar Cambios</button>
                </div>
                <div>
                    <button class="cancelar" type="submit" name="abrirTablaPacientes" onclick="return cancelarCambios()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>