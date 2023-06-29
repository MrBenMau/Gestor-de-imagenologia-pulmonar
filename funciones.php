<?php
include 'conexion.php';
require_once dirname(__FILE__).'/PHPWord-master/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
use PhpOffice\PhpWord\TemplateProcessor as POSTP;

$conexion = $conexionP;

session_start();

/* Redireccionamiento o llamado a funciones */
/*Llamado a funciones */
if (isset($_POST['crearCuenta'])) {
    crearUsuario($conexion);
    mysqli_close($conexion);
} elseif (isset($_POST['crearPacienteB'])) {
    crearPaciente($conexion);
    mysqli_close($conexion);
} elseif (isset($_POST['modificarPas'])) {
    modificarPaciente($conexion);
} elseif (isset($_POST['eliminarUsuario'])) {
    eliminarUsuario($conexion);
} elseif (isset($_POST['cerrarSesion'])) {
    cerrarSesion();
} elseif (isset($_POST['modificarUs'])) {
    modificar_usuario($conexion);
} elseif (isset($_POST['EliminarPas'])) {
    eliminarPaciente($conexion);
} elseif (isset($_POST['ActivarPas'])) {
    activarPaciente($conexion);
} elseif (isset($_POST['GuardarConsulta'])) {
    nuevaConsulta($conexion);
} elseif (isset($_POST['GuardarExpediente'])) {
    nuevoExpediente($conexion);
} elseif (isset($_POST['editAntecedente'])) {
    editarExpediente($conexion);
} elseif (isset($_POST['creaOrdEs'])) {
    nuevaOrdenEstudio($conexion);
} elseif (isset($_POST['comprobarUsuario'])) {
    comprobarUsuario($conexion);
} elseif (isset($_POST['crearCred'])) {
    crearCredencial($conexion);
    /* --------------------------------------- */
    /* redireccionamiento */
} elseif (isset($_POST['registrarse'])) {
    header("Location:vista/registrarse.php");
} elseif (isset($_POST['viejaCuenta'])) {
    header("Location:vista/index.php");
} elseif (isset($_POST['modificarUsVen'])) {
    header("Location:vista/modificarUsuario.php");
} elseif (isset($_POST['abrirTablaConsulta'])) {
    header("Location:vista/tablaConsulta.php");
} elseif (isset($_POST['abrirTablaExpediente'])) {
    header("Location:vista/tablaExpediente.php");
} elseif (isset($_POST['abrirTablaOrdenEstudio'])) {
    header("Location:vista/tablaOrdenEstudio.php");
} elseif (isset($_POST['abrirTablaPacientes'])) {
    header("Location:vista/tablaPacientes.php");
} elseif (isset($_POST['paginaPrincipal'])) {
    header("Location:vista/paginaEntrada.php");
} elseif (isset($_POST['crearPacienteP'])) {
    header("Location:vista/creacionPaciente.php");
} elseif (isset($_POST['crearConsultaP'])) {
    header("Location:vista/crearConsulta.php");
} elseif (isset($_POST['crearExpedienteP'])) {
    header("Location:vista/crearExpediente.php");
} elseif (isset($_POST['crearOrdenEstudioP'])) {
    header("Location:vista/crearOrdenEstudio.php");
}
/* declaracion de funciones */
/* Seccion usuario */
function crearUsuario($conexion)
{
    $contraseña = $_POST['contraseña'];
    $nombre_us = $_POST['nombre_us'];
    $apellido_pa = $_POST['apellido_pa'];
    $apellido_ma = $_POST['apellido_ma'];
    $correo_us = $_POST['correoUsuario'];
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    $rol = $_POST['rol'];
    $estado = 0;
    $fecha_re = date('mdY');

    if ($rol == "0") {
        $rol = "MG";
    } elseif ($rol == "1") {
        $rol = "ES";
    } elseif ($rol == "2") {
        $rol = "MT";
    }

    $id_us = strtoupper($rol . substr($nombre_us, 0, 1) . substr($apellido_pa, 0, 1) . substr($apellido_ma, 0, 1));

    $consulta2 = "SELECT COUNT(*) FROM usuario WHERE id_usuario REGEXP CONCAT('$id_us','[A-Z0-9]+')";
    $consulta3 = "SELECT * FROM usuario WHERE correo_usuario = '$correo_us'";

    $resultado = mysqli_query($conexion, $consulta3);
    if (mysqli_num_rows($resultado) == 0) {
        $resultado = mysqli_query($conexion, $consulta2);
        $id_usn = $id_us . strval(dechex(mysqli_fetch_array($resultado)[0] + 1));
        $consulta1 = "INSERT INTO usuario(id_usuario,contraseña,nom_usuario,ape_paterno,ape_materno,correo_usuario,foto_identificacion,rol,estado,fecha_registro)
            VALUES ('$id_usn','$contraseña','$nombre_us','$apellido_pa','$apellido_ma','$correo_us','$imagen','$rol','$estado','$fecha_re')";
        mysqli_query($conexion, $consulta1);

        if ($rol == "MG") {
            $consultaAlterna = "INSERT INTO medico_general(id_usuario,num_pac_aten,num_casos_rea)
            VALUES ('$id_usn',0,0)";
            mysqli_query($conexion, $consultaAlterna);
        } elseif ($rol == "ES") {
            $consultaAlterna = "INSERT INTO especialista(id_usuario,num_casos_reci,num_diag_rea)
            VALUES ('$id_usn',0,0)";
            mysqli_query($conexion, $consultaAlterna);
        } elseif ($rol == "MT") {
            $consultaAlterna = "INSERT INTO medico_tecnico(id_usuario,num_radio,num_tomo,num_ultra)
            VALUES ('$id_usn',0,0,0)";
            mysqli_query($conexion, $consultaAlterna);
        }
        echo '<script type="text/javascript">
        alert("Registro exitoso");
        window.location.href="vista/index.php";
        </script>';
    } else {
        echo '<script type="text/javascript">
        alert("El correo ya esta registrado");
        window.location.href="vista/registrarse.php";
        </script>';
    }
}

function eliminarUsuario($conexion)
{
    $id_us = $_SESSION['username'];
    $consulta = "UPDATE usuario SET estado = 1 WHERE id_usuario = '$id_us'";

    mysqli_query($conexion, $consulta);
    mysqli_close($conexion);
    cerrarSesion();
    die();
}

function modificar_usuario($conexion)
{
    $contraseñaUs = $_POST['contraseña'];
    $nomUs = $_POST['nombreUs'];
    $apePaUs = $_POST['apellidoPaUs'];
    $apeMaUs = $_POST['apellidoMaUs'];
    $correUs = $_POST['correoUs'];

    if (!empty($_POST['imagen'])) {
        $fotoId = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
        $fotoId = '';
    }

    $listaCambios = array();

    if ($contraseñaUs != '') {
        array_push($listaCambios, 'contraseña');
        array_push($listaCambios, $contraseñaUs);
    }
    if ($nomUs != '') {
        array_push($listaCambios, 'nom_usuario');
        array_push($listaCambios, $nomUs);
    }
    if ($apePaUs != '') {
        array_push($listaCambios, 'ape_paterno');
        array_push($listaCambios, $apePaUs);
    }
    if ($apeMaUs != '') {
        array_push($listaCambios, 'ape_materno');
        array_push($listaCambios, $apeMaUs);
    }
    if ($correUs != '') {
        array_push($listaCambios, 'correo_usuario');
        array_push($listaCambios, $correUs);
    }
    if ($fotoId != null) {
        array_push($listaCambios, 'foto_identificacion');
        array_push($listaCambios, $fotoId);
    }
    for ($i = 0; $i < sizeof($listaCambios); $i++) {
        if ($i % 2 == 0) {
            $j = $i + 1;
            $id = $_SESSION['username'];
            $consulta = "UPDATE usuario SET " . $listaCambios[$i] . " = '" . $listaCambios[$j] . "' WHERE id_usuario = '" . $id . "'";
            mysqli_query($conexion, $consulta);
        }
    }
    header("Location:vista/paginaEntrada.php");
    mysqli_close($conexion);
}

function recuperarUsuario($conexion)
{
    $id_us = $_POST['id_us'];
    $consulta = "UPDATE usuario SET estado = 0 WHERE id_usuario = '$id_us'";

    mysqli_query($conexion, $consulta);
    mysqli_close($conexion);
}

function comprobarUsuario($conexion)
{
    $id_us = $_POST['correoU'];
    $contraseña = $_POST['contraseña'];
    $consulta = "SELECT * FROM usuario WHERE correo_usuario ='$id_us'";
    $resultado = mysqli_query($conexion, $consulta);

    $array = mysqli_fetch_array($resultado);

    if (!empty($array)) {
        if ($array['estado'] != 1 && $array['estado'] != 2) {
            if ($contraseña == $array['contraseña']) {
                $_SESSION['username'] = $array['id_usuario'];
                header("Location:vista/paginaEntrada.php");
                $rol = $array['rol'];
                $_SESSION['rolUs'] = $rol;
            } else {
                session_destroy();
                echo '<script type="text/javascript">
        alert("Contraseña incorrecta!");
        window.location.href="vista/index.php";
        </script>';
            }
        } else {
            session_destroy();
            echo '<script type="text/javascript">
        alert("La cuenta esta suspendida, contacte al administrador");
        window.location.href="vista/index.php";
        </script>';
        }
    } else {
        session_destroy();
        echo '<script type="text/javascript">
        alert("Correo no registrado!");
        window.location.href="vista/index.php";
        </script>';
    }
    mysqli_close($conexion);
}

function cerrarSesion()
{
    session_destroy();
    header("Location:vista/index.php");
    exit();
}

/* seccion paciente */
function crearCredencial($conexion){
    $id_paciente = $_POST['crearCred'];
    $consulta = "SELECT * FROM paciente WHERE id_paciente = '$id_paciente'";
    $resultado = mysqli_query($conexion,$consulta);
    $arreglo = mysqli_fetch_array($resultado);

    $templateWord = new POSTP('docs/identificacionBase.docx');

    $templateWord->setValue('id_paciente',$id_paciente);
    $templateWord->setValue('nomb_pac',$arreglo['nomb_pac']);
    $templateWord->setValue('ape_pa_pac',$arreglo['ape_pa_pac']);
    $templateWord->setValue('ape_ma_pac',$arreglo['ape_ma_pac']);
    $templateWord->setValue('turno_aten',$arreglo['turno_aten']);

    $templateWord->saveAs('docs\credencial'.$id_paciente.'.docx');
    header("Content-Disposition: attachment; filename=credencial".$id_paciente.".docx; charset=iso-8859-1");
    echo file_get_contents("docs/credencial".$id_paciente.".docx");
    $filename="docs/credencial".$id_paciente.".docx";
    unlink($filename);
}

function crearPaciente($conexion)
{
    $nombpac = $_POST['nombpac'];
    $apellidopa = $_POST['apellidopa'];
    $apellidoma = $_POST['apellidoma'];
    $turno = $_POST['turno'];
    $estadoPa = 0;
    if(!empty($_FILES['imagenpac']['tmp_name'])){
        $imagenpac = addslashes(file_get_contents($_FILES['imagenpac']['tmp_name']));
    }   else{
        $imagenpac = addslashes(file_get_contents('img/pacienteD.jpeg'));
    }

    $idpaciente = strtoupper("P" . substr($nombpac, 0, 1) . substr($apellidopa, 0, 1) . substr($apellidoma, 0, 1) . substr($turno, 0, 2));

    $consult2 = "SELECT COUNT(*) FROM paciente WHERE id_paciente REGEXP CONCAT('$idpaciente','[A-Z0-9]+')";

    $resultado = mysqli_query($conexion, $consult2);

    $idpaciente = $idpaciente . strval(dechex(mysqli_fetch_array($resultado)[0] + 1));

    $consulta1 = "INSERT INTO paciente(id_paciente,nomb_pac,ape_pa_pac,ape_ma_pac,foto_ident_pac,turno_aten,estado_paciente) 
    VALUES ('$idpaciente','$nombpac','$apellidopa','$apellidoma','$imagenpac','$turno',$estadoPa)";
    mysqli_query($conexion, $consulta1);

    header("Location:vista/tablaPacientes.php");
}

function modificarPaciente($conexion)
{
    $idP = $_POST['idPaciente'];
    $nomPac = $_POST['nombreP'];
    $apePaP = $_POST['apellidoPaP'];
    $apeMaP = $_POST['apellidoMaP'];
    $turnoA = $_POST['turnoP'];

    if (!empty($_POST['imagen'])) {
        $fotoId = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
        $fotoId = '';
    }

    $listaCambios = array();

    if ($nomPac != '') {
        array_push($listaCambios, 'nomb_pac');
        array_push($listaCambios, $nomPac);
    }
    if ($apePaP != '') {
        array_push($listaCambios, 'ape_pa_pac');
        array_push($listaCambios, $apePaP);
    }
    if ($apeMaP != '') {
        array_push($listaCambios, 'ape_ma_pac');
        array_push($listaCambios, $apeMaP);
    }
    if ($turnoA != '') {
        array_push($listaCambios, 'turno_aten');
        array_push($listaCambios, $turnoA);
    }
    if ($fotoId != null) {
        array_push($listaCambios, 'foto_ident_pac');
        array_push($listaCambios, $fotoId);
    }
    for ($i = 0; $i < sizeof($listaCambios); $i++) {
        if ($i % 2 == 0) {
            $j = $i + 1;
            $id = $_SESSION['username'];
            $consulta = "UPDATE paciente SET " . $listaCambios[$i] . " = '" . $listaCambios[$j] . "' WHERE id_paciente = '" . $idP . "'";
            mysqli_query($conexion, $consulta);
        }
    }
    mysqli_close($conexion);
    header("Location:vista/tablaPacientes.php");
}

function eliminarPaciente($conexion)
{
    $id_pas = $_POST['EliminarPas'];
    $consulta = "UPDATE paciente SET estado_paciente = 1 WHERE id_paciente = '$id_pas'";

    mysqli_query($conexion, $consulta);
    mysqli_close($conexion);
    header("Location:vista/tablaPacientes.php");
}
function activarPaciente($conexion)
{
    $id_pas = $_POST['ActivarPas'];
    $consulta = "UPDATE paciente SET estado_paciente = 0 WHERE id_paciente = '$id_pas'";

    mysqli_query($conexion, $consulta);
    mysqli_close($conexion);
    header("Location:vista/tablaPacientes.php");
}
/* Seccion consulta */

function nuevaConsulta($conexion)
{
    $id_paciente = $_POST['idPaciente'];
    $id_doctor = $_POST['idDoctor'];
    $sintomasObservados = $_POST['sintomasObservados'];
    $sintomasAdscritos = $_POST['sintomasAdscritos'];
    $diagnosticoPre = $_POST['diagnosticoPreliminar'];

    $consulta = "INSERT INTO consulta(id_paciente,id_usuario,sintomas_obs,sintomas_ads,diagnostico_pre) 
        VALUES ('$id_paciente','$id_doctor','$sintomasObservados','$sintomasAdscritos','$diagnosticoPre')";
    mysqli_query($conexion, $consulta);

    $consulta = "SELECT num_pac_aten FROM medico_general WHERE id_usuario = '$id_doctor'";
    $resultado = mysqli_query($conexion, $consulta);

    $valor = intval(mysqli_fetch_array($resultado)[0]);
    $valor = $valor + 1;

    $consulta = "UPDATE medico_general SET num_pac_aten = '$valor'";
    mysqli_query($conexion, $consulta);

    mysqli_close($conexion);
    header("Location:vista/tablaConsulta.php");
}
/* Seccion expediente */
function nuevoExpediente($conexion)
{
    $id_paciente = $_POST['idPaciente'];
    $idExpediente = "EX" . substr($id_paciente, 1, 6);
    $padecimiento = $_POST['padecimiento'];
    $tratamiento = $_POST['tratamiento'];

    $consulta = "SELECT COUNT(*) FROM expediente WHERE id_antecedente REGEXP CONCAT('$idExpediente','[0-9]+')";
    $resultado = mysqli_query($conexion, $consulta);

    $idEx = $idExpediente . strval(dechex(mysqli_fetch_array($resultado)[0] + 1));

    $consulta = "INSERT INTO expediente(id_antecedente,padecimiento,tratamiento) 
            VALUES('$idEx','$padecimiento','$tratamiento')";

    mysqli_query($conexion, $consulta);

    $consulta = "SELECT * FROM pac_ant WHERE (id_paciente = '$id_paciente') AND (id_antecedente = '$idEx')";
    $resultado = mysqli_fetch_array(mysqli_query($conexion, $consulta));
    echo $resultado['id_paciente'] . ",";
    if ($resultado['id_paciente'] == "") {
        $consulta = "INSERT INTO pac_ant(id_paciente,id_antecedente) 
            VALUES('$id_paciente','$idEx')";
        mysqli_query($conexion, $consulta);
    }

    $consulta = "SELECT num_casos_rea FROM medico_general WHERE id_usuario = '" . $_SESSION['username'] . "'";
    $resultado = mysqli_query($conexion, $consulta);

    $valor = intval(mysqli_fetch_array($resultado)[0]);
    $valor = $valor + 1;

    $consulta = "UPDATE medico_general SET num_casos_rea = '$valor'";
    mysqli_query($conexion, $consulta);

    header("Location:vista/tablaExpediente.php");
    mysqli_close($conexion);
}

function editarExpediente($conexion)
{
    $idAntecedente = $_POST['editAntecedente'];
    $padecimiento = $_POST['padecimiento'];
    $tratamiento = $_POST['tratamiento'];

    $consulta = "SELECT * FROM expediente WHERE id_antecedente = '$idAntecedente'";
    $resultado = mysqli_fetch_array(mysqli_query($conexion, $consulta));

    if ($resultado[0] != "") {
        if ($padecimiento == "") {
            $padecimiento = $resultado['padecimiento'];
        }
        if ($tratamiento == "") {
            $tratamiento = $resultado['tratamiento'];
        }
        $consulta = "UPDATE expediente SET padecimiento = '$padecimiento', tratamiento = '$tratamiento' 
        WHERE id_antecedente = '$idAntecedente'";
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        header("Location:vista/tablaExpediente.php");
    }
}
/* Seccion orden de estudio */
function nuevaOrdenEstudio($conexion)
{
    $rol = $_SESSION['rolUs'];
    $idUsuario = $_SESSION['username'];
    $idAntecedente = $_POST['creaOrdEs'];
    $tipoEstudio = $_POST['tipoEstudio'];
    $especificacion = $_POST['especificaciones'];
    $tipoEstudio = $tipoEstudio . " " . $especificacion;
    $consulta = "";
    echo $idAntecedente;
    if ($rol == "MG") {
        /* crea la orden de estudio */
        $consulta = "INSERT INTO orden_estudio(id_antecedente,id_usuario,tipo_estudio)
            VALUES ('$idAntecedente','$idUsuario','$tipoEstudio')";
    } else if ($rol == "ES") {
        /* agrega las observaciones a la orden de estudio */
        $observaciones = $_POST['observaciones'];
        $consulta = "UPDATE orden_estudio SET observaciones = '$observaciones' 
                WHERE id_antecedente = '$idAntecedente'";
    } else if ($rol == "MT") {
        /* agregar las imagenes a la orden de estudio */
        $imagenResultado = addslashes(file_get_contents($_FILES['imagenResultado']['tmp_name']));
        $consulta = "UPDATE orden_estudio SET imagen_resultado = '$imagenResultado' 
                WHERE id_antecedente = '$idAntecedente'";
    }

    mysqli_query($conexion, $consulta);

    mysqli_close($conexion);
    header("Location:vista/tablaOrdenEstudio.php");
}
?>