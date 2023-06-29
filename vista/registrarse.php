<script>
    function guardarCambios(){
        return confirm("¿Crear cuenta?");
    }
    function cancelarCambios(){
        return confirm("¿Cancelar cuenta?");
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
            Registrarse.
        </div>
        <div class="login">
            <form action="../funciones.php" method="post" enctype="multipart/form-data">
                <div>
                    <a>Nombre(s): </a>
                </div>
                <div>
                    <input type="text" name="nombre_us" placeholder="nombre" required>
                </div>
                <div>
                    <a>Crear contraseña: </a>
                </div>
                <div>
                    <input type="password" name="contraseña" placeholder="Constraseña" required>
                </div>
                <div>
                    <a>apellido paterno: </a>
                </div>
                <div>
                    <input type="text" name="apellido_pa" placeholder="apellido paterno" required>
                </div>
                <div>
                    <a>apellido materno: </a>
                </div>
                <div>
                    <input type="text" name="apellido_ma" placeholder="apellido materno" required>
                </div>
                <div>
                    <a>Correo electronico: </a>
                </div>
                <div>
                    <input type="text" name="correoUsuario" placeholder="correo electronico" required>
                </div>
                <div>
                    <a>Seleccione una foto: </a>
                </div>
                <div>
                    <input type="file" name="imagen" multiple required>
                </div>
                <div>
                    <a>Seleccione rol: </a>
                    <select name="rol">
                        <option value="0">Medico General</option>
                        <option value="1">Especialista</option>
                        <option value="2">Medico Tecnico</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="crearCuenta" onclick="return guardarCambios()">Crear Cuenta!</button>
                </div>
            </form>
            <form action="../funciones.php" method="post">
                <div>
                    <button class="cancelar" type="submit" name="viejaCuenta" onclick="return cancelarCambios()">Ya tengo una cuenta</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>