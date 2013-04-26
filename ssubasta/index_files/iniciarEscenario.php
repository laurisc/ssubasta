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
	require_once ( BASELIB . 'class.ciudad.php');

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	$unSesion->redirecInicioSesion ('../index.php') ;
	
	if (isset($_GET['nuevo']) && $_GET['nuevo'] ) {	
		$unSesion->eliminarVariable('escenarios');
		$unSesion->eliminarVariable('listComprador');
		$unSesion->eliminarVariable('listVendedor');
		$unSesion->eliminarVariable('listDestino');
		$unSesion->eliminarVariable('listDemanda');
		$unSesion->eliminarVariable('transporte');
		$unSesion->eliminarVariable('idEscenario');
		$unSesion->eliminarVariable('listCiudadesEscenario');
		$unSesion->eliminarVariable('numeroRonda');
		}
	
	$tCompradores =  ($unSesion->obtenerVariable('listComprador')) ? count($unSesion->obtenerVariable('listComprador')) : 0 ;
//	$tCompradores = isset($tCompradores) ?  count($tCompradores) : 0;
	
	//Esta setencia sql busca los vendedores
	$sql = "SELECT campo.id as cid, campo.nombre as cn, empresa.id as eid, empresa.nombre as en FROM `campo`, `empresa`, `campo_empresa` WHERE campo.id = campo and empresa.id = empresa AND empresa.padre in (184, " . $unSesion->obtenerVariable('id') . ")";
	$res = $conDB->SQL($sql);
	$contador = 1;
	$tmpVendedores = '';
	while ($row = mysql_fetch_object ($res)) {
		$tmpVendedores .= '<tr>';
		$tmpVendedores .= '<td> ' . $contador . ' </td>';
		$tmpVendedores .= '<td> ' . $row->cn . ' </td>';
		$tmpVendedores .= '<td> ' . $row->en . ' </td>';
		$tmpVendedores .= '<td> <input type="checkbox" name="ch_vendedor[]" onclick="resumenVendedor()" id="ch_vendedor' . $contador . '" value="' . $row->cid . '|' . $row->eid . '"';
		$tmp = $unSesion->obtenerVariable('listVendedor');
		$tmpBuscar = $row->cid . '|' . $row->eid;
		$tmpVendedores .= (isset ($tmp[$tmpBuscar])) ? ' checked="checked" ' : '' ;
		$tmpVendedores .= '  /><label for="checkbox"></label> </td>';
		$tmpVendedores .= '</tr>';
		$contador++;
	}
	
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'escenario.php';
	include BASETPL . 'pie.php';
	$conDB->desconectar ();
?>