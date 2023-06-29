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
    $imagen = $_POST['img'];

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
        mysqli_close($conexionP);
        header("Location:index.php");
    }
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
            Imagen.
        </div>
        <div class="imgen">
            <form action="../funciones.php" method="post">
                <button class="color2" type="submit" name="abrirTablaOrdenEstudio">Cerrar Imagen</button>
            </form>
            <div class="imagen">
                <img height="900px" width="900px" src="data:image/jpg;base64,<?php echo $imagen ?>">
            </div>
            <form action="../funciones.php" method="post">
                <button class="color2" type="submit" name="abrirTablaOrdenEstudio">Cerrar Imagen</button>
            </form>
        </div>
    </div>
</body>

</html>