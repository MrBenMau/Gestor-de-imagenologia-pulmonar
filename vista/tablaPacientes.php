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
            Pacientes.
        </div>
        <div class="navegacion">
            <form action="../funciones.php" method="post">
                <button type="submit" name="paginaPrincipal" id="botonNavegacion">Pagina Principal</button>
                <?php if ($rol == "MG") { ?>
                    <button type="submit" name="abrirTablaConsulta" id="botonNavegacion">Consultas</button>
                    <button type="submit" name="abrirTablaExpediente" id="botonNavegacion">Expedientes</button>
                    <button type="submit" name="abrirTablaOrdenEstudio" id="botonNavegacion">Ordenes de estudio</button>
                <?php } else { ?>
                    <button type="submit" name="abrirTablaOrdenEstudio" id="botonNavegacion">Ordenes de estudio</button>
                <?php } ?>
                <button class="cerrarSesion" type="submit" name="cerrarSesion" id="botonNavegacion" onclick="return confirmarCerrarSesion()">cerrar sesion</button>
            </form>
        </div>
        <div class="barraBusqueda">
            <form action="tablaPacientes.php" method="post">
                <a>Búsqueda: </a>
                <input type="text" name="nomBus">
                <button type="submit" name="buscar">Buscar</button>
            </form>
            <?php
            if ($rol == "MG") { ?>
                <form action="../funciones.php" method="post">
                    <button type="submit" name="crearPacienteP">Crear paciente</button>
                </form>
            <?php } ?>
        </div>
        <?php
        $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total FROM paciente WHERE estado_paciente = 0");
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
        $consulta = "SELECT * FROM paciente
                     WHERE estado_paciente = 0 LIMIT $desde,$por_pagina";
        if (isset($_POST['buscar'])) {
            if ($_POST['nomBus'] != "") {
                $abuscar = $_POST['nomBus'];
                $cuenta = mysqli_query($conexionP, "SELECT COUNT(*) as total
                FROM paciente
                WHERE estado_paciente = 0
                AND (id_paciente LIKE '%$abuscar%'
                OR nomb_pac LIKE '%$abuscar%'
                OR ape_pa_pac LIKE '%$abuscar%'
                OR ape_ma_pac LIKE '%$abuscar%')");
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
                $consulta1 = "SELECT * FROM paciente
                              WHERE estado_paciente = 0
                              AND (id_paciente LIKE '%$abuscar%'
                              OR nomb_pac LIKE '%$abuscar%'
                              OR ape_pa_pac LIKE '%$abuscar%'
                              OR ape_ma_pac LIKE '%$abuscar%') LIMIT $desde,$por_pagina";
                $resultado = mysqli_query($conexionP, $consulta1);
            } else {
                $resultado = mysqli_query($conexionP, $consulta);
            }
        } else {
            $resultado = mysqli_query($conexionP, $consulta);
        }
        mysqli_close($conexionP);?>
        <div>
            <form action="tablaPacientes.php" method="post">
                <?php if (isset($abuscar)) { ?>
                    <a>Usted busco:<?php print($abuscar); ?></a>
                    <button type="submit" name="borrar">X</button>
                <?php
                } ?>
            </form>
        </div>
        <?php
        if (isset($_POST['borrar'])) {
            $consulta = "SELECT * FROM paciente";
        }?>
        <table border="1">
            <tr>
                <th>id paciente</th>
                <th>nombre del paciente</th>
                <th>foto de identificacion</th>
                <th>turno de atencion</th>
                <th>opciones</th>
            </tr>
            <?php
            while ($mostrar = mysqli_fetch_array($resultado)) {?>
                <tr>
                    <td><?php echo $mostrar['id_paciente'] ?></td>
                    <td><?php echo $mostrar['nomb_pac'] . " " . $mostrar['ape_pa_pac'] . " " . $mostrar['ape_ma_pac'] ?></td>
                    <td><img height="100px" src="data:image/jpg;base64,<?php echo base64_encode($mostrar['foto_ident_pac']); ?>"></td>
                    <td><?php echo $mostrar['turno_aten'] ?></td>
                    <td>
                        <form action="ventanaPaciente.php" method="post">
                            <button class="botonesTabla color2" type="submit" value=<?php echo $mostrar['id_paciente']; ?> name="verPaciente">Ver paciente</button>
                        </form>
                        <?php
                        if ($rol == "MG") {
                            if ($mostrar['estado_paciente'] == 0) {?>
                                <form action="crearConsulta.php" method="post">
                                    <button class="botonesTabla color1" type="submit" value=<?php echo $mostrar['id_paciente'] ?> name="crearCon">Crear consulta</button>
                                </form>
                                <form action="crearExpediente.php" method="post">
                                    <button class="botonesTabla" type="submit" name="crearAntecedentes" value=<?php echo $mostrar['id_paciente'] ?>>Nuevo Expediente</button>
                                </form>
                            <?php
                            } else {
                            ?>
                                <form action="../funciones.php" method="post">
                                    <button class="botonesTabla" type="submit" value=<?php echo $mostrar['id_paciente']; ?> name="ActivarPas">Reactivar</button>
                                </form>
                        <?php }
                    } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        <nav class="paginador">
            <ul>
                <?php
                if ($pagina != 1) {
                ?>
                    <li><a href="?pagina=<?php echo 1 ?>">|<</a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                            <<</a>
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