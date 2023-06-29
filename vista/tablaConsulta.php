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
            Consultas.
        </div>
        <div class="navegacion">
            <form action="../funciones.php" method="post">
                <button type="submit" name="paginaPrincipal">Pagina Principal</button>
                <button type="submit" name="abrirTablaPacientes">Pacientes</button>
                <button type="submit" name="abrirTablaExpediente">Expedientes</button>
                <button type="submit" name="abrirTablaOrdenEstudio">Ordenes de estudio</button>
                <button class="cerrarSesion" type="submit" name="cerrarSesion" onclick="return confirmarCerrarSesion()">cerrar sesion</button>
            </form>
        </div>
        <div class="barraBusqueda">
            <form action="tablaConsulta.php" method="post">
                <div>
                    <a>Búsqueda: </a>
                    <input type="text" name="nomBus">
                    <button type="submit" name="buscar">Buscar</button>
                </div>
            </form>
        </div>
        <?php
        $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total
                                            FROM consulta 
                                            WHERE id_usuario = '$usuario'");
        $rescuentaar = mysqli_fetch_array($cuenta);
        $rescuenta = $rescuentaar['total'];
        $por_pagina = 1;
        if (empty($_GET['pagina'])) {
            $pagina = 1;
        } else {
            $pagina = $_GET['pagina'];
        }
        $desde = ($pagina - 1) * $por_pagina;
        $total_paginas = ceil($rescuenta / $por_pagina);
        $consulta1 = "SELECT * FROM consulta WHERE id_usuario = '$usuario' LIMIT $desde,$por_pagina";
        if (isset($_POST['buscar'])) {
            if ($_POST['nomBus'] != "") {
                $abuscar = $_POST['nomBus'];
                $consulta2 = "SELECT * FROM consulta 
                              WHERE id_usuario = '$usuario' 
                              AND (id_paciente LIKE '%$abuscar%'
                              OR sintomas_obs LIKE '%$abuscar%'
                              OR sintomas_ads LIKE '%$abuscar%'
                              OR diagnostico_pre LIKE '%$abuscar%')
                              LIMIT $desde,$por_pagina";
                $resultado = mysqli_query($conexionP, $consulta2);
                $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total
                                            FROM consulta 
                                            WHERE id_usuario = '$usuario'");
                $rescuentaar = mysqli_fetch_array($cuenta);
                $rescuenta = $rescuentaar['total'];
                $por_pagina = 1;
                if (empty($_GET['pagina'])) {
                    $pagina = 1;
                } else {
                    $pagina = $_GET['pagina'];
                }
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($rescuenta / $por_pagina);
            } else {
                $resultado = mysqli_query($conexionP, $consulta1);
            }
        } else {
            $resultado = mysqli_query($conexionP, $consulta1);
        }
        mysqli_close($conexionP);?>
        <form action="tablaConsulta.php" method="post">
            <div>
                <?php if (isset($abuscar)) { ?>
                    <a>Usted busco:<?php print($abuscar); ?></a>
                    <button type="submit" name="borrar">X</button>
                <?php
                } ?>
            </div>
        </form>
        <?php
        if (isset($_POST['borrar'])) {
            $consulta = "SELECT * FROM consulta";
        }?>
        <table border="1">
            <tr>
                <th>id paciente</th>
                <th class="thModi">sintomas observados</th>
                <th class="thModi">sintomas adyacentes</th>
                <th class="thModi">diagnostico preliminar</th>
            </tr>
            <?php
            while ($mostrar = mysqli_fetch_array($resultado)) {
                if ($mostrar['id_usuario'] == $usuario) { ?>
                    <tr>
                        <td><?php echo $mostrar['id_paciente'] ?></td>
                        <td class="tdModi">
                            <div class="tdDiv">
                                <?php print($mostrar['sintomas_obs']) ?>
                            </div>
                        </td>
                        <td class="tdModi">
                            <div class="tdDiv">
                                <?php print($mostrar['sintomas_ads']) ?>
                            </div>
                        </td>
                        <td class="tdModi">
                            <div class="tdDiv">
                                <?php print($mostrar['diagnostico_pre']) ?>
                            </div>
                        </td>
                    </tr>
            <?php
                }
            }?>
        </table>
        <nav class="paginador">
            <ul><?php
                if ($pagina != 1) {?>
                    <li><a href="?pagina=<?php echo 1 ?>">|< </a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                            << </a>
                    </li><?php
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
                }?>
            </ul>
        </nav>
    </div>
</body>
</html>