<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location:../vista/paginaEntrada.php");
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
            Login.
        </div>
        <div class="login">
            <div class="tituloLogin">LOGIN</div>
            <form action="../funciones.php" method="post">
                <div class="loginItem">
                    <input type="text" name="correoU" placeholder="correo electronico" required>
                </div>
                <div class="loginItem">
                    <input type="password" name="contraseña" placeholder="Constraseña" required>
                </div>
                <div class="loginItem">
                    <button type="submit" name="comprobarUsuario">Entrar</button>
                </div>
            </form>
            <form action="../funciones.php" method="post">
                <div class="loginItem">
                    <p>¿No tienes cuenta? Registrate ya!</p>
                </div>
                <div class="loginItem">
                    <button type="submit" name="registrarse">Registrarse</button>
                </div>
            </form>
        </div>
        <div class="contenedorPadre">
</body>

</html>