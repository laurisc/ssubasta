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
	
	$linea = '';

	//ESTO NO ES POSIBLE ELIMINARLO YA?
	// Borra todas las variables de sesión relacionadas la configuracion, si usuario crea nuevo escenario
	if (isset($_GET['nuevo']) && $_GET['nuevo'] ) {	
		$unSesion->eliminarVariable('escenarios');
		$unSesion->eliminarVariable('listComprador');
		$unSesion->eliminarVariable('listVendedor');
		$unSesion->eliminarVariable('listDestino');
		$unSesion->eliminarVariable('transporte');
		$unSesion->eliminarVariable('idEscenario');
	}
	
	if (!empty($_POST['tVendedoresH'])) 
		$unSesion->registrarVariable ('tVendedores', $_POST["tVendedoresH"] );
	if (!empty($_POST['tCamposH'])) 
		$unSesion->registrarVariable ('tCampos', $_POST['tCamposH'] );
	
	if (!empty($_POST['ch_vendedor']) || $unSesion->obtenerVariable ('listVendedor')) {
		if (empty($_POST['ch_vendedor']))
			$_POST['ch_vendedor'] = array_keys ($unSesion->obtenerVariable ('listVendedor'));
			

		$contador = 1;
		foreach ($_POST['ch_vendedor'] as $valor) {
			$tmpDato = explode ("|", $valor);
			$sql = "SELECT campo.id as cid, campo.nombre as cn, empresa.id as eid, empresa.nombre as en FROM `campo`, `empresa`, `campo_empresa` WHERE campo.id = campo AND empresa.id = empresa AND campo_empresa.campo = " . $tmpDato[0] . " AND campo_empresa.empresa = " . $tmpDato[1];
			$res = $conDB->SQL($sql);
			$row = mysql_fetch_object ($res);
			$tmp = $unSesion->obtenerVariable('listVendedor');
			$tmp[$valor] = (isset($tmp[$valor])) ? $tmp[$valor] : array();
			$linea .= darLineaVendedor($contador, $row, $valor, $tmp[$valor] );
			$linea .= "<script>sumaTotales('" . $valor . "')</script>";

			$contador++;
		}
		
 		guardaDatosEscenario ($unSesion, $_POST);	
	}
	
	function darLineaVendedor($contador, $row, $tmpNombre, $tmp) {
		$linea = '	<tr >
			<td rowspan="2"> ' . $contador . ' </td>
			<td> ' . $row->en . ' </td>
			<td> ' . $row->cn . ' </td>
			<td><span id="sPtdvf-' . $tmpNombre . '">0</span>  <input name="ptdvf-' . $tmpNombre . '" type="hidden" id="ptdvf-' . $tmpNombre . '" value="0" /> </td>
			<td> <small><b>Cantidad</b></small> <a target="_blank" rel="tooltip" class="icon-info-sign" data-original-title="Cada unidad por cantidad corresponde a 100 MBTU"> </a> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['fijo'])) ? $tmp['fijo'] : 0;
		$linea .= '" class="input-mini" id="fijo-' . $tmpNombre . '" name="fijo-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDigitos(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['condicional'])) ? $tmp['condicional'] : 0;
		$linea .='" class="input-mini" id="condicional-' . $tmpNombre . '" name="condicional-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDigitos(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['opcional'])) ? $tmp['opcional'] : 0;
		$linea .='" class="input-mini" id="opcional-' . $tmpNombre . '"  name="opcional-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDigitos(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['fijoU'])) ? $tmp['fijoU'] : 0;	
		$linea .='" class="input-mini" id="fijoU-' . $tmpNombre . '" name="fijoU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDigitos(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['condicionalU'])) ? $tmp['condicionalU'] : 0;
		$linea .='" class="input-mini" id="condicionalU-' . $tmpNombre . '" name="condicionalU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDigitos(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['opcionalU'])) ? $tmp['opcionalU'] : 0;
		$linea .='" class="input-mini" id="opcionalU-' . $tmpNombre . '" name="opcionalU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\', this)"> </td>';
		$linea .= '</tr> <tr>
		<th> &nbsp; </th>
		<th> &nbsp; </th>
		<th> &nbsp; </th>
		<th> <small><b>Precio</b></small> <a target="_blank" rel="tooltip" class="icon-info-sign" data-original-title="El precio esta dado por USD/MBTU"> </a></th>';
		$linea .=' <td> <input type="text" value="';
		$linea .= (isset($tmp['firme'])) ? $tmp['firme'] : 0;	
		$linea .='" class="input-mini" id="firme-' . $tmpNombre . '" name="firme-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)">  </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['cfc'])) ? $tmp['cfc'] : 0;
		$linea .='" class="input-mini" id="cfc-' . $tmpNombre . '" name="cfc-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['ocg'])) ? $tmp['ocg'] : 0;	
		$linea .='" class="input-mini" id="ocg-' . $tmpNombre . '" name="ocg-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)"> </td>	';
		$linea .=' <td> <input type="text" value="';
		$linea .= (isset($tmp['firmeU'])) ? $tmp['firmeU'] : 0;	
		$linea .='" class="input-mini" id="firmeU-' . $tmpNombre . '" name="firmeU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['cfcU'])) ? $tmp['cfcU'] : 0;
		$linea .='" class="input-mini" id="cfcU-' . $tmpNombre . '" name="cfcU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)"> </td>
			<td> <input type="text" value="';
		$linea .= (isset($tmp['ocgU'])) ? $tmp['ocgU'] : 0;	
		$linea .='" class="input-mini" id="ocgU-' . $tmpNombre . '" name="ocgU-' . $tmpNombre . '" onChange="sumaTotales (\'' . $tmpNombre . '\')" onkeyup="esCorrectoDouble(this)"> </td>	';	
		$linea .='	</tr>';
		return $linea;			
	}
	
	function guardaDatosEscenario ($unSesion, $_TMPPOST) {
		$_POST = $_TMPPOST;
		foreach ($_POST['ch_vendedor'] as $vendedor) {
			$tmpArrayVendedor[$vendedor]['id'] = $vendedor;
			if ($unSesion->obtenerVariable('idEscenario') || $unSesion->obtenerVariable('listVendedor') ) {
				$tmp = $unSesion->obtenerVariable('listVendedor');
				$tmpArrayVendedor[$vendedor] = (isset($tmp[$vendedor])) ? $tmp[$vendedor] : '';
				$tmpArrayVendedor[$vendedor]['id'] = $vendedor;
			}			
		}

		$unSesion->registrarVariable ('listVendedor', $tmpArrayVendedor);
	}
		
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'completar_vendedores.php';		
	include BASETPL . 'pie.php';		
	
	$conDB->desconectar ();
	
?>