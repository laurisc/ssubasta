<?php
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	# variables estaticas para incluir librerias, plantillas y archivo config JOOMLA
	define ('BASELIB', 'lib/');
	define ('BASETPL', 'tpl/');
	define ('BASEJOOMLA', '../');
	
	
	# se incluye las  librerias de inicio de sesion y conexion a la base de datos
	require_once ( BASELIB . 'class.db.php' );
	require_once ( BASELIB . 'class.sesion.php');	
	require_once ( BASEJOOMLA . 'configuration.php');	

	# se inician las variables de sesion y base de datos
	$unSesion = new sesion ('sSubasta');
	$unJConfig = new JConfig;
	
	# si es enviado el formularion con los datos inicia la validacion del usuario
	# debe existir el usuario, debe estar activo, la clave debe ser igual a la resgistrada
	# si la validaciones son verdaderas se redirecciona al home
	if (isset($_POST['login']) && isset($_POST['clave'])) {
		$tmpCon = mysql_connect($unJConfig->host, $unJConfig->user, $unJConfig->password);
		$tmpDb = mysql_select_db($unJConfig->db, $tmpCon);
		$sql = "SELECT * FROM " . $unJConfig->dbprefix . "users WHERE username = '" . trim ($_POST['login']) . "'";
		$res = mysql_query ($sql);
		mysql_close ($tmpCon);
		if (mysql_num_rows ($res) == 1) {

			$conDB = new db ;
			$conDB->conectarDB ();
			$conDB->seleccionDB ();				

			$tmpUsuario = mysql_fetch_object ($res);
			$pass = explode (":", $tmpUsuario->password);
			if ($pass[0] == md5($_POST['clave'] . $pass[1])) {
				if ($tmpUsuario->block == 0) {
					$unSesion->registrarVariable('usuario', $tmpUsuario->name);
//					$unSesion->registrarVariable('tipo',    $tmpUsuario->tipo);
					$unSesion->registrarVariable('email',    $tmpUsuario->email);
					$unSesion->registrarVariable('id',    $tmpUsuario->id);
					$sql = "SELECT * FROM usuario where id = " . $tmpUsuario->id;
					$resT = $conDB->SQL($sql);
					$conDB->desconectar ();					
					if (mysql_num_rows ($resT) == 1) {
						$unSesion->registrarVariable('datosActualizados', 1);
						header ('location: index.php');
						}
					else
						header ('location: registro.php');
					}
				else
					$msjErrorRegistro = 'Error, el usuario esta inactivo.';	
				}
			else
				$msjErrorRegistro = 'Usuario o clave incorrectos.';
			}
		else
			$msjErrorRegistro = 'Error, el usuario no existe.';		
		
/*		$sql = "select * from usuario where documento = '" . trim ($_POST['login']) . "'";
		$res = $conDB->SQL ($sql);
		if (mysql_num_rows ($res) == 1) {
			$tmpUsuario = mysql_fetch_object ($res);
			if ($tmpUsuario->clave == md5($_POST['clave'])) {
				if ($tmpUsuario->estado == 1) {
					$unSesion->registrarVariable('usuario', $tmpUsuario->documento);
					$unSesion->registrarVariable('tipo',    $tmpUsuario->tipo);
					$unSesion->registrarVariable('email',    $tmpUsuario->email);
					$unSesion->registrarVariable('id',    $tmpUsuario->id);
					header ('location: index.php');
					}
				else
					$msjErrorRegistro = 'Error, el usuario esta inactivo.';	
				}
			else
				$msjErrorRegistro = 'Usuario o clave incorrectos.';
			}
		else
			$msjErrorRegistro = 'Error, el usuario no existe.';	*/	
		}
		
		
	
	# si es enviada la peticion de cerrar sesion
	if (isset ($_GET['cerrar']) && $_GET['cerrar']) {
		$unSesion->terminarSesion ();	
		header ('location: index.php');
		}
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'frm_login.php';
	include BASETPL . 'pie.php';
/*	echo "<pre>";
	print_r ($_SESSION);
	echo "</pre>";
	*/	
?>