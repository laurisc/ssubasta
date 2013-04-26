<?php
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	# variables estaticas para incluir librerias y plantillas
	define ('BASELIB', 'lib/');
	define ('BASETPL', 'tpl/');
	
	# se incluye las  librerias de inicio de sesion y conexion a la base de datos
	require_once ( BASELIB . 'class.db.php' );
	require_once ( BASELIB . 'class.sesion.php');	

	# se inician las variables de sesion y base de datos
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'inicio.php';
	include BASETPL . 'pie.php';
	
//	$conDB->desconectar ();
/*	echo "<pre>";
	print_r ($_SESSION);	
	echo "</pre>"; */
?>