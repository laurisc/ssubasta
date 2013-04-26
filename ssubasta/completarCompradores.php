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
	
	//Guarda las variables del resumen
	$tVendedores = $unSesion->obtenerVariable ('tVendedores');
	$tCampos = $unSesion->obtenerVariable ('tCampos');
	$tCompradores = $unSesion->obtenerVariable('listComprador');
	$tCompradores = isset($tCompradores) ?  count($tCompradores) : 0;

	//Busca todas las ciudades
	$sql = "SELECT * FROM `ciudad` order by `ciudad`";
	$res = $conDB->SQL($sql);
	while ($row = mysql_fetch_object ($res)) {
		$tmpArrayCiudad[$row->id] = $row->ciudad;
	}
	
	$unCiudad = new ciudad ($tmpArrayCiudad);
	$selectCiudad = $unCiudad->creaOptionSelect (0);
	

	// Carga los datos de vendedores en la variable de sesion
	if (!empty($_POST['ch_vendedor']) || $unSesion->obtenerVariable ('listVendedor')) {
		if (empty($_POST['ch_vendedor'])) {
			$tmpArrayVendedor = $unSesion->obtenerVariable ('listVendedor');
			foreach ($_POST as $llave => $valor) {
				$asig = explode ("-", $llave);
				switch ($asig[0]) {
					case 'fijo':		$tmpArrayVendedor[$asig[1]]['fijo'] = $valor; break;
					case 'condicional': $tmpArrayVendedor[$asig[1]]['condicional'] = $valor; break;
					case 'opcional':	$tmpArrayVendedor[$asig[1]]['opcional'] = $valor; break;
					case 'fijoU':		$tmpArrayVendedor[$asig[1]]['fijoU'] = $valor; break;
					case 'condicionalU':$tmpArrayVendedor[$asig[1]]['condicionalU'] = $valor; break;
					case 'opcionalU':	$tmpArrayVendedor[$asig[1]]['opcionalU'] = $valor; break;
					case 'firme':		$tmpArrayVendedor[$asig[1]]['firme'] = $valor; break;
					case 'cfc':			$tmpArrayVendedor[$asig[1]]['cfc'] = $valor; break;
					case 'ocg':			$tmpArrayVendedor[$asig[1]]['ocg'] = $valor; break;
					case 'firmeU':		$tmpArrayVendedor[$asig[1]]['firmeU'] = $valor; break;
					case 'cfcU':		$tmpArrayVendedor[$asig[1]]['cfcU'] = $valor; break;
					case 'ocgU':		$tmpArrayVendedor[$asig[1]]['ocgU'] = $valor; break;
				}	
			}
			$unSesion->registrarVariable ('listVendedor', $tmpArrayVendedor);
		} 
	}
	
		
	//Escribe los compradores
	$sql = "SELECT comprador.* FROM `comprador` WHERE comprador.padre in (184, " . $unSesion->obtenerVariable('id') . ")";
	$res = $conDB->SQL($sql);
	$contador = 1;
	$tmpComprador = '';
	$tmpDestino = ($unSesion->obtenerVariable('listDestino')) ? $unSesion->obtenerVariable('listDestino') : array();
	$tmpDem = ($unSesion->obtenerVariable('listDemanda')) ? $unSesion->obtenerVariable('listDemanda') : array();
	while ($row = mysql_fetch_object ($res)) {
		$tmpComprador .= '<tr id="fila' . $contador . '">';
		$tmpComprador .= '<td> ' . $contador . ' </td>';
		$tmpComprador .= '<td> <input type="checkbox" name="ch_comprador[]" onclick="resumenEscenario ()" id="ch_comprador' . $contador . '" value="' . $row->id . '"';
		$tmp = $unSesion->obtenerVariable('listComprador');
		$tmpBuscar = $row->id;
		$tmpComprador .= (isset ($tmp[$tmpBuscar])) ? ' checked="checked" ' : '' ;	
		$tmpComprador .= ' /><label for="checkbox"></label> </td>';
		
		$tmpComprador .= '<td> ' . $row->nombre . ' </td>';
		$tmpTipo = (isset($tmpDem[$row->id]['tipo'])) ? $tmpDem[$row->id]['tipo'] : 'A';
		$tmpComprador .= '<td> ' . creaSelectTipo ($row->id, $tmpTipo) . ' </td>';
		$tmpComprador .= '<td> <input name="demanda[' . $row->id . ']" class="input-mini" id="demanda' . $contador .'" type="text" onkeyup="esCorrectoDouble(this)" value="';
		$tmpComprador .= (isset ($tmpDem[$row->id]['demanda'])) ? $tmpDem[$row->id]['demanda'] : '';
		$tmpComprador .= '"> </td>';
		
		# crea los select de ciudades necesarios
		$tmpComprador .= '<td>';
		if (isset ($tmpDestino[$row->id])) {
			$totalLineas = count ($tmpDestino[$row->id]);
			$complemento = false ;
			foreach ($tmpDestino[$row->id] as $tmpD ) {
				$tmpComprador .= '<select name="ciudad[' . $row->id . '][]" id="ciudad' . $contador . $complemento . '"> ' . $unCiudad->creaOptionSelect ($tmpD) . ' </select> ';	
				$tmpComprador .= '<span style="height:20px;margin:10px;display:block" id="span' . $contador . $complemento . '"> &nbsp; </span> ';
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;	
					 
			}
		}
		else {
			$tmpComprador .= '<select name="ciudad[' . $row->id . '][]" id="ciudad' . $contador . '"> ' . $selectCiudad . ' </select> ';	
			$tmpComprador .= '<span style="height:20px;margin:10px;display:block"> &nbsp; </span> ';
			$totalLineas = 1;
		}
		$tmpComprador .= '</td>';
		
		$tmpComprador .= '<input type="hidden" name="cantidadFilas' . $row->id . '" id="cantidadFilas' . $row->id . '" value="' . $totalLineas . '"> </input>';
		
		$tmpComprador .= '<td><a href="javascript:void(0);" onclick="agregarCiudad(' . $row->id . ', ' . $contador . ')" rel="tooltip" class="icon-plus" data-original-title="Agregar otra ciudad."></a>
		<a href="javascript:void(0);" onclick="eliminarCiudad(' . $row->id . ', ' . $contador . ')" rel="tooltip" class="icon-remove" data-original-title="Eliminar un campo."></a></td>';			
		$tmpComprador .= ' </td>';
		
		$tmpComprador .= '<td>';			
		$tmpComprador .= '<small><b>Cantidad</b></small></br></br>';
		$tmpComprador .= '<small><b>Elasticidad</b></small>';
		$tmpComprador .= '</td>';			
		
		if (isset ($tmpDem[$row->id])) {
			$tmpComprador .= '<td>';
			$complemento = false ;
			foreach ($tmpDem[$row->id]['dFirme'] as $tmpD ) {
				$tmpComprador .= '<input type="text" class="input-mini" id="dFirmeU' . $row->id . $complemento . '" name="dFirme[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
				$tmpComprador .= (isset ($tmpDem[$row->id]['dFirme'])) ? $tmpD : '' ;
				$tmpInE = 0;
	
				$tmpComprador .= '"> ' . creaSelectElasticidad ('elasticidadFirme[' . $row->id . '][]', $tmpDem[$row->id]['elasticidadFirme'][$tmpInE], '',$complemento);
				$tmpInE++;
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;	
									
				if ($tmpDem[$row->id]['tipo'] == 'A') {
					$disableA = ' disabled="true" ' ;
					$disableB = '';
					$tmpDem[$row->id]['dOcgU'][] = '';
					$tmpDem[$row->id]['dOcgC'][] = '';
					}
				else {
					$disableB = ' disabled="true" ';
					$disableA = '';					
					$tmpDem[$row->id]['dCfcU'][] = '';	
					$tmpDem[$row->id]['dCfcC'][] = '';	
					}
				
				}
			$tmpComprador .= '	 </td>';
			$tmpComprador .= '<td>';
			/* crea los select necesario para CFC a un a�o */
			$complemento = false;
			foreach ($tmpDem[$row->id]['dCfcU'] as $tmpD ) {
				$tmpComprador .= ' <input type="text" class="input-mini" id="dCfcU' . $row->id . $complemento . '" name="dCfcU[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" ' . $disableB . ' value="';
				$tmpComprador .= (isset ($tmpDem[$row->id]['dCfcU'])) ? $tmpD : '';
				$tmpInE = 0;
				$tmpDem[$row->id]['elasticidadDcfc'][$tmpInE] = (isset ($tmpDem[$row->id]['elasticidadDcfc'][$tmpInE])) ?$tmpDem[$row->id]['elasticidadDcfc'][$tmpInE] : 0;
				$tmpComprador .= '"> ' . creaSelectElasticidad ('elasticidadDcfc[' . $row->id . '][]', $tmpDem[$row->id]['elasticidadDcfc'][0] , $disableB, $complemento);
				$tmpInE++;
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;	
				
				}
			$tmpComprador .= '	 </td>';
			$tmpComprador .= '<td>';
			/* crea los select necesario para OCG a un a�o */
			$complemento = false ;
			foreach ($tmpDem[$row->id]['dOcgU'] as $tmpD ) {
				$tmpComprador .= '<input type="text" class="input-mini" id="dOcgU' . $row->id . $complemento .'" name="dOcgU[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" ' . $disableA . ' value="';
									
				$tmpComprador .= (isset ($tmpDem[$row->id]['dOcgU'])) ? $tmpD : '';
				$tmpInE = 0;
				$tmpDem[$row->id]['elasticidadDocg'][$tmpInE] = (isset ($tmpDem[$row->id]['elasticidadDocg'][$tmpInE])) ?$tmpDem[$row->id]['elasticidadDocg'][$tmpInE] : 0;
				$tmpComprador .= '"> ' . creaSelectElasticidad ('elasticidadDocg[' . $row->id . '][]', $tmpDem[$row->id]['elasticidadDocg'][$tmpInE], $disableA, $complemento);
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;				
				$tmpInE++;
				}
			$tmpComprador .= '	 </td>';
			$tmpComprador .= '<td>';
			/* crea los select necesario para FIRME a cinco a�os */	
			$complemento = false ;		
			foreach ($tmpDem[$row->id]['dFirmeC'] as $tmpD ) {
				$tmpComprador .= '<input type="text" class="input-mini" id="dFirme' . $row->id . $complemento . '" name="dFirmeC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			
				$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpD : '';
				$tmpInE = 0;
				$tmpDem[$row->id]['elasticidadDfirmeC'][$tmpInE] = (isset ($tmpDem[$row->id]['elasticidadDfirmeC'][$tmpInE])) ?$tmpDem[$row->id]['elasticidadDfirmeC'][$tmpInE] : 0;				
				$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDfirmeC[' . $row->id . '][]', $tmpDem[$row->id]['elasticidadDfirmeC'][$tmpInE], '', $complemento);
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;					
				$tmpInE++;
				}
			$tmpComprador .= '	</td>';	
			$tmpComprador .= '<td>'; 
			/* crea los select necesario para CFC a cinco a�os */			
			$complemento = false ;
			foreach ($tmpDem[$row->id]['dCfcC'] as $tmpD ) {				
				$tmpComprador .= ' <input type="text" class="input-mini" id="dCfc' . $row->id . $complemento . '" name="dCfcC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" ' . $disableB . ' value="';
				$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpD : '';
				$tmpInE = 0;
				$tmpDem[$row->id]['elasticidadDcfcC'][$tmpInE] = (isset ($tmpDem[$row->id]['elasticidadDcfcC'][$tmpInE])) ?$tmpDem[$row->id]['elasticidadDcfcC'][$tmpInE] : 0;
				$tmpComprador .= '"> ' . creaSelectElasticidad ('elasticidadDcfcC[' . $row->id . '][]', $tmpDem[$row->id]['elasticidadDcfcC'][$tmpInE], $disableB, $complemento);
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;					
				$tmpInE++;
			}
			$tmpComprador .= '</td>';
			$tmpComprador .= '<td>';
			/* crea los select necesario para OCG a cinco a�os */
			$complemento = false ;
			foreach ($tmpDem[$row->id]['dOcgC'] as $tmpD ) {					
				$tmpComprador .= ' <input type="text" class="input-mini" id="dOcg' . $row->id . $complemento . '" name="dOcgC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" ' . $disableA . ' value="';
				$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpD : '';
				$tmpInE = 0;
				$tmpDem[$row->id]['elasticidadDocgC'][$tmpInE] = (isset ($tmpDem[$row->id]['elasticidadDocgC'][$tmpInE])) ?$tmpDem[$row->id]['elasticidadDocgC'][$tmpInE] : 0;
				$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDocgC[' . $row->id . '][]', 0, $disableA, $complemento); 
				if (!$complemento) {
					$complemento = 2 ;
					}
				else
					$complemento++;				
				$tmpInE++;
				} 
			$tmpComprador .= '</td>';	
			}
		else {
			$tmpComprador .= '<td> <input type="text" class="input-mini" id="dFirmeU' . $row->id . '" name="dFirme[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dFirme'])) ? $tmpDem[$row->id]['dFirme'] : '' ;
			$tmpComprador .= '"> ' . creaSelectElasticidad ('elasticidadFirme[' . $row->id . '][]', 0) . ' </td>';
			
			$tmpComprador .= '<td><input type="text" class="input-mini" id="dCfcU' . $row->id . '" name="dCfcU[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dCfcU'])) ? $tmpDem[$row->id]['dCfcU'] : '';
			$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDcfc[' . $row->id . '][]', 0) . ' </td>';
			
			$tmpComprador .= '<td><input type="text"  class="input-mini" disabled="true" id="dOcgU' . $row->id . '" name="dOcgU[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dOcgU'])) ? $tmpDem[$row->id]['dOcgU'] : '';
			$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDocg[' . $row->id . '][]', 0, 'disabled="true"') . ' </td>';
			
			$tmpComprador .= '<td><input type="text" class="input-mini" id="dFirmeC' . $row->id . '" name="dFirmeC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpDem[$row->id]['dFirmeC'] : '';
			$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDfirmeC[' . $row->id . '][]', 0) . ' </td>';
			
			$tmpComprador .= '<td><input type="text" class="input-mini" id="dCfc' . $row->id . '" name="dCfcC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpDem[$row->id]['dFirmeC'] : '';
			$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDcfcC[' . $row->id . '][]', 0) . ' </td>';
			
			$tmpComprador .= '<td><input type="text" class="input-mini" disabled="true" id="dOcg' . $row->id . '" name="dOcgC[' . $row->id . '][]" onkeyup="esCorrectoDouble(this)" value="';
			$tmpComprador .= (isset ($tmpDem[$row->id]['dFirmeC'])) ? $tmpDem[$row->id]['dFirmeC'] : '';
			$tmpComprador .= '">' . creaSelectElasticidad ('elasticidadDocgC[' . $row->id . '][]', 0, 'disabled="true"') . ' </td>'; 			
			}

		$tmpComprador .= '</tr>';
		$contador++;
	}
	
	
	function creaSelectTipo ($id, $tipo) {
		$select = '<select id="tipoComprador-'. $id . '" name="tipoComprador['. $id . ']" class="input-mini" onchange="actualizar(this)">';
		$select .= '<option';
		$select .='> A </option>';
		$select .= ($tipo == 'A') ? ' selected="selected" ' : '';
		$select .= '<option';
		$select .= ($tipo == 'B') ? ' selected="selected" ' : '';
		$select .='> B </option></select>' ;
		return $select;
	}
	
	function creaSelectElasticidad ($nombre, $valor, $estado = '', $complemento = '') {
//		echo $complemento ;
		$tmpIdTag  = explode ("[", $nombre) ;
		$tmpIdTagT = explode ("]", $tmpIdTag[1]) ;
		$nombreId =   $tmpIdTag[0] . $tmpIdTagT[0] . $complemento;
		$select  = '<input type="text" class="input-mini" ' . $estado . ' name="' . $nombre . '" id="' . $nombreId . '" value="' . $valor . '" onkeyup="esCorrectoDoubleHastaTres(this)">';
		$select .= '</input>';
		return $select;
		}

	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'completar_compradores.php';
	include BASETPL . 'pie.php';
	$conDB->desconectar ();

//	echo "<pre>";
//	print_r ($_SESSION);
//	print_r ($_POST);
//	echo "</pre>";	
?>