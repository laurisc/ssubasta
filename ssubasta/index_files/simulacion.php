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
	require_once ( BASELIB . 'class.simulacion.php');

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
	$unSimulacion = new simulacion;
	
	$unSesion = new sesion ('sSubasta');
	$linea = '';
	
	$ofertaContratoFirme = '';
	$ofertaReglaExceso   = '';
	$rondasCorridas      = '';
	$ronda				 = 0;
	//NUEVO
	$rondas = array();
	
	//Se crean los productos necesarios a incluir en la subasta
	$productos[] = 'firme';
	$productos[] = 'condicional';
	$productos[] = 'opcional';
	$productos[] = 'firmeC';
	$productos[] = 'condicionalC';
	$productos[] = 'opcionalC';
			
	// validamos si la variable ronda no existe, si existe la recumperamos e incrementamos en 1 cuando se corra la siguiete ronda de lo contrario iniciamos  
	// con la ronda cero, se guarda el valor de la ronda en sesion.
	if (!$unSesion->obtenerVariable ('numeroRonda')) {
		$unSesion->registrarVariable ('numeroRonda', 0);
		$ronda = 0;
		}
	else
		$ronda = $unSesion->obtenerVariable ('numeroRonda');
	
	
//	echo "ronda " . $ronda  . "<br />";
	
	// guardamos los datos de la matriz de tranporte si son enviados desde el paso anterior 		
	if (isset ($_POST['button']) && $_POST['button']) {
		
		$tmpArrayTransporte = array();
		foreach ($_POST as $llave => $valor) {
			if ($valor != 'siguiente')
				$tmpArrayTransporte[$llave] =  $valor;
			}
			
		$unSesion->registrarVariable ('transporte', $tmpArrayTransporte);	
		
		$tmpArrayVendedor = $unSesion->obtenerVariable ('listVendedor');
		$unSesion->registrarVariable ('listaVendedorModificables', $tmpArrayVendedor);
	}
	
	
	// si hay un id escenario en sesion damos la opcion de guardar los cambios de lo contrario se indica que se guardara nuevo escenario
	if (!$unSesion->obtenerVariable ('idEscenario'))	
		$guardarEscenario = 'Este escenario no se ha guardado clic <a href="#modalGuardar" role="button"  data-toggle="modal">aquí</a> para guardarlo';	
	else	
		$guardarEscenario = 'Clic <a href="#modalGuardar" role="button"  data-toggle="modal">aquí</a> para guardar cambios';

	
		
	/* Si es enviada la variable correrRonda se asume que se correra la siguiente ronda  */
	$entro = false;
	if (isset ($_POST['correrRonda']) ) {
	
		$unSesion->registrarVariable ('numeroRonda', $_POST['correrRonda']) ;	
		$ronda = $_POST['correrRonda'] ;

		$rondas = $unSesion->obtenerVariable('rondas');
		$rondaAnterior = $rondas[count($rondas)-1];
		$rondas[] = $rondaAnterior;
		$unSesion->registrarVariable ('rondas', $rondas) ;	
		$tmpArrayVendedor = $unSesion->obtenerVariable('listVendedor');
		
		
		foreach ($_POST['campoProductoProximaRonda'] as $llave => $valorTemp) {
			$tmp = explode("|", $valorTemp);
			$producto = $tmp[0];
			$idCampo = $tmp[1];

			$valorTmp = $_POST['tipoincremento'];
			$valor = $valorTmp[0];
			

			// si es porcentual y el campo se lleno con un valor se ejecuta el incremento
			if ($valor == 1 && $_POST['fila'][$llave] != ""  ) {
				$entro = true;
				$rondas = 
				$unSimulacion->darDemandaPorcentual ($rondas, $ronda, $tmpArrayVendedor, $_POST['fila'][$llave], $idCampo, $producto) ;
				}
			elseif ($valor == 2 && $_POST['fila'][$llave] != "" ) {
				$entro = true;
				$rondas = 
				$unSimulacion->darDemandaNominal ($rondas, $ronda, $tmpArrayVendedor, $_POST['fila'][$llave], $idCampo, $producto) ;
				}
			elseif ($valor == 3 && $_POST['fila'][$llave] != "" ) {
				$entro = true;
				$rondas = 
				$unSimulacion->darDemandaNuevoValor ($rondas, $ronda, $tmpArrayVendedor, $_POST['fila'][$llave], $idCampo, $producto) ;
				}
			elseif ($valor == 4) {
				$entro = true;
				$rondas = 
				$unSimulacion->darDemandaValorOptimo ($rondas, $ronda, $tmpArrayVendedor, $idCampo, $producto) ;
			}
		}
		
		
		
		if (!$entro && count ($rondas) > 1) {
			$pos = count ($rondas) - 1 ;
			unset ($rondas[$pos]) ;
			$ronda = $ronda - 1 ;
		} 
		
		$unSesion->registrarVariable ('rondas', $rondas) ;	
		$unSesion->registrarVariable ('numeroRonda', $ronda) ;		
		$unSesion->registrarVariable ('listVendedorModificables', $tmpArrayVendedor);
		header("location: simulacion.php") ;
	}



	// si es la ronda cero se genera la mejor oferta y demanda para la ronda
	// se busca la mejor oferta y la demanda para cada campo
	
/*	if ($unSesion->obtenerVariable('rondas') && !isset ($_POST['correrRonda']) ) {
		$rondas = $unSesion->obtenerVariable('rondas');
//		$unSesion->registrarVariable ('numeroRonda', ) ;
		}
	else {	*/
		if ($unSesion->obtenerVariable ('rondas') && !isset ($_POST['correrRonda']) ) {
			$rondas = $unSesion->obtenerVariable ('rondas') ;
			$ronda = count ($rondas) - 1  ;
		}

		if ($ronda == 0) {
			$rondas = $unSimulacion->darRondaCero($unSesion, $productos, $ronda);
			$unSesion->registrarVariable ('rondas', $rondas);
		}
		elseif($ronda > 0 && $entro) {
			$rondas = $unSesion->obtenerVariable ('rondas');
			$rondas = $unSimulacion->darDemandaBaseRondaCero($unSesion, $productos, $rondas, $ronda);
		}
	
//		$htmlOfertaGas 
		 
//		echo "<pre>";
//		print_r ($rondas);
//		echo "</pre>";
		
		
/*		$tmpArrayRondaOfertas = array();
		$tmpArrayCampoProductoEmpresa = array ();
		$tmpArrayCampoComercializadorCiudad = array ();
		
		$temp = $unSimulacion->darMejorOferta($tmpArrayVendedor, $tmpArrayRondaOfertas, $tmpArrayCampoProductoEmpresa);
		$mejorOferta = $temp[0];
		$tmpArrayRondaOfertas = $temp[1];
		$tmpArrayCampoProductoEmpresa = $temp[2];
		$tmpCampo = $unSimulacion->darOfertaDeGasTotal($tmpArrayVendedor);
	
		// se calcula la demanda por cada campo seleccionado en mejor oferta
		$temp = $unSimulacion->darDemandaRondaCero($mejorOferta, $unSesion );	
		$arrayDemanda = $temp[0];	
		$tmpArrayCampoComercializadorCiudad[$ronda] = $temp[1];	
		$arrayDemanda = $unSimulacion->validaDemandaNoEnOfertaCero ($mejorOferta, $arrayDemanda) ;

		$unSesion->registrarVariable ('tmpArrayRondaOfertas', $tmpArrayRondaOfertas) ;
		$unSesion->registrarVariable ('tmpArrayCampoProductoEmpresa', $tmpArrayCampoProductoEmpresa) ;
		$unSesion->registrarVariable ('tmpArrayCampoComercializadorCiudad', $tmpArrayCampoComercializadorCiudad) ; */
/*	elseif ($ronda >= 1) {
		$tmpArrayRondas = $unSesion->obtenerVariable ('rondas');
		$tmpArrayRondaOfertas = $unSesion->obtenerVariable ('tmpArrayRondaOfertas') ;
		$tmpArrayCampoProductoEmpresa = $unSesion->obtenerVariable ('tmpArrayCampoProductoEmpresa') ;
		$tmpArrayCampoComercializadorCiudad = $unSesion->obtenerVariable ('tmpArrayCampoComercializadorCiudad') ;
		
		$temp = $unSimulacion->darMejorOferta($tmpArrayVendedor, $tmpArrayRondaOfertas, $tmpArrayCampoProductoEmpresa);
		$mejorOferta = $temp[0];
		$tmpArrayRondaOfertas = $temp[1];
		$tmpArrayCampoProductoEmpresa = $temp[2];
		$tmpCampo = $unSimulacion->darOfertaDeGasTotal($tmpArrayVendedor);

		$unSesion->registrarVariable ('tmpArrayCampoProductoEmpresa', $tmpArrayCampoProductoEmpresa) ;
		$unSesion->registrarVariable ('tmpArrayRondaOfertas', $tmpArrayRondaOfertas) ;
		// se calcula la demanda por cada campo seleccionado en mejor oferta
		$temp = $unSimulacion->darDemandaRondaCero($mejorOferta, $unSesion );	
		$arrayDemanda = $temp[0];	
		$tmpArrayCampoComercializadorCiudad[$ronda] = $temp[1];
		//$arrayDemanda = $unSimulacion->validaDemandaNoEnOfertaCero ($mejorOferta, $arrayDemanda) ;

		$unSesion->registrarVariable ('tmpArrayCampoComercializadorCiudad', $tmpArrayCampoComercializadorCiudad) ;
		$tmpArrayCampoComercializadorCiudad = $unSesion->obtenerVariable ('tmpArrayCampoComercializadorCiudad');
		$unSimulacion->verificarDemanda(count($tmpArrayRondas), $mejorOferta, $arrayDemanda, $tmpArrayCampoComercializadorCiudad, $unSesion );
	} */


		/*
		*
		* seleccionamos los campos que estan en la simulacion
		*
		*/
		foreach ($unSesion->obtenerVariable ('listVendedor') as $itemCampo ) {
			$tmp = explode ('|', $itemCampo['id'] ) ;
			$tmpCampo[$tmp[0]] = $tmp[0];
			}
		
		$sql = "SELECT * FROM campo where id in (" . implode (", ", array_keys ($tmpCampo)) . ")";
		$res = $conDB->SQL($sql);
		
		while ($row = mysql_fetch_object ($res)) {
			$tmpListaCampo[$row->id] = $row->nombre;
		}
/*
		echo "<pre>";
		print_r ($tmpListaCampo);
		echo "</pre>"; */
		
		
//		$unSesion->registrarVariable ('listCiudadesEscenario', $tmpListaCampo);		
	
	
	$tmpCampo = $unSimulacion->darOfertaDeGasTotal($unSesion->obtenerVariable ('listVendedor')) ;
	
	// Imprime en pantalla la suma total por campo de la oferta de gas
	foreach ($tmpListaCampo as $llave => $valor) {
		$ofertaContratoFirme .= '			<tr>
					<td> ' . $valor . ' </td>
					<td> ' . $tmpCampo[$llave]['fijo'] . ' </td>
					<td> ' . $tmpCampo[$llave]['condicional'] . ' </td>
					<td> ' . $tmpCampo[$llave]['opcional'] . ' </td>
					<td> ' . $tmpCampo[$llave]['fijoU'] . ' </td>
					<td> ' . $tmpCampo[$llave]['condicionalU'] . ' </td>
					<td> ' . $tmpCampo[$llave]['opcionalU'] . ' </td>
				</tr>';		
		}
	
	
	// iniciamos una varible temporal con la mejor oferta para luego guarda en la base de datos todas las rondas corridas 
//	$rondas[$ronda]['mejorOferta'] = $mejorOferta;

	// inicia en la pocision 0 luego incrementara en 1 segun la ronda actual.
//	$rondas[$ronda]['demanda'] =  $arrayDemanda;

	// guardamos en sesion las rondas corridas asi como la mejor oferta
/*	if (isset ($_POST['correrRonda']) ) {
		$rondas = $unSimulacion->agregaRonda ($unSesion, $rondas) ;
		}
	else	
		$unSesion->registrarVariable ('rondas', $rondas);  */
	
	
	// se genera el HTML de la ronda actual
//	$tmpArrayRondaOfertas = $unSesion->obtenerVariable ('tmpArrayRondaOfertas') ;

	$ofertaReglaExceso    = $unSimulacion->darHtmlOfertaExceso ($rondas, $ronda, $tmpListaCampo, $productos, $unSesion) ;

//	$tmpHTML = $unSimulacion->generaHtml ($rondas, $ronda, $arrayExceso, $tmpListaCampo, $ofertaReglaExceso, $tmpArrayRondaOfertas );
//  $ofertaReglaExceso = $tmpHTML[0] ;
//	$arrayExceso = $tmpHTML[1] ;
//	$unSesion->registrarVariable ('arrayExceso', $arrayExceso); 
	// este foreach es para generar el HTML del historico	
	
	
	$rondasCorridas = $unSimulacion->generaHtmlHistorico ($rondas, $ronda, $tmpListaCampo ) ;	
	
//	$ofertaReglaExceso .= '			<tr>';	


	if (!$unSimulacion->validaExcesoCampo ($unSesion, $productos)) {
		$contrato = $unSimulacion->darContrato($unSesion, $conDB, $productos, $tmpListaCampo) ;
		}
	
		
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'simulacion.php';	
	include BASETPL . 'pie.php';
	
	$conDB->desconectar ();
//	unset ($_SESSION['escenarios']);
	echo "<pre>";
//	print_r ($tmpListaCampo);
//	print_r ($unSesion->obtenerVariable ('listDemanda'));
	print_r ($rondas[count ($rondas) - 1]);
//	print_r ($tmpArrayVendedor);
//	print_r ($_SESSION);
//	print_r ($_POST);
	echo "</pre>"; 
?>
