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
}?>
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
            Ordenes de estudio.
        </div>
        <div class="navegacion">
            <form action="../funciones.php" method="post">
                <button type="submit" name="paginaPrincipal">Pagina Principal</button>
                <?php if ($rol != "MT") { ?>
                    <button type="submit" name="abrirTablaPacientes">Pacientes</button>
                <?php } ?>
                <?php if ($rol == "MG") { ?>
                    <button type="submit" name="abrirTablaConsulta" id="botonNavegacion">Consultas</button>
                    <button type="submit" name="abrirTablaExpediente">Expedientes</button>
                <?php } ?>
                <button class="cerrarSesion" type="submit" name="cerrarSesion" onclick="return confirmarCerrarSesion()">cerrar sesion</button>
            </form>
        </div>
        <div class="barraBusqueda">
            <form action="tablaOrdenEstudio.php" method="post">
                <div>
                    <a>Búsqueda: </a>
                    <input type="text" name="nomBus">
                    <button type="submit" name="buscar">Buscar</button>
                </div>
            </form>
        </div>
        <?php
        if ($rol == "MT" || $rol == "ES") {
            $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total
                                                FROM orden_estudio");
        } else {
            $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total 
                                                FROM orden_estudio 
                                                WHERE id_usuario = '$usuario'");
        }
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
        if ($rol == "MT" || $rol == "ES") {
            $consulta1 = "SELECT * FROM orden_estudio 
                          LIMIT $desde,$por_pagina";
        } else {
            $consulta1 = "SELECT * FROM orden_estudio 
                          WHERE id_usuario = '$usuario' 
                          LIMIT $desde,$por_pagina";
        }
        if (isset($_POST['buscar'])) {
            if ($_POST['nomBus'] != "") {
                $abuscar = $_POST['nomBus'];
                if ($rol == "MT" || $rol == "ES") {
                    $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total
                                                        FROM orden_estudio 
                                                        WHERE id_antecedente LIKE '%$abuscar%'
                                                        OR tipo_estudio LIKE '%$abuscar%'
                                                        OR observaciones LIKE '%$abuscar%'
                                                        LIMIT $desde,$por_pagina");
                } else {
                    $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total 
                                                        FROM orden_estudio 
                                                        WHERE id_usuario = '$usuario' 
                                                        AND(id_antecedente LIKE '%$abuscar%'
                                                        OR tipo_estudio LIKE '%$abuscar%'
                                                        OR observaciones LIKE '%$abuscar%')");
                }
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
                if ($rol == "MT" || $rol == "ES") {
                    $consulta2 = "SELECT * FROM orden_estudio 
                                  WHERE id_antecedente LIKE '%$abuscar%'
                                  OR tipo_estudio LIKE '%$abuscar%'
                                  OR observaciones LIKE '%$abuscar%'
                                  LIMIT $desde,$por_pagina";
                    $resultado = mysqli_query($conexionP, $consulta2);
                } else {
                    $consulta2 = "SELECT * FROM orden_estudio 
                                  WHERE id_usuario = '$usuario'  
                                  AND (id_antecedente LIKE '%$abuscar%'
                                  OR tipo_estudio LIKE '%$abuscar%'
                                  OR observaciones LIKE '%$abuscar%')
                                  LIMIT $desde,$por_pagina";
                    $resultado = mysqli_query($conexionP, $consulta2);
                }
            } else {
                $resultado = mysqli_query($conexionP, $consulta1);
            }
        } else {
            $resultado = mysqli_query($conexionP, $consulta1);
        }
        mysqli_close($conexionP);
        ?>
        <form action="tablaOrdenEstudio.php" method="post">
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
            $consulta = "SELECT * FROM orden_estudio";
        } ?>
        <table border="1">
            <tr>
                <th>id antecedente</th>
                <th class="thModi">tipo de estudio</th>
                <th class="thModi">imagen de resultado</th>
                <th class="thModi">observaciones</th>
            </tr>
            <?php
            while ($mostrar = mysqli_fetch_array($resultado)) {
                if ($mostrar['id_usuario'] == $usuario || $rol == "MT" || $rol == "ES") { ?>
                    <tr>
                        <td><?php echo $mostrar['id_antecedente'] ?></td>
                        <td class="tdModi">
                            <div class="tdDiv">
                                <?php echo $mostrar['tipo_estudio'] ?>
                            </div>
                        </td>
                        <?php if ($rol == "MT") { ?>
                            <?php if ($mostrar['imagen_resultado'] == null) { ?>
                                <form action="agregarCambioOE.php" method="post">
                                    <td class="tdModi"><button class="botonesTabla color2" type="submit" name="creaOrdEs" value=<?php echo $mostrar['id_antecedente'] ?>>Agregar imagen</button></td>
                                </form>
                            <?php } else { ?>
                                <td class="tdModi">
                                    <img height="80px" src="data:image/jpg;base64,<?php echo base64_encode($mostrar['imagen_resultado']); ?>">
                                    <form action="agregarCambioOE.php" method="post">
                                        <div>
                                            <button class="botonesTabla color3" type="submit" name="creaOrdEs" value=<?php echo $mostrar['id_antecedente'] ?>>Cambiar imagen</button>
                                        </div>
                                    </form>
                                </td>
                            <?php } ?>
                            <?php if ($mostrar['observaciones'] == "") { ?>
                                <td class="tdModi">Denegado</td>
                            <?php } else { ?>
                                <td class="tdModi">
                                    <div class="tdDiv">
                                        <?php echo $mostrar['observaciones']; ?>
                                    </div>
                                </td>
                            <?php } ?>
                        <?php } elseif ($rol == "ES") { ?>
                            <?php if ($mostrar['imagen_resultado'] == null) { ?>
                                <td class="tdModi">Denegado</td>
                                <td class="tdModi">Imagen inexistente</td>
                            <?php } else { ?>
                                <td class="tdModi">
                                    <div>
                                        <img height="80px" src="data:image/jpg;base64,<?php echo base64_encode($mostrar['imagen_resultado']); ?>">
                                    </div>
                                    <form action="verImagen.php" method="post">
                                        <div>
                                            <button type="submit" class="btnImagen" name="img" value=<?php echo base64_encode($mostrar['imagen_resultado']); ?>>ver</button>
                                        </div>
                                    </form>
                                </td>
                                <?php if ($mostrar['observaciones'] == "") { ?>
                                    <form action="agregarCambioOE.php" method="post">
                                        <td class="tdModi"><button class="botonesTabla color2" type="submit" name="creaOrdEs" value=<?php echo $mostrar['id_antecedente'] ?>>Agregar observaciones</button></td>
                                    </form>
                                <?php } else { ?>
                                    <td class="tdModi">
                                        <div class="tdDiv"><?php echo $mostrar['observaciones']; ?></div>
                                        <form action="agregarCambioOE.php" method="post">
                                            <div><button class="botonesTabla color3" type="submit" name="creaOrdEs" value=<?php echo $mostrar['id_antecedente'] ?>>Editar</button></div>
                                        </form>
                                    </td>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($mostrar['imagen_resultado'] == null) { ?>
                                <td class="tdModi">Denegado</td>
                            <?php } else { ?>
                                <td class="tdModi"><img height="80px" src="data:image/jpg;base64,<?php echo base64_encode($mostrar['imagen_resultado']); ?>"></td>
                            <?php } ?>
                            <?php if ($mostrar['observaciones'] == "") { ?>
                                <td class="tdModi">Denegado</td>
                            <?php } else { ?>
                                <td class="tdModi">
                                    <div class="tdDiv">
                                        <?php echo $mostrar['observaciones']; ?>
                                    </div>
                                </td>
                            <?php } ?>
                        <?php } ?>
                    </tr>
            <?php }
            } ?>
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