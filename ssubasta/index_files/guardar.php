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

	if (isset ($_POST['button']) && $_POST['button'] == 'Guardar') {
		$tmplistComprador = serialize($unSesion->obtenerVariable ('listComprador'));
		$tmplistVendedor  = serialize($unSesion->obtenerVariable ('listVendedor'));
		$tmpTransporte    = serialize($unSesion->obtenerVariable ('transporte'));
		$tmpDestino       = serialize($unSesion->obtenerVariable ('listDestino'));
		$tmpDemanda       = serialize($unSesion->obtenerVariable ('listDemanda'));
		$tmpRondas        = $unSesion->obtenerVariable ('rondas');
		
//		$sql = "INSERT INTO escenario (id_usuario, nombre, list_comprador, list_vendedor, list_transporte, list_destino, list_demanda, ronda) VALUES (" 
		$sql = "INSERT INTO escenario (id_usuario, nombre, list_comprador, list_vendedor, list_transporte, list_destino, list_demanda) VALUES (" 
			. $unSesion->obtenerVariable ('id') . ", '" 
			. trim ($_POST['nombreEscenario']) . "', '" 
			. $tmplistComprador . "', '" 
			. $tmplistVendedor . "', '" 
			. $tmpTransporte . "', '" 
			. $tmpDestino . "', '"  
			. $tmpDemanda ."')";
				
			
		$res = $conDB->SQL($sql);
		if (mysql_affected_rows () == 1) {
			$tmpIdEscenario = mysql_insert_id() ;

			$unSesion->registrarVariable('idEscenario', $tmpIdEscenario);
			foreach ($tmpRondas as $llaveRonda => $ronda) {
				$sql = "INSERT INTO escenario_ronda (id_escenario, id_ronda, ronda) values ('" . $tmpIdEscenario . "','" . $llaveRonda . "','" . serialize($ronda) . "')" ;
				$conDB->SQL($sql);
			}
			
			
			header ("location: simulacion.php");
			}	
		}

	if ($unSesion->obtenerVariable ('idEscenario') && isset ($_POST['button2']) && $_POST['button2'] == 'Guardar') {
		$tmplistComprador = serialize($unSesion->obtenerVariable ('listComprador'));
		$tmplistDestino   = serialize($unSesion->obtenerVariable ('listDestino'));
		$tmplistVendedor  = serialize($unSesion->obtenerVariable ('listVendedor'));
		$tmpTransporte    = serialize($unSesion->obtenerVariable ('transporte'));
		$tmpDemanda       = serialize($unSesion->obtenerVariable ('listDemanda'));
		$tmpRondas        = $unSesion->obtenerVariable ('rondas');
				
		$sql = "UPDATE escenario SET list_comprador = '" . $tmplistComprador . "', list_vendedor = '" . $tmplistVendedor . "', list_transporte = '" . $tmpTransporte . "', list_destino = '" . $tmplistDestino . "', list_demanda = '" . $tmpDemanda . "', ronda = '" . count($tmpRondas) . "' WHERE id = "  . $unSesion->obtenerVariable ('idEscenario') ;
		$conDB->SQL($sql);
		if (mysql_affected_rows () == 1) {
			$sql = "delete from escenario_ronda where id_escenario = " . $unSesion->obtenerVariable ('idEscenario');
			$conDB->SQL($sql);
			foreach ($tmpRondas as $llaveRonda => $ronda) {
				$sql = "INSERT INTO escenario_ronda (id_escenario, id_ronda, ronda) values ('" . $unSesion->obtenerVariable ('idEscenario') . "','" . $llaveRonda . "','" . serialize($ronda) . "')" ;
				$conDB->SQL($sql);
			}
			
			header ("location: simulacion.php");
			}
		else {
			echo "<script>alert ('No hay cambios en el escenario actual'); location.href='simulacion.php'; </script>";
			}
		}
		
		
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	if (!$unSesion->obtenerVariable ('idEscenario'))
		include BASETPL . 'guardar.php';
	else 
		include BASETPL . 'guardarDos.php';
			
			
	include BASETPL . 'pie.php';		
		 
//	echo "<pre>";
//	print_r ($tmpIdCampo);
//	print_r ($tmpIdEmpresa);
//	print_r ($_SESSION);
//	print_r ($_POST);
//	echo "</pre>"; 
?>