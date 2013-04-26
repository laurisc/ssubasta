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
	
	if ((isset ($_POST['siguiente']) && $_POST['siguiente'] = 'siguiente') || $unSesion->obtenerVariable ('transporte')) {
		if (empty($_POST['ch_comprador'])) {
			// si no es enviado el el formulario por ejemplo si se da clic en atras carga la lista de compraddores y demanda de la sesion
			$_POST['ch_comprador'] = array_keys ($unSesion->obtenerVariable ('listComprador')); 
		}
		else {
			// si es enviado el formulario desde completarCompradores se recorre todos los campos basado en los checkbox seleccionados
			// se crea un array con los datos llenos en la pagina completarCompradores
			$unSesion->eliminarVariable ('listComprador');	
			$unSesion->eliminarVariable ('listDestino');	
			$unSesion->eliminarVariable ('listDemanda');	
			$tmpArrayComprador = array();
			$tmpArrayDestino = array();
			$tmpArrayDemanda = array();
			
			
			foreach ($_POST['ch_comprador'] as $valor ) {
				$tmpArrayComprador[$valor] = $valor;
				$tmpArrayDestino[$valor] = $_POST['ciudad'][$valor];
				$tmpArrayDemanda[$valor]['tipo'] = $_POST['tipoComprador'][$valor];
    			$tmpArrayDemanda[$valor]['demanda'] = $_POST['demanda'][$valor];
    			$tmpArrayDemanda[$valor]['dFirme'] = $_POST['dFirme'][$valor];
    			$tmpArrayDemanda[$valor]['elasticidadFirme'] = $_POST['elasticidadFirme'][$valor];
    			$tmpArrayDemanda[$valor]['dCfcU'] = (isset($_POST['dCfcU'][$valor])) ? $_POST['dCfcU'][$valor] : '';
    			$tmpArrayDemanda[$valor]['elasticidadDcfc'] = (isset($_POST['elasticidadDcfc'][$valor])) ? $_POST['elasticidadDcfc'][$valor] : '';
    			$tmpArrayDemanda[$valor]['dOcgU'] = (isset($_POST['dOcgU'][$valor])) ? $_POST['dOcgU'][$valor] : '';
    			$tmpArrayDemanda[$valor]['elasticidadDocg'] = (isset($_POST['elasticidadDocg'][$valor])) ? $_POST['elasticidadDocg'][$valor] : '';
    			$tmpArrayDemanda[$valor]['dFirmeC'] = $_POST['dFirmeC'][$valor];
    			$tmpArrayDemanda[$valor]['elasticidadDfirmeC'] = (isset($_POST['elasticidadDfirmeC'][$valor])) ? $_POST['elasticidadDfirmeC'][$valor] : '';
    			$tmpArrayDemanda[$valor]['dCfcC'] = (isset($_POST['dCfcC'][$valor])) ? $_POST['dCfcC'][$valor] : '';
    			$tmpArrayDemanda[$valor]['elasticidadDcfcC'] = (isset($_POST['elasticidadDcfcC'][$valor])) ? $_POST['elasticidadDcfcC'][$valor] : '';
    			$tmpArrayDemanda[$valor]['dOcgC'] =(isset( $_POST['dOcgC'][$valor])) ? $_POST['dOcgC'][$valor] : '';
    			$tmpArrayDemanda[$valor]['elasticidadDocgC'] = (isset( $_POST['elasticidadDocgC'][$valor])) ? $_POST['elasticidadDocgC'][$valor] : '';
			}
							
			$unSesion->registrarVariable ('listComprador', $tmpArrayComprador);	
			$unSesion->registrarVariable ('listDestino', $tmpArrayDestino);
			$unSesion->registrarVariable ('listDemanda', $tmpArrayDemanda);	
		}			
			
		$tmpArrayVendedor  = $unSesion->obtenerVariable ('listVendedor');
		$tmpArrayComprador = $unSesion->obtenerVariable ('listComprador');
		$tmpArrayDestino   = $unSesion->obtenerVariable ('listDestino');
		
		foreach ($tmpArrayVendedor as $tmpMa) {		
			$datoMatriz = explode ("|", $tmpMa['id']);
			$tmpIdCampo[] = $datoMatriz[0];
			$tmpIdEmpresa[] = $datoMatriz[1];
			}

		// Consulta segun los compradores
		// $sql = "SELECT  DISTINCT ciudad.* FROM comprador, ciudad WHERE id_ciudad in (" . implode ("," , $tmpArrayComprador) . ") AND ciudad.id = id_ciudad ORDER BY ciudad.ciudad";
		$datoDestinos = array ();
		foreach ($tmpArrayDestino as $tmpDes) {
			if (isset ($tmpDes))
				foreach ($tmpDes as $tmpDesT) {	
					$datoDestinos[] .= $tmpDesT;
					}
			} 
	
		$sql = "SELECT * FROM ciudad WHERE id in (" . implode (',', array_unique($datoDestinos)) . ") order by ciudad";
		$resCiudad = $conDB->SQL($sql);//mysql_query ($sql);

		$sql = "SELECT * FROM campo WHERE id in (" . implode ("," , $tmpIdCampo) . ") ORDER BY nombre";	
		$resCampo = $conDB->SQL($sql); //mysql_query ($sql);
		
		$linea .= '<thead>
				<tr>
					<th> Localización </th>';
		$tmpResCampo = 	$resCampo;	
		while ($row = mysql_fetch_object ($resCampo)) {
			$linea .= '<th> ' . $row->nombre . ' </th>';
		}
					
		$linea .= '</tr></thead><tbody>';
		
		while ($row = mysql_fetch_object ($resCiudad)) {
			$linea .= '<tr>';
			$linea .= '<td> ' . $row->ciudad . ' </td>';
			$sql = "SELECT * FROM campo WHERE id in (" . implode ("," , $tmpIdCampo) . ") ORDER BY nombre";	
			$resCampo = mysql_query ($sql);
			$tmpArrayTransporte = $unSesion->obtenerVariable ('transporte');
			while ($rowC = mysql_fetch_object ($resCampo)) {
				$linea .= '<td> <input type="text" onkeyup="esCorrectoDoubleTres(this)" value="';
				$tmpValor = darValorTransporte($conDB, $row->id, $rowC->id);
				// se crea el nombre del campo  idCiudad | idCampo
				$linea .= (isset($tmpArrayTransporte[$row->id . '|' . $rowC->id])) ? $tmpArrayTransporte[$row->id . '|' . $rowC->id] : $tmpValor ;
				$linea .= '" class="input-small" name="' . $row->id . '|' . $rowC->id . '"> </td>';
				}			
			$linea .= '</tr>';
			
			}
			

		

	/*	
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
		$unSesion->registrarVariable ('listVendedor', $tmpArrayVendedor); */
		}
	
		function darValorTransporte ($conDB, $idCiudad, $idCampo) {
			$sql = "SELECT * FROM transporte WHERE id_campo = " . $idCampo . " AND id_ciudad = " . $idCiudad ;
			$res = $conDB->SQL($sql);
			$row = mysql_fetch_object ($res);
			return $row->valor;
			}

	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'transporte.php';
	include BASETPL . 'pie.php';
	
	$conDB->desconectar ();
//	echo "<pre>";
//	print_r ($unSesion->obtenerVariable ('listDemanda'));
//	print_r ($_SESSION);
//	print_r ($_POST);
//	echo "</pre>";	
?>