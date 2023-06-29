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
    $id_paciemte = "";
    $consulta = "SELECT * FROM usuario WHERE id_usuario = '$usuario'";
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
<html lang="en"><?php
                include "../conexion.php";
                $usuario = $_SESSION['username'];
                $revisar = "SELECT * FROM usuario WHERE id_usuario = '$usuario'";
                $resultado = mysqli_fetch_array(mysqli_query($conexionP, $revisar));
                $rol = $_SESSION['rolUs'];

                if (isset($resultado)) {
                    if ($resultado['estado'] == 1) {
                        header("Location:index.php");
                        mysqli_close($conexionP);
                    }
                    if ($rol == "MT") {
                        header("Location:index.php");
                        mysqli_close($conexionP);
                    }
                } else {
                    mysqli_close($conexionP);
                    header("Location:index.php");
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
            Modificar usuario.
        </div>
        <div class="CrPaciente">
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div>
                    <a>Nombre(s): </a>
                </div>
                <div>
                    <input type="text" name="nombreUs" placeholder=<?php echo $resultado['nom_usuario'] ?>>
                </div>
                <div>
                    <a>modificar contraseña: </a>
                </div>
                <div>
                    <input type="text" name="contraseña" placeholder=<?php echo $resultado['contraseña'] ?>>
                </div>
                <div>
                    <a>apellido paterno: </a>
                </div>
                <div>
                    <input type="text" name="apellidoPaUs" placeholder=<?php echo $resultado['ape_paterno'] ?>>
                </div>
                <div>
                    <a>apellido materno: </a>
                </div>
                <div>
                    <input type="text" name="apellidoMaUs" placeholder=<?php echo $resultado['ape_materno'] ?>>
                </div>
                <div>
                    <a>Correo electronico: </a>
                </div>
                <div>
                    <input type="text" name="correoUs" placeholder=<?php echo $resultado['correo_usuario'] ?>>
                </div>
                <div>
                    <a>Seleccione una foto: </a>
                </div>
                <div>
                    <input type="file" name="imagen" multiple>
                </div>
                <div>
                    <button type="submit" name="modificarUs" onclick="return guardarCambios()">Guardar Cambios</button>
                </div>
                <div>
                    <button class="cancelar" type="submit" name="paginaPrincipal" onclick="return cancelarCambios()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>