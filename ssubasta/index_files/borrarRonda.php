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
	require_once ( BASELIB . 'class.usuario.php');

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	$ronda = $unSesion->obtenerVariable('numeroRonda');
	
//	echo $ronda;
	
	if ($ronda >= 1) {
		$rondas = $unSesion->obtenerVariable('rondas');
		$ronda = count ($rondas);
		$ronda = $ronda - 1;
		unset ($rondas[$ronda]) ;
		$ronda = $ronda - 1;
//		echo $ronda;
//		die ();
		
		$unSesion->registrarVariable ('rondas', $rondas) ;
		$unSesion->registrarVariable ('numeroRonda', $ronda) ;
	}
		
	header ('location: simulacion.php');	

	
		 
//	echo "<pre>";
//	print_r ($tmpIdCampo);
//	print_r ($tmpIdEmpresa);
//	print_r ($_SESSION['rondas']);
//	print_r ($_POST);
//	echo "</pre>"; 
?>