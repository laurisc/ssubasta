<?php
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	# variables estaticas para incluir librerias y plantillas
	define ('BASELIB', 'lib/');
	define ('BASETPL', 'tpl/');
	
	$nuevoRegistro = false;
	
	# se incluye las  librerias de inicio de sesion y conexion a la base de datos
	require_once ( BASELIB . 'class.db.php' );
	require_once ( BASELIB . 'class.sesion.php');
	require_once ( BASELIB . 'class.usuario.php');

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	
	//NO SE PUEDE BORRAR??
	# si es enviada la peticion de crear un usuario.
	if (isset ($_POST['button']) && $_POST['button'] == 'Guardar') {
		if ($_POST['clave'] == $_POST['reclave']) {
			$unUsuario = new usuario ($_POST['tipoDocumento'], $_POST['documento'], $_POST['razonSocial'], $_POST['representanteLegal'], $_POST['actividadEconomica'], $_POST['direccion'], $_POST['ciudad'], $_POST['telefonoUno'], $_POST['telefonoDos'], $_POST['email']);
			if ($unUsuario->validarUsuario()) {
				$sql = "INSERT INTO `usuario` (`tipo_documento` ,`documento` ,`razon_social` ,`representante` ,`actividad_economica` ,`direccion` ,`id_ciudad` ,`telefono_uno` ,`telefono_dos` ,`email` ,`codigo_verificacion` ,`clave` ,`fecha_inicio` ,`fecha_fin` ,`tipo`) 
				VALUES ('" . $_POST['tipoDocumento'] . "', '" . $_POST['documento'] . "', '" . $_POST['razonSocial'] . "', '" . $_POST['representanteLegal'] . "', '" . $_POST['actividadEconomica'] . "', '" . $_POST['direccion'] . "', '" . $_POST['ciudad'] . "', '" . $_POST['telefonoUno'] . "', '" . $_POST['telefonoDos'] . "', '" . $_POST['email'] . "', '" . md5($_POST['clave']) . "', '" . md5($_POST['clave']) . "', '" . date ('Y') .'-'. date ('m') .'-'. date ('d') . "', '" . date ('Y') .'-'. date ('m') .'-'. date ('d') . "', '1');";
				$res = $conDB->SQL($sql);
				if (mysql_affected_rows () == 1 ) {
					unset($_POST);
					$nuevoRegistro = true;
				}
				elseif (mysql_errno ()) { 
					if (mysql_errno () == 1062)
						$msjErrorRegistro = 'Error, este usuario ya se encuentra registrado';	
				}
			}
			else
				$msjErrorRegistro = 'Error, complete o corriga la información';
			}
		else
			$msjErrorRegistro = 'Error, las contraseñas no coinciden.';	
		}
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	
	include BASETPL . 'list_escenario.php';
			
	include BASETPL . 'pie.php';
	
	$conDB->desconectar ();
?>