<?php
session_start();
include "../conexion.php";
$usuario = $_SESSION['username'];
$revisar = "SELECT estado FROM usuario WHERE id_usuario = '$usuario'";
$resultado = mysqli_query($conexionP, $revisar);
$rol = $_SESSION['rolUs'];

if ($usuario == "") {
    header("Location:index.php");
} else {
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
}
?>
<script>
    function crearUsuario() {
        return confirm("¿Crear nuevo paciente?");
    }
    function cancelarUsuario() {
        return confirm("¿Cancelar nuevo paciente?");
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
            Crear paciente.
        </div>
        <div class="CrPaciente">
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div>
                    <a>Ingresar nombre del paciente: </a>
                    <input type="text" name="nombpac" placeholder="nombre del paciente" required>
                </div>
                <div>
                    <a>ingresar apellido paterno del paciente: </a>
                    <input type="text" name="apellidopa" placeholder="apellido paterno del paciente" required>
                </div>
                <div>
                    <a>ingresar apellido materno del paciente: </a>
                    <input type="text" name="apellidoma" placeholder="apellido materno del paciente" required>
                </div>
                <div>
                    <a>Subir foto identicacion del paciente: </a>
                    <input type="file" name="imagenpac" multiple>
                </div>
                <div>
                    <a>Seleccione turno del paciente: </a>
                    <select name="turno">
                        <option value="matutino">Matutino</option>
                        <option value="vespertino">Vespertino</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="crearPacienteB" onclick="return crearUsuario()">Crear</button>
                </div>
            </form>
            <form action="../funciones.php" method="post">
                <button class="cancelar" type="submit" name="abrirTablaPacientes" onclick="return cancelarUsuario()">Cancelar</button>
            </form>
        </div>
    </div>
</body>
</html>