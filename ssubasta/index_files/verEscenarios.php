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
	
	$sql = "SELECT * FROM escenario where id_usuario = " . $unSesion->obtenerVariable ('id');
	$res = $conDB->SQL($sql);
	$unSesion->eliminarVariable('numeroRonda');
	$filasEscenarios = '';
	if (mysql_num_rows ($res) >= 1) {
		$contadorEscenario = 0;
		while ($row = mysql_fetch_object ($res)) {
			$tmpEscenario[$contadorEscenario]['listComprador'] = unserialize($row->list_comprador);
			$tmpEscenario[$contadorEscenario]['listVendedor'] = unserialize($row->list_vendedor);
			$tmpEscenario[$contadorEscenario]['transporte'] = unserialize($row->list_transporte);
			$tmpEscenario[$contadorEscenario]['listDestino'] = unserialize($row->list_destino);
			$tmpEscenario[$contadorEscenario]['listDemanda'] = unserialize($row->list_demanda);
			$tmpEscenario[$contadorEscenario]['idEscenario'] = $row->id;
			$tmpEscenario[$contadorEscenario]['numeroRonda'] = $row->ronda;
			
			$sql = "select * from escenario_ronda where id_escenario = " . $row->id;
			$resRondas = $conDB->SQL($sql);
			while ($rowRonda = mysql_fetch_object ($resRondas)) {
				$tmpEscenario[$contadorEscenario]['rondas'][$rowRonda->id_ronda] = unserialize($rowRonda->ronda);
				}
				
/*			echo "<pre>";
			print_r ($tmpEscenario[$contadorEscenario]['rondas']);
			echo "</pre>";  */
			
			$ptdvf = 0;
			foreach ($tmpEscenario[$contadorEscenario]['listVendedor'] as $tmpIdArr) {
				$tmpId = explode ('|', $tmpIdArr['id']);
				$arr[] = $tmpId[0]; 
				$ptdvf += floatval ($tmpIdArr['fijo']) + floatval ($tmpIdArr['condicional']) + floatval ($tmpIdArr['opcional']) + floatval ($tmpIdArr['fijoU']) + floatval ($tmpIdArr['condicionalU']) + floatval ($tmpIdArr['opcionalU']) + floatval ($tmpIdArr['firme']) + floatval ($tmpIdArr['cfc']) + floatval ($tmpIdArr['ocg']) ;
				}
/*				
			echo "<pre>";
			print_r ($tmpEscenario[$contadorEscenario]['listVendedor']);
			echo "</pre>";				
			*/
			
			$filasEscenarios .= '				<tr>';
			$filasEscenarios .= '		<td> ' . $row->nombre . ' </td>';
			$filasEscenarios .= '		<td> ' . count ($tmpEscenario[$contadorEscenario]['listComprador']) . ' </td>';
			$filasEscenarios .= '		<td> ' . count ($tmpEscenario[$contadorEscenario]['listVendedor']) . ' </td>';
			$filasEscenarios .= '		<td> ' . count (array_unique($arr)) . ' </td>';
			$filasEscenarios .= '		<td> ' . $ptdvf . ' </td>';
			$filasEscenarios .= '		<td>  </td>';
			$filasEscenarios .= '		<td> <a class="btn" href="cargaEscenario.php?ver=' . $contadorEscenario . '&que=config"> Config. <a> </td>';
            $filasEscenarios .= '        <td> <a class="btn" href="cargaEscenario.php?ver=' . $contadorEscenario . '&que=ver"> Ver <a> </td>';
			$filasEscenarios .= '	</tr>';

			$contadorEscenario++;
			}
		$unSesion->registrarVariable('escenarios', $tmpEscenario)	;
		}
		
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'list_escenario.php';
	include BASETPL . 'pie.php';
	
	$conDB->desconectar ();
/*	echo "<pre>";
	print_r ($_SESSION);
	echo "</pre>"; */
?>