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
	$unSesion->redirecInicioSesion ('../index.php') ;
	
	if (isset ($_GET['ver'])) {
		$tmpEscenario = $unSesion->obtenerVariable('escenarios');
		$unSesion->registrarVariable('listVendedor', $tmpEscenario[$_GET['ver']]['listVendedor']);
		$unSesion->registrarVariable('listComprador', $tmpEscenario[$_GET['ver']]['listComprador']);
		$unSesion->registrarVariable('listDestino', $tmpEscenario[$_GET['ver']]['listDestino']);
		$unSesion->registrarVariable('transporte', $tmpEscenario[$_GET['ver']]['transporte']);
		$unSesion->registrarVariable('listDemanda', $tmpEscenario[$_GET['ver']]['listDemanda']);
		$unSesion->registrarVariable('idEscenario', $tmpEscenario[$_GET['ver']]['idEscenario']);
		$unSesion->registrarVariable('rondas', $tmpEscenario[$_GET['ver']]['rondas']);
		$unSesion->registrarVariable('numeroRonda', (count ($tmpEscenario[$_GET['ver']]['rondas']) - 1 ) );
		}


	if ($_GET['que'] == 'config')
		header ('Location: iniciarEscenario.php');	
	elseif ($_GET['que'] == 'ver')
		header ('Location: simulacion.php');	
/*	
	echo "<pre>";
	print_r ($unSesion->obtenerVariable('rondas'));
	echo "</pre>";	
*/
?>