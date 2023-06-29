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
    function confirmarCerrarSesion() {
        return confirm("¿Cerrar la sesión?");
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
            Expedientes.
        </div>
        <div class="navegacion">
            <form action="../funciones.php" method="post">
                <button type="submit" name="paginaPrincipal">Pagina Principal</button>
                <button type="submit" name="abrirTablaPacientes">Pacientes</button>
                <button type="submit" name="abrirTablaConsulta" id="botonNavegacion">Consultas</button>
                <button type="submit" name="abrirTablaOrdenEstudio">Ordenes de estudio</button>
                <button class="cerrarSesion" type="submit" name="cerrarSesion" onclick="return confirmarCerrarSesion()">cerrar sesion</button>
            </form>
        </div>
        <div class="barraBusqueda">
            <form action="tablaExpediente.php" method="post">
                <div>
                    <a>Búsqueda: </a>
                    <input type="text" name="nomBus">
                    <button type="submit" name="buscar">Buscar</button>
                </div>
            </form>
        </div>
        <?php
        $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total FROM expediente");
        $rescuentaar = mysqli_fetch_array($cuenta);
        $rescuenta = $rescuentaar['total'];
        $por_pagina = 5;
        if (empty($_GET['pagina'])) {
            $pagina = 1;
        } else {
            $pagina = $_GET['pagina'];
        }
        $desde = ($pagina - 1) * $por_pagina;
        $total_paginas = ceil($rescuenta / $por_pagina);
        $consulta1 = "SELECT * FROM expediente LIMIT $desde,$por_pagina";
        if (isset($_POST['buscar'])) {
            if ($_POST['nomBus'] != "") {
                $abuscar = $_POST['nomBus'];
                $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total FROM expediente 
                                                    WHERE id_antecedente LIKE '%$abuscar%'
                                                    OR padecimiento LIKE '%$abuscar%'
                                                    OR tratamiento LIKE '%$abuscar%' ");
                $rescuentaar = mysqli_fetch_array($cuenta);
                $rescuenta = $rescuentaar['total'];
                $por_pagina = 5;
                if (empty($_GET['pagina'])) {
                    $pagina = 1;
                } else {
                    $pagina = $_GET['pagina'];
                }
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($rescuenta / $por_pagina);
                $consulta2 = "SELECT * FROM expediente 
                              WHERE id_antecedente LIKE '%$abuscar%'
                              OR padecimiento LIKE '%$abuscar%'
                              OR tratamiento LIKE '%$abuscar%'
                              LIMIT $desde,$por_pagina";
                $resultado = mysqli_query($conexionP, $consulta2);
            } else {
                $resultado = mysqli_query($conexionP, $consulta1);
            }
        } else {
            $resultado = mysqli_query($conexionP, $consulta1);
        }
        mysqli_close($conexionP);?>
        <form action="tablaExpediente.php" method="post">
            <div>
                <?php if (isset($abuscar)) { ?>
                    <a>Usted busco:<?php print($abuscar); ?></a>
                    <button type="submit" name="borrar">X</button>
                <?php } ?>
            </div>
        </form>
        <?php
        if (isset($_POST['borrar'])) {
            $consulta = "SELECT * FROM expediente";
        } ?>
        <table border="1">
            <tr>
                <th>Id antecedente</th>
                <th class="thModi">Padecimiento</th>
                <th class="thModi">Tratamiento</th>
                <th class="thModi">Opciones</th>
            </tr>
            <?php
            while ($mostrar = mysqli_fetch_array($resultado)) { ?>
                <tr>
                    <td><?php echo $mostrar['id_antecedente'] ?></td>
                    <td class="tdModi">
                        <div class="tdDiv">
                            <?php echo $mostrar['padecimiento'] ?>
                        </div>
                    </td>
                    <td class="tdModi">
                        <div class="tdDiv">
                            <?php echo $mostrar['tratamiento'] ?>
                        </div>
                    </td>
                    <td>
                        <form action="editarExpediente.php" method="post">
                            <button class="botonesTabla2 color3" type="submit" name="editarExpediente" value=<?php echo $mostrar['id_antecedente']?>>Editar expediente</button>
                        </form>
                        <form action="crearOrdenEstudio.php" method="post">
                            <button class="botonesTabla2 color1" type="submit" name="crearOE" value=<?php echo $mostrar['id_antecedente']?>>Crear orden de estudio</button>
                        </form>
                    </td>
                </tr>
            <?php }  ?>
        </table>
        <nav class="paginador">
            <ul>
                <?php
                if ($pagina != 1) {
                ?>
                    <li><a href="?pagina=<?php echo 1 ?>">|< </a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                            << </a>
                    </li>
                <?php
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li>[' . $i . ']</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($pagina != $total_paginas) {
                ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
                    <li><a href="?pagina=<?php echo $total_paginas ?>">>|</a></li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </div>
</body>
</html>