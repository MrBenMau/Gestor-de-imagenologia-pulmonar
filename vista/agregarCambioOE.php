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
        if ($rol == "MG") {
            header("Location:index.php");
            mysqli_close($conexionP);
        }
    } else {
        header("Location:index.php");
    }
    $cadena = $_POST['creaOrdEs'];
}
?>
<script>
    function confirmarCambio() {
        return confirm("¿Está seguro que desea guardar los cambios?");
    }
    function cancelarCambio() {
        return confirm("¿Desea cancelar?");
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
            Gestor y Administrador de Imagenología Pulmonar. <br />
            Cambiar resultados. Orden de estudio.
        </div>
        <div class="CrPaciente">
            <p><?php echo $cadena ?></p>
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <?php if ($rol == "MT") { ?>
                    <div>
                        <a>Subir imagen: </a>
                        <input type="file" name="imagenResultado" multiple required>
                    </div>
                    <button type="submit" value=<?php echo $cadena ?> name="creaOrdEs" onclick="return confirmarCambio()">Agregar imagen</button>
                <?php } else if ($rol == "ES") { ?>
                    <div>
                        <a>ingresar observaciones: </a>
                        <textarea name="observaciones" rows="4" cols="50" required></textarea>
                    </div>
                    <button type="submit" value=<?php echo $cadena ?> name="creaOrdEs" onclick="return confirmarCambio()">Agregar Observacion</button>
                <?php } ?>
            </form>
            <form action="tablaOrdenEstudio.php" method="post">
                <button class="cancelar" type="submit" onclick="return cancelarCambio()">Cancelar</button>
            </form>
        </div>
    </div>
</body>
</html>