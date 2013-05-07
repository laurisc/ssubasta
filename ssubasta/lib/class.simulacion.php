<?php 
class simulacion {	
	/*
	* Este método calcula las demandas y ofertas para la primera ronda, a la que se refiere como Ronda 0.
	* Se escoge el menor precio de los ofertadores por campo y producto y se suma las ofertas que tengan ese mismo precio.
	* En el caso de las demandas se busca el menor costo (gas+transporte) para cada comprador_ciudad_producto.
	*/
	function darRondaCero($unSesion, $productos, $ronda) {
		unset ($_SESSION['rondas']) ;
		$tmpArrayVendedor = $unSesion->obtenerVariable ('listVendedor');
		$tmpArrayTransporte = $unSesion->obtenerVariable ('transporte');
		$tmpArrayDestino    = $unSesion->obtenerVariable ('listDestino');  
		$tmpArrayComprador  = $unSesion->obtenerVariable ('listComprador'); 
		$tmpArrayDemanda    = $unSesion->obtenerVariable ('listDemanda'); 
		
		$rondasCorridastmp = array();
		
		foreach($productos as $producto) {
		
			foreach($tmpArrayVendedor as $itemCampo) {
				$tmp = explode("|",$itemCampo['id']);
				$idCampo = $tmp[0];
				$idEmpresa = $tmp[1];
				
				$nombreValorProducto = $this->darNombreValorProductoReal2($producto);
				$nombreProducto = $this->darNombreProducto2($producto);
				
				if(!isset($rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['precio'])) {
					if(($itemCampo[$nombreProducto] > 0)) {
						//Si no hay nada se ubica la primera oferta como base
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['idCampo'] = $idCampo;
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['exceso'] = false;
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['precio'] = $itemCampo[$nombreValorProducto]; 
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['numCorridasValorOptimo'] = 0;

						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $itemCampo[$nombreProducto];
					}
				} 
				elseif ($itemCampo[$nombreProducto] > 0) {
					if($rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['precio'] > $itemCampo[$nombreValorProducto]) {
						//Se actualiza el precio al que oferta el campo
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['precio'] = $itemCampo[$nombreValorProducto];
						
						//Elimino las empresas que había para agregar la nueva
						unset($rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa']);
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $itemCampo[$nombreProducto];
					}
					elseif($rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['precio'] == $itemCampo[$nombreValorProducto]) {
						//Agrego a las empresas que había la nueva empresa que puede ofertar
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
						$rondasCorridastmp[$ronda][$producto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $itemCampo[$nombreProducto];
					}
				}
			}
		}
		
		foreach($tmpArrayComprador as $idComprador) {
			foreach($tmpArrayDestino[$idComprador] as $idCiudad){
				$precioTotalFirme = -1;
				$precioTotalOtro = -1;
				$idCampoFirme = -1;
				$idCampoOtro = -1;
				$posCiudadFirme = -1;
				$posCiudadOtro = -1;
				
				$nombreProductoFirme = '';
				$nombreProductoOtro = '';
				$nombreProductoDemanda = '';
				
				foreach($productos as $producto) {
				  if (isset ($rondasCorridastmp[$ronda][$producto]['campo']))
					foreach($rondasCorridastmp[$ronda][$producto]['campo'] as $idCampo) {
						$nombreProductoDemanda = $this->darNombreProductoDemanda2($producto);
						
						//La demanda no puede ir a un campo donde la oferta es 0
						$oferta = 0;
						foreach($idCampo['empresa'] as $tmpEmpresa) {
							$oferta += $tmpEmpresa['oferta'];
						}
						
						//Busca la ciudad
						$posCiudad = 0;
						$entro = false;
						foreach($tmpArrayDestino[$idComprador] as $tmpidCiudad) {
							if($idCiudad == $tmpidCiudad || $entro)
								$entro = true;
							else
								$posCiudad = $posCiudad	 + 1;
						}
						
					
						//Si la oferta es cero, el producto no existe o la demanda es cero, no debe entrar a realizar ofertas
//						if($oferta > 0 && !empty($tmpArrayDemanda[$idComprador][$nombreProductoDemanda]) && $tmpArrayDemanda[$idComprador][$nombreProductoDemanda][$posCiudad] > 0) {
						if($oferta > 0 && !empty($tmpArrayDemanda[$idComprador][$nombreProductoDemanda]) ) {	
						
							$idCampoTemp = $idCampo['idCampo'];
							$precioGas = $idCampo['precio'];
							$precioTransporte = $tmpArrayTransporte[$idCiudad . "|" . $idCampoTemp];
							$precioTotalTemp = $precioGas + $precioTransporte;
							
							//Se busca el menor precio para firme
							if($producto == 'firme' || $producto == 'firmeC') {
								if($precioTotalFirme == -1) {
									$precioTotalFirme = $precioTotalTemp;
									$idCampoFirme = $idCampoTemp;
									$nombreProductoFirme = $producto;
									$posCiudadFirme = $posCiudad;
								}
								elseif($precioTotalFirme > $precioTotalTemp) {
									$precioTotalFirme = $precioTotalTemp;
									$idCampoFirme = $idCampoTemp;
									$nombreProductoFirme = $producto;
									$posCiudadFirme = $posCiudad;
								}
							}
							//Se busca el menor precio para el otro producto
							else {
								$entroPosCiudad = false;

								if($precioTotalOtro == -1 && !$entroPosCiudad) {
//									echo $posCiudad . " entra aquí SI<br />" ;
									$precioTotalOtro = $precioTotalTemp;
									$idCampoOtro = $idCampoTemp;
									$nombreProductoOtro = $producto;
									$posCiudadOtro = $posCiudad;
									$entroPosCiudad = true ;
								}
								elseif($precioTotalOtro > $precioTotalTemp && !$entroPosCiudad) {
//									echo $posCiudad . " entra aquí NO SI<br />" ;
									$precioTotalOtro = $precioTotalTemp;
									$idCampoOtro = $idCampoTemp;
									$nombreProductoOtro = $producto;
									$posCiudadOtro = $posCiudad;
									$entroPosCiudad = true ;
								}
							}

						}

					}

				}
				
				//Asigna las demandas al firme y/o al otro 
				if($precioTotalFirme <= $precioTotalOtro) {
					$productotmp = 'dCfcU';
					$productotmpO = 'dCfcC';
					if($nombreProductoOtro == 'opcional' || $nombreProductoOtro == 'opcionalU') {
						$productotmp = 'dOcgU';
						$productotmpO = 'dOcgC';
					}
					
					$cont = isset($rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador']) ? count($rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador']) : 0;
					$elasticidad = $this->darNombreProductoElasticidadDemanda2('firme');
					//Se asigna el producto firme a firme
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador]['dFirme'][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$elasticidad][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = 'firme';	

					$cont++;
					//Se asigna el producto firmeC a firme
					$elasticidad = $this->darNombreProductoElasticidadDemanda2('firmeC');
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador]['dFirmeC'][$posCiudadFirme];
					$tmpArrayDemanda[$idComprador];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$elasticidad][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = 'firmeC';		

					$cont++;
					//Se asigna el producto otro a firme
					if($nombreProductoOtro == 'condicional' || $nombreProductoOtro == 'condicionalC') {
						$posibilidad1 = 'condicional';	
						$posibilidad2 = 'condicionalC';	
					}
					elseif($nombreProductoOtro == 'opcional' || $nombreProductoOtro == 'opcionalC') {
						$posibilidad1 = 'opcional';	
						$posibilidad2 = 'opcionalC';	
					}
					$nombreProductoElasticidadDemanda = $this->darNombreProductoElasticidadDemanda2($posibilidad1);
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador][$productotmp][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = $posibilidad1;	
					

					$cont++;
					$nombreProductoElasticidadDemanda = $this->darNombreProductoElasticidadDemanda2($posibilidad2);
					//Se asigna el producto firmeC a firme
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador][$productotmpO][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = $posibilidad2;	
				}
				else {
					$productotmp = 'dCfcU';
					$productotmpO = 'dCfcC';
					if($nombreProductoOtro == 'opcional' || $nombreProductoOtro == 'opcionalU') {
						$productotmp = 'dOcgU';
						$productotmpO = 'dOcgC';
					}

					$cont = isset($rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador']) ? count($rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador']) : 0;
					$nombreProductoElasticidadDemanda = $this->darNombreProductoElasticidadDemanda2('firme');
					
					
					//Se asigna el producto firme a firme
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador]['dFirme'][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = 'firme';	
					
					$cont++;
					$nombreProductoElasticidadDemanda = $this->darNombreProductoElasticidadDemanda2('firmeC');
					//Se asigna el producto firmeC a firme
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idComprador'] = $idComprador;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['idCiudad'] = $idCiudad;
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador]['dFirmeC'][$posCiudadFirme];
					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadFirme];

					$rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador'][$cont]['tipProducto'] = 'firmeC';		

					//Se asigna el producto otro a otro
					if($nombreProductoOtro == 'condicional' || $nombreProductoOtro == 'condicionalC') {
						$posibilidad1 = 'condicional';	
						$posibilidad2 = 'condicionalC';	
					}
					elseif($nombreProductoOtro == 'opcional' || $nombreProductoOtro == 'opcionalC') {
						$posibilidad1 = 'opcional';	
						$posibilidad2 = 'opcionalC';	
					}
					$cont = isset($rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador']) ? count($rondasCorridastmp[$ronda][$nombreProductoFirme]['campo'][$idCampoFirme]['comprador']) : 0;
					
					$nombreProductoElasticidadDemanda = $this->darNombreProductoElasticidadDemanda2('firme');
					if ($posCiudadOtro >= 0) { 
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['idComprador'] = $idComprador;
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['idCiudad'] = $idCiudad;
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador][$productotmp][$posCiudadOtro];
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadOtro];
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['tipProducto'] = $posibilidad1;	
						
						$cont++;
						//Se asigna el producto otroC a otro
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['idComprador'] = $idComprador;
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['idCiudad'] = $idCiudad;
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['demanda'] = $tmpArrayDemanda[$idComprador][$productotmpO][$posCiudadOtro];
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['elasticidad'] = $tmpArrayDemanda[$idComprador][$nombreProductoElasticidadDemanda][$posCiudadOtro];
						$rondasCorridastmp[$ronda][$nombreProductoOtro]['campo'][$idCampoOtro]['comprador'][$cont]['tipProducto'] = $posibilidad2;	
					}
				}
			}
		}
		return $rondasCorridastmp;
	}
	
	
	function buscarMenoresPrecios ($unSesion, $ultimaRonda, $tmpIdCiudad) {
			$tmpArrayTransporte = $unSesion->obtenerVariable ('transporte');
			
			$precioTotalFirme = -1;
			$precioTotalOpcional = -1;
			$precioTotalCondicional = -1;

			$idCampoFirme = -1;
			$idCampoOpcional = -1;
			$idCampoCondicional = -1;
			
			$nombreProductoFirme = '';
			$nombreProductoOpcional = '';
			$nombreProductoCondicional = '';
			
	/*		echo "<pre>";
			print_r ($tmpArrayTransporte);
			echo "</pre>";
		*/	
			
			foreach($ultimaRonda as $producto => $itemProducto) {
				foreach ($itemProducto['campo'] as $campo ) {
					if (isset ($campo['idCampo']) && isset ($campo['empresa'])) {
						if ($producto == 'firme' || $producto == 'firmeC') {
	//						echo $campo['idCampo'] ." // " . $tmpIdCiudad . " // " .  ($campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']]) ."<br>";
							if($precioTotalFirme == -1) {
								$precioTotalFirme = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoFirme = $campo['idCampo'];
								$nombreProductoFirme = $producto;
							}
							elseif($precioTotalFirme > $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']]) {
								$precioTotalFirme = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoFirme = $campo['idCampo'];
								$nombreProductoFirme = $producto;
							}
							
						}
						if ($producto == 'condicional' || $producto == 'condicionalC') {
							if($precioTotalCondicional == -1) {
								$precioTotalCondicional = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoCondicional = $campo['idCampo'];
								$nombreProductoCondicional = $producto;
							}
							elseif($precioTotalCondicional > $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']]) {
								$precioTotalCondicional = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoCondicional = $campo['idCampo'];
								$nombreProductoCondicional = $producto;
							}
							
						}
						if ($producto == 'opcional' || $producto == 'opcionalC') {
							if($precioTotalOpcional == -1) {
								$precioTotalOpcional = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoOpcional = $campo['idCampo']; 
								$nombreProductoOpcional = $producto;
							}
							elseif($precioTotalOpcional > $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']]) {
								$precioTotalOpcional = $campo['precio'] + $tmpArrayTransporte[$tmpIdCiudad ."|".$campo['idCampo']];
								$idCampoOpcional = $campo['idCampo'];
								$nombreProductoOpcional = $producto;
							}
							
						}
						
						
					}
					
				}
			}
/*			
			echo $tmpIdCiudad . '<br /> ';
			echo $precioTotalFirme . '<br /> ';
			echo $precioTotalOpcional . '<br /> ';
			echo $precioTotalCondicional . '<br /> ';

			echo $idCampoFirme . '<br /> ';
			echo $idCampoOpcional . '<br /> ';
			echo $idCampoCondicional . '<br /> ';
			
			echo $nombreProductoFirme . '<br /> ';
			echo $nombreProductoOpcional . '<br /> ';
			echo $nombreProductoCondicional . '<br /> ';
			
	*/			
		return  array ($precioTotalFirme, $precioTotalOpcional, $precioTotalCondicional, $idCampoFirme, $idCampoOpcional, $idCampoCondicional, $nombreProductoFirme, $nombreProductoOpcional, $nombreProductoCondicional) ;	
			
	}
	/*
	* Este método calcula el primer movimiento de las demandas de la ronda n.
	*/
	function darDemandaBaseRondaCero($unSesion, $productos, $rondas, $ronda) {
		$tmpArrayTransporte = $unSesion->obtenerVariable ('transporte');
		$tmpArrayDestino    = $unSesion->obtenerVariable ('listDestino');  
		$tmpArrayComprador  = $unSesion->obtenerVariable ('listComprador'); 
		$tmpArrayDemanda    = $unSesion->obtenerVariable ('listDemanda');
				
		// se recorren los campos de la ultima ronda
		$ultimaRonda = $rondas[$ronda] ;
		$rondaComparacion = $rondas[$ronda - 1 ] ;
		
		
		foreach  ($ultimaRonda as $llaveProducto => $tmpProducto) {
			// se recorre campos
			foreach ($tmpProducto['campo'] as $llaveCampo => $tmpCampo  ) {
//				echo $llaveCampo . "<br />";
				if(isset ($tmpCampo['idCampo']) && $tmpCampo['exceso']) {
					$tmpArraySalen = array ();
					$tmpArraySeQuedan = array ();
					// se recorren los compradores
					if (isset ($tmpCampo['comprador'])) {
						foreach ($tmpCampo['comprador'] as $llaveComprador => $tmpComprador) {
							$mejoresPrecios = $this->buscarMenoresPrecios($unSesion, $ultimaRonda, $tmpComprador['idCiudad']);				

						$continuar = true ;

							if ($llaveProducto == 'firme' || $llaveProducto == 'firmeC') {
								$tmpSumaTransporteActual = $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] + $tmpCampo['precio'] ;
								$tmpSumaTransporteNuevo = $mejoresPrecios[0] ;

								$P0 = $rondaComparacion[$llaveProducto]['campo'][$llaveCampo]['precio'] + $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] ;
								$Q0 = $tmpComprador['demanda'] ;
								$e1 = $tmpComprador['elasticidad'] ;  
								
								if ($tmpSumaTransporteActual <= $tmpSumaTransporteNuevo )  {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteActual, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySeQuedan[] = $tmpComprador;
									}
								}
								// se guarda en un arrray los compradores que se quieren ir
								else {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteNuevo, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySalen[] = $tmpComprador;
									}
								}
							}
							elseif ($llaveProducto == 'condicional' || $llaveProducto == 'condicionalC') {
								$tmpSumaTransporteActual = $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] + $tmpCampo['precio'] ;
								$tmpSumaTransporteNuevo =  $mejoresPrecios[2] ;
								
								$P0 = $rondaComparacion[$llaveProducto]['campo'][$llaveCampo]['precio'] + $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] ;
								$Q0 = $tmpComprador['demanda'] ;
								
								$e1 = $tmpComprador['elasticidad'] ;
								if ($tmpSumaTransporteActual <= $tmpSumaTransporteNuevo )  {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteActual, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySeQuedan[] = $tmpComprador;
									}
								}
								// se guarda en un arrray los compradores que se quieren ir
								else {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteNuevo, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySalen[] = $tmpComprador;
									}
								}
							}
							elseif ($llaveProducto == 'opcional' || $llaveProducto == 'opcionalC') {
								$tmpSumaTransporteActual = $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] + $tmpCampo['precio'] ;
								$tmpSumaTransporteNuevo =  $mejoresPrecios[1] ;
								
								$P0 = $rondaComparacion[$llaveProducto]['campo'][$llaveCampo]['precio'] + $tmpArrayTransporte[$tmpComprador['idCiudad'] .'|'. $tmpCampo['idCampo']] ;
								$Q0 = $tmpComprador['demanda'] ;
								$e1 = $tmpComprador['elasticidad'] ;

								if ($tmpSumaTransporteActual <= $tmpSumaTransporteNuevo )  {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteActual, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySeQuedan[] = $tmpComprador;
									}
								}
								// se guarda en un arrray los compradores que se quieren ir
								else {
									$nuevaDemanda = $this->calcularQ1conElasticidad($Q0, $tmpSumaTransporteNuevo, $P0, $e1) ;
									if ($tmpComprador['demanda'] > 0) {	
										$tmpComprador['demandaQ'] = $nuevaDemanda ;
										$tmpArraySalen[] = $tmpComprador;
									}
								}
							}
						}
					
					}
	
					// ahora se hace la suma demanda de los que se van y los que se quedan
					
				if ($continuar) {

					$tmpSumaSalen = 0;
					if (isset($tmpArraySalen))
					foreach ($tmpArraySalen as $salen) {
						$tmpSumaSalen += $salen['demandaQ'];
						}
						
					$tmpSumaQuedan = 0;
					if (isset($tmpArraySeQuedan))
					foreach ($tmpArraySeQuedan as $quedan) {
						$tmpSumaQuedan += $quedan['demandaQ'];
					}
					
					$tmpOfertaCampo = (isset($ultimaRonda[$llaveProducto]['campo'][$llaveCampo]['empresa'])) ? 
									$this->darSumaOfertaProductoCampo($ultimaRonda[$llaveProducto]['campo'][$llaveCampo]['empresa']) : 0;
									
					// si todos se quieren ir se calcula el porccentaje de la participacion en la demanda y se asigan nuevos valores para los que se van
					// y se agrega a los que se quedan
					if ($tmpSumaQuedan == 0 ) {
						$pC = $tmpOfertaCampo / $tmpSumaSalen ;
					}
					elseif ($tmpSumaSalen == 0) { // todos se quedan
						$pC = $tmpOfertaCampo / $tmpSumaQuedan ;
					}
					else {
						$pC = ($tmpOfertaCampo - $tmpSumaQuedan) / $tmpSumaSalen ;
					}
					
					$tmpArrayAgregar = array () ;
					
					$tmpSumaDiferencias = 0;
					foreach ($tmpArraySeQuedan as $llaveSeQuedan => $seQuedan) {
						$tmpSumaDiferencias += $seQuedan['demanda'] - $seQuedan['demandaQ'];
					}
					foreach ($tmpArraySalen as $llaveSalen => $salen) {
						$tmpSumaDiferencias += $salen['demanda'] - $salen['demandaQ'];
					}
					
					
					$tmpResta = count($tmpArraySeQuedan) ;
					//Se actualiza las lista de compradores por campo que se quedan
					foreach ($tmpArraySeQuedan as $llaveSeQuedad => $seQuedan) {
						// si la suma demandaQ de los que se quedan es menor a la oferta y todos se quedan 
						// no se toma la demandaQ, se toma la demanda
						if ($tmpOfertaCampo > $tmpSumaQuedan) {
							$pC = ($seQuedan['demanda'] - $seQuedan['demandaQ'])/$tmpSumaDiferencias;
							if ($tmpResta == 1)
								$seQuedan['demanda'] = $tmpOfertaCampo ;
							else {
								$tmpCalculo = ($pC * ($tmpOfertaCampo - $tmpSumaQuedan));
								if($tmpCalculo < 0)
									$tmpCalculo = 0;
								
								$seQuedan['demanda'] = $tmpCalculo + $seQuedan['demandaQ'];
							}
						}
						// si no entonces la demanda sigue igual
						else
							$seQuedan['demanda'] = $seQuedan['demandaQ'];
							
						// se asigan el nuevo valor de comprador en los que se quedan
						$tmpArraySeQuedan[$llaveSeQuedad] = $seQuedan;
					}
					
					// se actualizan los regisros para los que se quieren ir
					foreach ($tmpArraySalen as $llaveSalen => $salen) {
						// si todos se van y la suman demandaQ > a la oferta hay que dejar lo que tiene 
						// tomo la demandaQ resto lo que debe dejar
						if ($tmpSumaQuedan == 0) {
							$tmpSalen = $salen;
							$pC = $salen['demandaQ']/$tmpSumaSalen;
							$debeDejar = $pC * $tmpOfertaCampo;
							
							if(count($tmpArraySalen)==1) {
								$tmpSalen['demanda'] = $tmpOfertaCampo;		
								$salen['demanda']  = $tmpSalen['demandaQ'] - $tmpOfertaCampo ;
							}
							elseif($debeDejar < $salen['demandaQ']) {
								$tmpSalen['demanda'] = $debeDejar ;
								$salen['demanda'] = $salen['demandaQ'] - $debeDejar ;
							}
							else {
								$tmpSalen['demanda'] = $debeDejar ;
								$salen['demanda'] = 0 ;
							}
							
							unset ($tmpSalen['demandaQ']) ;
							$tmpArrayAgregar[] = $tmpSalen ;
						}
						// si la suma de los que se quedan es mayor a cero y menor a la oferta
						// se busca la diferencia de lo que hace falta y se  resta
						elseif ($tmpSumaQuedan > 0 && $tmpSumaQuedan < $tmpOfertaCampo) {
							$tmpSalen = $salen;
							$pC = ($salen['demanda'] - $salen['demandaQ'])/$tmpSumaDiferencias;
							
							if (count($tmpArraySalen) == 1) {
								$tmpSalen['demanda'] = $tmpOfertaCampo ;
								$salen['demanda'] = $salen['demandaQ'] - $salen['demanda'];
							}
							else {
								$tmpSalen['demanda'] = $pC * ($tmpOfertaCampo - $tmpSumaQuedan);
								$salen['demanda'] = $salen['demandaQ'] - $salen['demanda'];
							}
	
							unset ($tmpSalen['demandaQ']) ;
							$tmpArrayAgregar[] = $tmpSalen ;
						}
						elseif ($tmpSumaQuedan > 0 && $tmpSumaQuedan >= $tmpOfertaCampo) {
							$salen['demanda'] = $salen['demandaQ'];
						}
						
						unset ($salen['demandaQ']) ;
						$tmpArraySalen[$llaveSalen] = $salen ;	
					}
					
					$tmpArraySeQuedan = array_merge ($tmpArraySeQuedan, $tmpArrayAgregar);
					
					$tmpArraySalen = $this->formatoNumeroSumaIgualesDemanda ($tmpArraySalen) ;
					$tmpArraySeQuedan = $this->formatoNumeroSumaIgualesDemanda ($tmpArraySeQuedan) ;
					
					$ultimaRonda[$llaveProducto]['campo'][$llaveCampo]['comprador'] = $tmpArraySeQuedan ;
	
					// Ahora hay que asignar las demandas sobrantes a otro campo
					if ($llaveProducto == 'firme' || $llaveProducto == 'firmeC')
						$ultimaRonda = $this->asignarCompradoresANuevosCampos($ultimaRonda, $tmpArraySalen, $mejoresPrecios[3], $mejoresPrecios[6]);
					elseif ($llaveProducto == 'condicional' || $llaveProducto == 'condicionalC')
						$ultimaRonda = $this->asignarCompradoresANuevosCampos($ultimaRonda, $tmpArraySalen, $mejoresPrecios[5], $mejoresPrecios[8]);
					elseif ($llaveProducto == 'opcional' || $llaveProducto == 'opcionalC')
						$ultimaRonda = $this->asignarCompradoresANuevosCampos($ultimaRonda, $tmpArraySalen, $mejoresPrecios[4], $mejoresPrecios[7]);
					
					}
				}
			}
		}
		$rondas[$ronda] = $ultimaRonda ;
		
		return $rondas;
	}

	function calcularDemandaCorrecta($tmpArraySeQuedan, $pC) {
		$retorno = 0;

		foreach ($tmpArraySeQuedan as $llaveQuedan => $quedan) {
			if( $quedan['demandaQ'] < ($quedan['demanda'] * $pC) )
				$retorno += $quedan['demanda'] * $pC ;
			else
				$retorno += $quedan['demandaQ'] ;
			
			// se elimina la variable temporal demandaQ 
			unset ($quedan['demandaQ']);
		}

		return $retorno;
	}
	
	function calcularSaleDemandaCorrecta($tmpArraySalen, $pC) {
		$retorno = 0;

		foreach ($tmpArraySalen as $llaveSalen => $salen) {
			$retorno += $salen['demanda'] ;
		}

		return $retorno;
	}
	
	function formatoNumeroSumaIgualesDemanda ($arrayDemanda) {
	//		return $arrayDemanda;
		for ($i = 0 ; $i < count ($arrayDemanda)  ; $i++ ) {
			$arrayDemanda[$i]['demanda'] = number_format($arrayDemanda[$i]['demanda'], 3, '.', '') ;
//			echo "contador " . count ($arrayDemanda) . " -- " .$arrayDemanda[$i]['demanda'] . "<br />";
			$j = $i + 1 ;
			if (isset ($arrayDemanda[$j]))	{
				for ($j ; $j < count ($arrayDemanda) ; $j++ ) {
					if ($arrayDemanda[$i]['idComprador'] == $arrayDemanda[$j]['idComprador']) 
						if ($arrayDemanda[$i]['idCiudad'] == $arrayDemanda[$j]['idCiudad']) 
							if ($arrayDemanda[$i]['tipProducto'] == $arrayDemanda[$j]['tipProducto'])  {
								$arrayDemanda[$i]['demanda'] = number_format($arrayDemanda[$i]['demanda'] + $arrayDemanda[$j]['demanda'], 3, '.', '') ;
								unset ($arrayDemanda[count ($arrayDemanda) - 1]) ;
								array_values ($arrayDemanda);
							}
				} 
			}
		} 
		return $arrayDemanda;			
	}

	function asignarCompradoresANuevosCampos($ultimaRonda, $tmpArraySalen, $idCampo, $nombreProducto) {
//		echo $idCampo . "<br>";
//		echo $nombreProducto  . "<br>";
//		echo "Antiguo <pre>";
//		print_r ($ultimaRonda);
//		echo "</pre>";


		foreach ($tmpArraySalen as $llaveSalen => $salen) {
			$ultimaRonda[$nombreProducto]['campo'][$idCampo]['comprador'][] = $salen;
		}
		
		
//		echo "Nuevo <pre>";
//		print_r ($ultimaRonda);
//		echo "</pre>";
		
		return $ultimaRonda;
	}
	
	function comprobarTipoDeComprador($producto, $tmpArrayDemanda, $idComprador) {
		$cond1 = $tmpArrayDemanda[$idComprador]['tipo'] == 'A' && ($producto=='condicional' || $producto=='condicionalC');
		$cond2 = $tmpArrayDemanda[$idComprador]['tipo'] == 'B' && ($producto=='opcional' || $producto=='opcionalC');
		$cond3 = $producto=='firme' || $producto=='firmeC';
		return $cond1 || $cond2 || $cond3;
	}
	
	function borrarDemandasDeLaNuevaRonda($rondas, $ronda, $productos ){
		foreach($productos as $producto) {
			foreach($rondas[$ronda][$producto]['campo'] as $idCampo => $itemCampo) {
			//	unset($rondas[$ronda][$producto]['campo'][$idCampo]['comprador']);
				$rondas[$ronda][$producto]['campo'][$idCampo]['comprador'] = array ();
			}
		}
		return $rondas;
	}
	
	function buscarProductosFirmeDeComprador($rondas, $ronda, $idComprador, $idCiudad, $nombreProductoFirme) {
		$producto = 'firme';
		
		//Se busca donde esta el producto firme del comprador en los productos firmes en la ronda anterior
		$idCampoFirmePos = $this->buscarCompradorEnRondaAnterior($rondas, $ronda, $producto, $idComprador, $idCiudad, $nombreProductoFirme);
//		print_r  ($idCampoFirmePos) ;

		if(count($idCampoFirmePos) == 0) {
			$producto = 'firmeC';
			$idCampoFirmePos = $this->buscarCompradorEnRondaAnterior($rondas, $ronda, $producto, $idComprador, $idCiudad, $nombreProductoFirme);
		}
		
		if(count($idCampoFirmePos) == 0)
			return array();
		else
			return  array(0=>$idCampoFirmePos[0], 1=>$idCampoFirmePos[1], 2=>$producto);
	}
	
	function buscarProductosOtrosDeComprador($rondas, $ronda, $idComprador, $idCiudad, $nombreProductoOtro) {
		
		$tmp = $this->buscarProductosFirmeDeComprador($rondas, $ronda, $idComprador, $idCiudad, $nombreProductoOtro);
		if(count($tmp)==0) {
			
			if($nombreProductoOtro == 'condicional' || $nombreProductoOtro == 'condicionalC')
				$producto = 'condicional';
			elseif($nombreProductoOtro == 'opcional' || $nombreProductoOtro == 'opcionalC')
				$producto = 'opcional';
			
			$idCampoOtroPos = $this->buscarCompradorEnRondaAnterior($rondas, $ronda, $producto, $idComprador, $idCiudad, $nombreProductoOtro);
			
			//Se busca donde esta el otro producto del comprador en los productos otros en la ronda anterior
			if(count($idCampoOtroPos)==0) {
			
				if($producto == 'condicional')
					$producto = 'condicionalC';
				elseif($producto == 'opcional')
					$producto = 'opcionalC';
			
				$idCampoOtroPos = $this->buscarCompradorEnRondaAnterior($rondas, $ronda, $producto, $idComprador, $idCiudad, $nombreProductoOtro);
			}

			//Se revisa que haya encontrado el comprador en el producto tipo otro en la ronda anterior
			if(count($idCampoOtroPos)==0)
				return array();
			else				
					$tmp = array(0=>$idCampoOtroPos[0], 1=>$idCampoOtroPos[1] ,2=>$producto);
		}
		return $tmp;
	}
	
	/*
	 * Busca el comprador por el id del comprador, el id de la ciudad y el tipo de producto en la ronda anterior
	 * Si no lo encuentra devuelve false
	 */
	function buscarCompradorEnRondaAnterior($rondas, $ronda, $nombreProducto, $idComprador, $idCiudad, $tipProducto) {
		
		$tmp = array();
				
		foreach($rondas[$ronda-1][$nombreProducto]['campo'] as $tmpCampo) {
 
			if(isset($tmpCampo['idCampo']) && isset($tmpCampo['comprador'])) {
				$idCampo = $tmpCampo['idCampo'];
				$pos = 0;
				foreach($tmpCampo['comprador'] as $itemComprador) {
					$idCompradortmp = $itemComprador['idComprador'];
					$idCiudadtmp = $itemComprador['idCiudad'];
					$tipProductotmp = $itemComprador['tipProducto'];

					if($idComprador == $idCompradortmp && $idCiudad == $idCiudadtmp && $tipProducto == $tipProductotmp && empty($tmp)) {
						$tmp = array (0 => $idCampo, 1=> $pos);
					}
					$pos++;
				}
			}
		}
		
		return $tmp;
	}
	
	/*
	* Retorna la suma de la oferta por un producto dado
	* @producto
	*/
	function darSumaOfertaCampoProducto ($llave, $producto) {
		
	}
	
	/* 
	 * Este método modifica los precios de un producto de un vendedor de acuerdo al porcentaje ingresado.
	 * También actualiza las empresas que van a participar en la oferta.
	 */
	function darDemandaPorcentual ($rondas, $ronda, $tmpArrayVendedor, $valorP, $idCampo, $tipoProducto, $tmpListaVendedores) {
		//Asignamos el nombre correcto del tipo de producto
		$tipoProductoReal = $this->darNombreValorProductoReal2($tipoProducto);
		$productoReal = $this->darNombreProducto2($tipoProducto);
		
		//Sacamos el valor actual del campo y lo aumentamos de acuerdo al porcentaje de entrada. Este valor se le asigna al campo-producto de entrada.
		$valorResultante = ($valorP / 100 ) + 1;
		$valorPorcentual = ($rondas[$ronda-1][$tipoProducto]['campo'][$idCampo]['precio']) * ($valorResultante);
		$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] = $valorPorcentual;

		//Se borran todas las empresas que habia
		unset($rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa']);

		foreach ($tmpArrayVendedor as $vendedor) {
			//Se saca los valores de la empresa
			$idCampoEmpresa = $vendedor['id'];
			$tmp = explode("|", $idCampoEmpresa);
			$idCampoE = $tmp[0];
			$idEmpresa = $tmp[1];
			
			if($idCampoE == $idCampo && $vendedor[$productoReal]>0 && $rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] >= $vendedor[$tipoProductoReal]) {
				//Se agrega las empresas que van a entrar en la oferta
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $vendedor[$productoReal];
				$tmpListaVendedores[$idCampoEmpresa][$tipoProductoReal] = $valorPorcentual; 
			}
		}		
		return array( 0=>$rondas, 1=>$tmpListaVendedores);
	}

	/*
	 * Este método modifica los precios de un producto de un vendedor de acuerdo al cálculo tipo nominal
	 * También actualiza las empresas que van a participar en la oferta para el campo_producto al que se le hace el incremento.
	 */
	function darDemandaNominal ($rondas, $ronda, $tmpArrayVendedor, $valorP, $idCampo, $tipoProducto, $tmpListaVendedores) {
		//Asignamos el nombre correcto del tipo de producto
		$tipoProductoReal = $this->darNombreValorProductoReal2($tipoProducto);
		$productoReal = $this->darNombreProducto2($tipoProducto);
		
		//Sacamos el valor nominal sumando el valor de entrada con el actual. Este valor se le asigna al campo-producto de entrada.
		$valorNominal = $valorP + ($rondas[$ronda-1][$tipoProducto]['campo'][$idCampo]['precio'])	;
		$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] = $valorNominal;
		
		//Se borran todas las empresas que habia
		unset($rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa']);
		
		foreach ($tmpArrayVendedor as $vendedor) {
			//Se saca los valores de la empresa
			$idCampoEmpresa = $vendedor['id'];
			$tmp = explode("|", $idCampoEmpresa);
			$idCampoE = $tmp[0];
			$idEmpresa = $tmp[1];

			if($idCampoE == $idCampo && $vendedor[$productoReal]>0 && $rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] >= $vendedor[$tipoProductoReal]) {				
				//Se agrega las empresas que van a entrar en la oferta
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $vendedor[$productoReal];
				$tmpListaVendedores[$idCampoEmpresa][$tipoProductoReal] = $valorNominal;
			}
		}		
		return array( 0=>$rondas, 1=>$tmpListaVendedores);
	}
	
	/*
	 * Este método modifica los precios de un producto de un vendedor de acuerdo al nuevo valor ingresado
	 * También actualiza las empresas que van a participar en la oferta para el campo_producto al que se le hace el incremento.
	 */
	function darDemandaNuevoValor ($rondas, $ronda, $tmpArrayVendedor, $valorP, $idCampo, $tipoProducto, $tmpListaVendedores) {
		
		//Asignamos el nombre correcto del tipo de producto
		$tipoProductoReal = $this->darNombreValorProductoReal2($tipoProducto);
		$productoReal = $this->darNombreProducto2($tipoProducto);
		
		//Se actualiza el costo del campo
		$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] = $valorP;
		
		//Se borran todas las empresas que habia
		unset($rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa']);
		
		foreach ($tmpArrayVendedor as $vendedor) {
			//Se saca los valores de la empresa
			$idCampoEmpresa = $vendedor['id'];
			$tmp = explode("|", $idCampoEmpresa);
			$idCampoE = $tmp[0];
			$idEmpresa = $tmp[1];

			if($idCampoE == $idCampo && $vendedor[$productoReal]>0 && $rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] >= $vendedor[$tipoProductoReal]) {				
				//Se agrega las empresas que van a entrar en la oferta
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $vendedor[$productoReal];
				$tmpListaVendedores[$idCampoEmpresa][$tipoProductoReal] = $valorP;
			}
		}
		return array( 0=>$rondas, 1=>$tmpListaVendedores);
	}
	
	/*
	 * Este método modifica los precios de un producto de un vendedor de acuerdo al cálculo del valor óptimo y mantiene el
	 * conteo de cuantas veces se le ha hecho este tipo de incremento a es campo_producto
	 * También actualiza las empresas que van a participar en la oferta para el campo_producto al que se le hace el incremento.
	 */
	function darDemandaValorOptimo ($rondas, $ronda, $tmpArrayVendedor, $idCampo, $tipoProducto, $tmpListaVendedores) {
		
		//Asignamos el nombre correcto del valor de acuerdo al tipo de producto
		$nombreValorProductoActual = $this->darNombreValorProductoReal2($tipoProducto);
		
		//Asignamos el nombre correcto del tipo de producto
		$nombreProductoActual = $this->darNombreProductoReal($tipoProducto);
		
		$tipoProductoReal = $this->darNombreValorProductoReal2($tipoProducto);
		$productoReal = $this->darNombreProducto2($tipoProducto);
		
		$valorActual = $rondas[$ronda-1][$tipoProducto]['campo'][$idCampo]['precio'];
		$valorSiguiente = $valorActual;
		$valorSiguienteAgregado = $valorActual;
		$tmpUltimaRonda = $rondas[$ronda-1];
		
		//Se busca el siguiente precio para fijo
		if($tipoProducto == 'firme' || $tipoProducto == 'firmeC') {
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firme', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firmeC', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
		}
		elseif($tipoProducto == 'condicional' || $tipoProducto == 'condicional') {
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firme', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firmeC', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'condicional', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'condicionalC', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
		}
		elseif($tipoProducto == 'opcional' || $tipoProducto == 'opcionalU') {
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firme', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'firmeC', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'opcional', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];
			
			$tmp = $this->calculaValorSiguienteYAgregado($tmpUltimaRonda, $tmpListaVendedores, 'opcionalC', $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo);
			$valorSiguiente = $tmp[0];
			$valorSiguienteAgregado = $tmp[1];			
		}
		
		//Trae las veces a las que se le ha aplicado el valor óptimo al campo
		$numVecesCorridas = $rondas[$ronda][$tipoProducto]['campo'][$idCampo]['numCorridasValorOptimo'];
		//Se aumenta en una unidad las veces a las que se le ha aplicado el valor optimo
		$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['numCorridasValorOptimo'] = $numVecesCorridas + 1;

		//Se actualiza el valor siguiente agregado, sólo si el valorSiguiente es menor al valor siguiente agregado y mayor al valor actual
		//$valorSiguienteAgregado = ($valorSiguiente > $valorActual && $valorSiguiente < $valorSiguienteAgregado) ? $valorSiguiente : $valorSiguienteAgregado;
		
		//Realiza los calculos finales para el valor optimo
		$valorOptimo = 0;
		if($valorActual != $valorSiguiente && $numVecesCorridas < 3 )
		{
			$primerValor = ($valorActual + $valorSiguiente)/2;
			if($primerValor < $valorSiguienteAgregado)
				$valorOptimo = $primerValor;
			else
				$valorOptimo = $valorSiguienteAgregado;
		}
		else
			$valorOptimo = $valorSiguienteAgregado;
		
		//Se actualiza el costo del campo
		$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] = $valorOptimo;
		
		//Se borran todas las empresas que habia
		unset($rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa']);
		
		foreach ($tmpArrayVendedor as $vendedor) {
			//Se saca los valores de la empresa
			$idCampoEmpresa = $vendedor['id'];
			$tmp = explode("|", $idCampoEmpresa);
			$idCampoE = $tmp[0];
			$idEmpresa = $tmp[1];

			if($idCampoE == $idCampo && $vendedor[$productoReal]>0 && $rondas[$ronda][$tipoProducto]['campo'][$idCampo]['precio'] >= $vendedor[$tipoProductoReal]) {				
				//Se agrega las empresas que van a entrar en la oferta
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['idEmpresa'] = $idEmpresa;
				$rondas[$ronda][$tipoProducto]['campo'][$idCampo]['empresa'][$idEmpresa]['oferta'] = $vendedor[$productoReal];
				$tmpListaVendedores[$idCampoEmpresa][$tipoProductoReal] = $valorOptimo;
			}
		}
		
		return array( 0=>$rondas, 1=>$tmpListaVendedores);
	}
	
	/* 
	 * Esta función busca dentro del campo el valor siguiente y por fuera del campo el valor siguiente agregado
	 */
	function calculaValorSiguienteYAgregado($ronda, $tmpArrayVendedor, $tipoProducto, $valorSiguiente, $valorActual, $valorSiguienteAgregado, $idCampo) {
		foreach ($tmpArrayVendedor as $vendedor) {
			//Se saca los valores de la empresa
			$idCampoEmpresa = $vendedor['id'];
			$tmp = explode("|", $idCampoEmpresa);
			$idCampoE = $tmp[0];
			$idEmpresa = $tmp[1];
			
			$producto = $this->darNombreValorProductoReal2($tipoProducto);
			if($idCampoE == $idCampo) {
				//En este estado se busca cualquier valor siguiente
				if($valorSiguiente == $valorActual && $vendedor[$producto] > $valorSiguiente) {
					$valorSiguiente = $vendedor[$producto];
				}
				//En este estado se busca un valor menor a siguiente pero mayor al actual
				elseif($valorSiguiente > $valorActual && $vendedor[$producto] > $valorActual && $vendedor[$producto] < $valorSiguiente) {
					$valorSiguiente = $vendedor[$producto];
				}
			}
			else {
				//En este estado se busca cualquier valor siguienteAgregado
				if($valorSiguienteAgregado == $valorActual && $vendedor[$producto] > $valorSiguienteAgregado) {
					$valorSiguienteAgregado = $vendedor[$producto];
				}
				//En este estado se busca un valor menor a siguiente pero mayor al actual
				elseif($valorSiguienteAgregado > $valorActual && $vendedor[$producto] > $valorActual && $vendedor[$producto] < $valorSiguienteAgregado) {
					$valorSiguienteAgregado = $vendedor[$producto];
				}
			}
		}

		return array( 0 => $valorSiguiente, 1 => $valorSiguienteAgregado);
	}

	/*
	* Retorna html para la oferta de gas para regla de esceso de oferta
	* @rondas: array de rondas por producto
	* @tmpCampo: lista de campos en la subasta	
	*/
		
	function darHtmlOfertaExceso ($rondas, $ronda,  $tmpListaCampo, $productos, $unSesion) {
		$tmpHtml = '';
		foreach ($tmpListaCampo as $llave => $campo) {
			$tmpHtml .= '<tr>';
			$tmpHtml .= '<td>' . $campo . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['firme']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['firme']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset($rondas[$ronda]['firme']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['firme']['campo'][$llave]['comprador']) : 0;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$tmpHtml .= '<td>' . $rondas[$ronda]['firme']['campo'][$llave]['precio'] . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['firme']['campo'][$llave]['exceso'] = true ;
			else
				$rondas[$ronda]['firme']['campo'][$llave]['exceso'] = false ;
					 
			$tmpHtml .= '<td ' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . ' >' . $tmpSumaDemanda . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['condicional']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['condicional']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset ($rondas[$ronda]['condicional']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['condicional']['campo'][$llave]['comprador']) : 0;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$precio = (isset($rondas[$ronda]['condicional']['campo'][$llave]['precio'])) ? $rondas[$ronda]['condicional']['campo'][$llave]['precio'] : 0;
			$tmpHtml .= '<td>' . $precio . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['condicional']['campo'][$llave]['exceso'] = true ;	
			else
				$rondas[$ronda]['condicional']['campo'][$llave]['exceso'] = false ;	
				
			$tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['opcional']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['opcional']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset ($rondas[$ronda]['opcional']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['opcional']['campo'][$llave]['comprador']) : 0;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$precio = (isset($rondas[$ronda]['opcional']['campo'][$llave])) ? $rondas[$ronda]['opcional']['campo'][$llave]['precio'] : 0;
			$tmpHtml .= '<td>' . $precio . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['opcional']['campo'][$llave]['exceso'] = true ;
			else
				$rondas[$ronda]['opcional']['campo'][$llave]['exceso'] = false ;
			$tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['firmeC']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['firmeC']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset ($rondas[$ronda]['firmeC']['campo'][$llave]['comprador'])) ?  $this->darSumaDemandaProductoCampo ($rondas[$ronda]['firmeC']['campo'][$llave]['comprador']) : 0;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$precio = (isset($rondas[$ronda]['firmeC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['firmeC']['campo'][$llave]['precio'] : 0;
			$tmpHtml .= '<td>' . $precio . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda)) 
				$rondas[$ronda]['firmeC']['campo'][$llave]['exceso'] = true ;
			else 
 				$rondas[$ronda]['firmeC']['campo'][$llave]['exceso'] = false ;	
			$tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset($rondas[$ronda]['condicionalC']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['condicionalC']['campo'][$llave]['comprador']) : 0 ;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$precio = (isset($rondas[$ronda]['condicionalC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['condicionalC']['campo'][$llave]['precio'] : 0;
			$tmpHtml .= '<td>' . $precio . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['condicionalC']['campo'][$llave]['exceso'] = true;
			else
				$rondas[$ronda]['condicionalC']['campo'][$llave]['exceso'] = false;	
			$tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			
			$tmpSumaOferta = (isset($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa'])) ?$this->darSumaOfertaProductoCampo ($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa']) : 0;
			$tmpSumaDemanda = (isset ($rondas[$ronda]['opcionalC']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['opcionalC']['campo'][$llave]['comprador']) : 0 ;
			$tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			$precio = (isset($rondas[$ronda]['opcionalC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['opcionalC']['campo'][$llave]['precio'] : 0;
			$tmpHtml .= '<td>' . $precio . "</td>";
			
			if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['opcionalC']['campo'][$llave]['exceso'] = true ;
			else
				$rondas[$ronda]['opcionalC']['campo'][$llave]['exceso'] = false ;	
			$tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			
			$tmpHtml .= '</tr>';
			}
			
		$unSesion->registrarVariable ("rondas", $rondas) ;
		return $tmpHtml;
	}

	/*
	* Retorna la suma de ofertas de unas empresas_campo
	*/	
	function darSumaOfertaProductoCampo ($empresas) {
		$suma = 0 ;
		if (isset ($empresas))
		foreach ($empresas as $itemEmpresa) {
			$suma += $itemEmpresa['oferta'];
		}	
		return $suma;
	}

	/*
	* Retorna la suma de demandas de unos compradores_campo
	*/	
	function darSumaDemandaProductoCampo ($comprador) {
		$suma = 0 ;
		foreach ($comprador as $itemComprador) {
			$suma += (isset($itemComprador['demanda'])) ? $itemComprador['demanda'] : 0 ;
		}
		return $suma;
	}
	
	//Devuelve el nombre correcto del valor del tipo de producto
	function darNombreValorProductoReal2($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'firme' )
			$tipoProductoReal = 'firme' ;
		elseif ($tipoProducto == 'condicional')
			$tipoProductoReal = 'cfc' ;
		elseif ($tipoProducto == 'opcional')
			$tipoProductoReal = 'ocg' ;
		elseif ($tipoProducto == 'firmeC')
			$tipoProductoReal = 'firmeU' ;
		elseif ($tipoProducto == 'condicionalC')
			$tipoProductoReal = 'cfcU' ;
		elseif ($tipoProducto == 'opcionalC')
			$tipoProductoReal = 'ocgU' ;

		return $tipoProductoReal;
	}
	
	//Devuelve el nombre correcto del producto para demanda
	function darNombreProducto2($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'firme' )
			$tipoProductoReal = 'fijo' ;
		elseif ($tipoProducto == 'condicional')
			$tipoProductoReal = 'condicional' ;
		elseif ($tipoProducto == 'opcional')
			$tipoProductoReal = 'opcional' ;
		elseif ($tipoProducto == 'firmeC' )
			$tipoProductoReal = 'fijoU' ;
		elseif ($tipoProducto == 'condicionalC')
			$tipoProductoReal = 'condicionalU' ;
		elseif ($tipoProducto == 'opcionalC')
			$tipoProductoReal = 'opcionalU' ;

		return $tipoProductoReal;
	}

	//Devuelve el nombre correcto del valor del producto para demanda
	function darNombreProductoDemanda2($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'firme' )
			$tipoProductoReal = 'dFirme' ;
		elseif ($tipoProducto == 'condicional')
			$tipoProductoReal = 'dCfcU' ;
		elseif ($tipoProducto == 'opcional')
			$tipoProductoReal = 'dOcgU' ;
		elseif ($tipoProducto == 'firmeC')
			$tipoProductoReal = 'dFirmeC' ;
		elseif ($tipoProducto == 'condicionalC')
			$tipoProductoReal = 'dCfcC' ;
		elseif ($tipoProducto == 'opcionalC')
			$tipoProductoReal = 'dOcgC' ;

		return $tipoProductoReal;
	}

	//Devuelve el nombre correcto de la elasticidad para el producto para demanda
	function darNombreProductoElasticidadDemanda2($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'firme')
			$tipoProductoReal = 'elasticidadFirme' ;
		elseif ($tipoProducto == 'condicional')
			$tipoProductoReal = 'elasticidadDcfc' ;
		elseif ($tipoProducto == 'opcional')
			$tipoProductoReal = 'elasticidadDocg' ;
		elseif ($tipoProducto == 'firmeC')
			$tipoProductoReal = 'elasticidadDfirmeC' ;
		elseif ($tipoProducto == 'condicionalC')
			$tipoProductoReal = 'elasticidadDcfcC' ;
		elseif ($tipoProducto == 'opcionalC')
			$tipoProductoReal = 'elasticidadDocgC' ;

		return $tipoProductoReal;
	}

	//Función básica con el que se calcula la nueva cantidad
	function calcularQ1conElasticidad($Q0, $P1, $P0, $e1) {
		
		$tmpSuma = 1 * (1 + $e1) ;
		$tmpResta = 1 * (1 - $e1) ;
		
/*		echo "--------" .  $tmpSuma  . "<br />";
		echo "--------" .  $tmpResta * $P1 . "<br />";
		
		echo "<br /> Q0 ".$Q0 ."<br />P1 " . $P1 ."<br />P0" . $P0 ."<br />e1" . $e1  ."<br />" ;  */
		$arriba = $Q0 * (($P1 * $tmpResta) + ( $P0 * $tmpSuma)) ;
		$abajo  = (($P1 * $tmpSuma ) + ($P0 * $tmpResta)) ;
		$Q1 = $arriba / $abajo ;
/*
		
		echo "Echo a " .$arriba . "<br />";
		echo "Echo ab " .$abajo . "<br />";
		echo "Echo Q1 " .$Q1 . "<br />"; */
		return $Q1;
	}
	
	function buscarADondeSeFueComprador($rondas, $ronda, $idComprador, $tipoProductoD, $idCampo, $cantidadDemandada) {
		foreach($rondas[$ronda][$tipoProductoD]['campo'] as $campo) {
			if(isset($campo['idCampo'])) {
				$idCampo = $campo['idCampo'];
				
				foreach ($campo['comprador'] as $comprador) {
					$idCompradorN = $comprador['idComprador'];
					$cantidadDemandadaN = $comprador['demanda'];
					$tipoProductoN = $comprador['demanda'];
					if($idComprador == $idCompradorN && $cantidadDemandada == $cantidadDemandadaN && $tipoProductoD == $tipoProductoN) {
						return $idCampo;
					}
				}
			}
		}
		return false;
	}
	
	function calcularMovimientoDemandaRondaN($rondas, $ronda, $tmpArrayComprador, $productos ) {
		foreach ($tmpArrayComprador as $comprador => $idComprador) {
			foreach ($productos as $tipoProducto) {
				foreach($rondas[$ronda-1][$tipoProducto]['campo'] as $campo) {
					if(isset($campo['idCampo']) && $campo['exceso']==true) {
						$idCampo = $campo['idCampo'];
						
						foreach ($rondas[$ronda-1][$tipoProducto]['campo'][$idCampo]['comprador'] as $comprador) {
							$cantidadDemandada = $comprador['demanda'];
							$tipoProductoD = $comprador['tipoProducto'];
							
							$idCampoN = $this->buscarADondeSeFueComprador($rondas, $ronda, $idComprador, $tipoProductoD, $idCampo, $cantidadDemandada);
							
						}
					}
				}
			}
		}
	}
	//FIN CONDIGO NUEVO
	//FIN CONDIGO NUEVO
	//FIN CONDIGO NUEVO
	//FIN CONDIGO NUEVO
	
	// Retorna un array con la mejor oferta y denmanda por campo
	function darMejorOferta ($tmpArrayFirme, $tmpArrayRondaOfertas, $tmpArrayCampoProductoEmpresa) {
		//se recorre la lista de vendedores buscando la mejor oferta
		foreach ($tmpArrayFirme as $item) {
			$tmp = explode ('|', $item['id']);
			if (!isset($mejorOferta[$tmp[0]])) {
				$mejorOferta[$tmp[0]] = $item;
				$mejorOferta[$tmp[0]]['idCampo'] = $tmp[0];
				
				//Se le asigna el idEmpresa a la mejor oferta por producto
				$tmpArrayRondaOfertas[$tmp[0]]['fijo'] = $item['fijo'];
				$tmpArrayRondaOfertas[$tmp[0]]['condicional'] = $item['condicional'];
				$tmpArrayRondaOfertas[$tmp[0]]['opcional'] = $item['opcional'];
				$tmpArrayRondaOfertas[$tmp[0]]['fijoU'] = $item['fijoU'];
				$tmpArrayRondaOfertas[$tmp[0]]['condicionalU'] = $item['condicionalU'];
				$tmpArrayRondaOfertas[$tmp[0]]['opcionalU'] =$item['opcionalU'];

				//El arreglo guarda las empresas que están incluidas en la oferta de la ronda
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijo'] = $tmp[1];
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicional'] = $tmp[1];
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcional'] = $tmp[1];
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijoU'] = $tmp[1];
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicionalU'] = $tmp[1];
				$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcionalU'] = $tmp[1];
				}
			else
			{
				if ($mejorOferta[$tmp[0]]['firme'] > $item['firme'] && $item['firme'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['fijo'] = $item['fijo'];
					$mejorOferta[$tmp[0]]['firme'] = $item['firme'];
					$tmpArrayRondaOfertas[$tmp[0]]['fijo'] = $item['fijo'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijo'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['firme'] == $item['firme']) {
					$tmpArrayRondaOfertas[$tmp[0]]['fijo'] += $item['fijo'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijo'] .= "," . $tmp[1];
				}
				
				
				if ($mejorOferta[$tmp[0]]['cfc'] > $item['cfc'] && $item['condicional'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['condicional'] = $item['condicional'];
					$mejorOferta[$tmp[0]]['cfc'] = $item['cfc'];
					$tmpArrayRondaOfertas[$tmp[0]]['condicional'] = $item['condicional'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicional'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['cfc'] == $item['cfc']) {
					$tmpArrayRondaOfertas[$tmp[0]]['condicional'] += $item['condicional'];				
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicional'] .= "," . $tmp[1];
				}
				
				
				if ($mejorOferta[$tmp[0]]['ocg'] > $item['ocg'] && $item['opcional'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['opcional'] = $item['opcional'];
					$mejorOferta[$tmp[0]]['ocg'] = $item['ocg'];
					$tmpArrayRondaOfertas[$tmp[0]]['opcional'] = $item['opcional'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcional'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['ocg'] == $item['ocg']) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$tmpArrayRondaOfertas[$tmp[0]]['opcional'] += $item['opcional'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcional'] .= "," . $tmp[1];
				}
				
				if ($mejorOferta[$tmp[0]]['firmeU'] > $item['firmeU'] && $item['fijoU'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['fijoU'] = $item['fijoU'];
					$mejorOferta[$tmp[0]]['firmeU'] = $item['firmeU'];
					$tmpArrayRondaOfertas[$tmp[0]]['fijoU'] = $item['fijoU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijo'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['firmeU'] == $item['firmeU']) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$tmpArrayRondaOfertas[$tmp[0]]['fijoU'] += $item['fijoU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['fijo'] .= "," . $tmp[1];
				}
				
				if ($mejorOferta[$tmp[0]]['cfcU'] > $item['cfcU'] && $item['condicionalU'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['condicionalU'] = $item['condicionalU'];
					$mejorOferta[$tmp[0]]['cfcU'] = $item['cfcU'];
					$tmpArrayRondaOfertas[$tmp[0]]['condicionalU'] = $item['condicionalU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicionalU'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['cfcU'] == $item['cfcU']) {
					$tmpArrayRondaOfertas[$tmp[0]]['condicionalU'] += $item['condicionalU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['condicionalU'] .= "," . $tmp[1];
				}
				
				if ($mejorOferta[$tmp[0]]['ocgU'] > $item['ocgU'] && $item['opcionalU'] > 0) {
					$mejorOferta[$tmp[0]]['id'] = $item['id'];
					$mejorOferta[$tmp[0]]['opcionalU'] = $item['opcionalU'];
					$mejorOferta[$tmp[0]]['ocgU'] = $item['ocgU'];
					$tmpArrayRondaOfertas[$tmp[0]]['opcionalU'] = $item['opcionalU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcionalU'] = $tmp[1];
				}
				elseif ($mejorOferta[$tmp[0]]['ocgU'] == $item['ocgU']) {
					$tmpArrayRondaOfertas[$tmp[0]]['opcionalU'] += $item['opcionalU'];
					$tmpArrayCampoProductoEmpresa[$tmp[0]]['opcionalU'] .= "," . $tmp[1];
				}
			}
		}
		
		$mejorOferta = $this->sumaOfertasValorCampoIgual ($mejorOferta , $tmpArrayFirme, $tmpArrayCampoProductoEmpresa) ;
				
		return array ( 0 => $mejorOferta , 1 => $tmpArrayRondaOfertas, 2 => $tmpArrayCampoProductoEmpresa) ;		
	}

	// se recorre la mejor oferta y campos emperesa para hacer la suma de ofertas dado el mismo valor
	// todas las variables deben estar inciadas y como llave del array tener el idCampo
	// retorna la mejor oferta con la suma de los campos empresa que participan en la oferta
    function sumaOfertasValorCampoIgual ($mejorOferta , $tmpArrayFirme, $tmpArrayCampoProductoEmpresa) {
		foreach ($tmpArrayCampoProductoEmpresa as $llaveProductoEmpresa => $valorPoductoEmpresa) {

		$tmpFijo = explode (',',$valorPoductoEmpresa['fijoU']) ;

		// para 5 años
		$mejorOferta[$llaveProductoEmpresa]['fijoU'] = 0;
		foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['firmeU'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['firmeU'] )
			  $mejorOferta[$llaveProductoEmpresa]['fijoU'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['fijoU'] ;
		}
					  
		$tmpFijo = explode (',',$valorPoductoEmpresa['condicionalU']) ;
		$mejorOferta[$llaveProductoEmpresa]['condicionalU'] = 0;
		foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['cfcU'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['cfcU'] )
				  $mejorOferta[$llaveProductoEmpresa]['condicionalU'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['condicionalU'] ;
		}
	   
		$tmpFijo = explode (',',$valorPoductoEmpresa['opcionalU']) ;
		$mejorOferta[$llaveProductoEmpresa]['opcionalU'] = 0;
		foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['ocgU'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['ocgU'] )
			  $mejorOferta[$llaveProductoEmpresa]['opcionalU'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['opcionalU'] ;
		}
					  
	   // para 1 año                                                                    
	   $tmpFijo = explode (',',$valorPoductoEmpresa['fijo']) ;
	   $mejorOferta[$llaveProductoEmpresa]['fijo'] = 0;
	   foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['firme'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['firme'] )
			  $mejorOferta[$llaveProductoEmpresa]['fijo'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['fijo'] ;
		}
					  
	   $tmpFijo = explode (',',$valorPoductoEmpresa['condicional']) ;
	   $mejorOferta[$llaveProductoEmpresa]['condicional'] = 0;
	   foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['cfc'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['cfc'] )
				$mejorOferta[$llaveProductoEmpresa]['condicional'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['condicional'] ;
		}
					  
	   $tmpFijo = explode (',',$valorPoductoEmpresa['opcional']) ;
	   $mejorOferta[$llaveProductoEmpresa]['opcional'] = 0;
	   foreach ($tmpFijo as $valorEmpresa) {
			if ($mejorOferta[$llaveProductoEmpresa]['ocg'] == $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['ocg'] )
				$mejorOferta[$llaveProductoEmpresa]['opcional'] += $tmpArrayFirme[$llaveProductoEmpresa .'|'. $valorEmpresa]['opcional'] ;
		}

		}
					  
        return $mejorOferta ;
    } 
	             
	/*
	* Devuelve array con la suma total por campo de la oferta de gas
	*/
	function darOfertaDeGasTotal($tmpArrayFirme) {
		$tmpCampo = array ();
		foreach ($tmpArrayFirme as $item) {
			$tmp = explode ('|', $item['id']);
			if (isset ($tmpCampo[$tmp[0]]['fijo']))
				$tmpCampo[$tmp[0]]['fijo'] += $item['fijo'] ;
			else
				$tmpCampo[$tmp[0]]['fijo'] = $item['fijo'] ;
			
			if (isset ( $tmpCampo[$tmp[0]]['condicional'] ))
				$tmpCampo[$tmp[0]]['condicional'] += $item['condicional'];
			else
				$tmpCampo[$tmp[0]]['condicional'] = $item['condicional'];
			
			if (isset ( $tmpCampo[$tmp[0]]['opcional'] ))
				$tmpCampo[$tmp[0]]['opcional'] += $item['opcional'];
			else
				$tmpCampo[$tmp[0]]['opcional'] = $item['opcional'];
			
			if (isset ( $tmpCampo[$tmp[0]]['fijoU'] )) 
				$tmpCampo[$tmp[0]]['fijoU'] += $item['fijoU'];
			else
				$tmpCampo[$tmp[0]]['fijoU'] = $item['fijoU'];
			
			if (isset ( $tmpCampo[$tmp[0]]['condicionalU'] )) 
				$tmpCampo[$tmp[0]]['condicionalU'] += $item['condicionalU'];
			else
				$tmpCampo[$tmp[0]]['condicionalU'] = $item['condicionalU'];
			
			if (isset ( $tmpCampo[$tmp[0]]['opcionalU'] )) 
				$tmpCampo[$tmp[0]]['opcionalU'] += $item['opcionalU'];		
			else
				$tmpCampo[$tmp[0]]['opcionalU'] = $item['opcionalU'];
			}
		return $tmpCampo;
	}
	
	// Retorna la demanda por campo para la ronda 0
	function darDemandaRondaCero ($tmpArrayMejorOferta, $unSesion ) {
		
		$tmpArrayTransporte = $unSesion->obtenerVariable ('transporte');
		$tmpArrayDestino    = $unSesion->obtenerVariable ('listDestino');  
		$tmpArrayComprador  = $unSesion->obtenerVariable ('listComprador'); 
		$tmpArrayDemanda    = $unSesion->obtenerVariable ('listDemanda'); 
		$tmpArrayCiudadesEscenario = $unSesion->obtenerVariable ('listCiudadesEscenario') ;

		$tmpArrayCampoComercializadorCiudad = array();
		$demanda = array();
		$tmpIdCampo = 0;
		foreach ($tmpArrayComprador as $llaveComprador => $itemComprador ) {
			$sumaValorFirmeTransporte  = -1;
			$sumaValorCfcTransporte    = -1;
			$sumaValorOcgTransporte    = -1;
			$sumaValorFirmeUTransporte = -1;
			$sumaValorCfcUTransporte   = -1;
			$sumaValorOcgUTransporte   = -1;
			foreach ($tmpArrayDestino[$itemComprador] as $llaveDestino => $itemDestino) {
				foreach ($tmpArrayMejorOferta as $llave => $item ) {
					
					/* Demanda 1 año firme */
					if (!isset ($demanda[$item['idCampo']]['firme'])) 
						$demanda[$item['idCampo']]['firme'] = 0;
						
					if ($sumaValorFirmeTransporte < 0) {
						$sumaValorFirmeTransporte = $item['firme'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampo = $item['idCampo'] ;
						}
					elseif ($sumaValorFirmeTransporte > ($item['firme'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorFirmeTransporte = $item['firme'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampo = $item['idCampo'] ;
						}
						
					/* Demanda 1 año CFC */
					if (!isset ($demanda[$item['idCampo']]['cfc'])) 
						$demanda[$item['idCampo']]['cfc'] = 0;

					if ($sumaValorCfcTransporte < 0) {
						$sumaValorCfcTransporte = $item['cfc'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampoCfc = $item['idCampo'] ;
						}
					elseif ($sumaValorCfcTransporte > ($item['cfc'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorCfcTransporte = $item['cfc'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampoCfc = $item['idCampo'] ;
						} 
						
					/* Demanda 1 año OCG */
					if (!isset ($demanda[$item['idCampo']]['ocg'])) 
						$demanda[$item['idCampo']]['ocg'] = 0;

					if ($sumaValorOcgTransporte < 0) {
						$sumaValorOcgTransporte = $item['ocg'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampoOcg = $item['idCampo'] ;
						}
					elseif ($sumaValorOcgTransporte > ($item['ocg'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorOcgTransporte = $item['ocg'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampoOcg = $item['idCampo'] ;
						} 
						
					/* Demanda 5 años firme */
					if (!isset ($demanda[$item['idCampo']]['firmeU'])) 
						$demanda[$item['idCampo']]['firmeU'] = 0;

					if ($sumaValorFirmeUTransporte < 0) {
						$sumaValorFirmeUTransporte = $item['firmeU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampoFirmeU = $item['idCampo'] ;
						}
					elseif ($sumaValorFirmeUTransporte > ($item['firmeU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorFirmeUTransporte = $item['firmeU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampoFirmeU = $item['idCampo'] ;
						} 
						

					/* Demanda 5 años cfc */
					if (!isset ($demanda[$item['idCampo']]['cfcU'])) 
						$demanda[$item['idCampo']]['cfcU'] = 0;

					if ($sumaValorCfcUTransporte < 0) {
						$sumaValorCfcUTransporte = $item['cfcU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampoCfcU = $item['idCampo'] ;
						}
					elseif ($sumaValorCfcUTransporte > ($item['cfcU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorCfcUTransporte = $item['cfcU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampoCfcU = $item['idCampo'] ;
						} 						
					

					/* Demanda 5 años OCG */
					if (!isset ($demanda[$item['idCampo']]['ocgU'])) 
						$demanda[$item['idCampo']]['ocgU'] = 0;

					if ($sumaValorOcgUTransporte < 0) {
						$sumaValorOcgUTransporte = $item['ocgU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];
						$tmpIdCampoOcgU = $item['idCampo'] ;
						}
					elseif ($sumaValorOcgUTransporte > ($item['ocgU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']])) {
						$sumaValorOcgUTransporte = $item['ocgU'] + $tmpArrayTransporte[$itemDestino . "|" . $item['idCampo']];	
						$tmpIdCampoOcgU = $item['idCampo'] ;
						} 					
				}
				
				$tmpSumaFirme = (isset($tmpArrayDemanda[$itemComprador]['dFirme'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dFirme'][$llaveDestino] : 0;
				$demanda[$tmpIdCampo]['firme']  += $tmpSumaFirme;

				$tmpSumaCfc = (isset($tmpArrayDemanda[$itemComprador]['dCfcU'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dCfcU'][$llaveDestino] : 0;
				$demanda[$tmpIdCampoCfc]['cfc'] += $tmpSumaCfc;

				$tmpSumaOcg = (isset($tmpArrayDemanda[$itemComprador]['dOcgU'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dOcgU'][$llaveDestino] : 0;
				$demanda[$tmpIdCampoOcg]['ocg']   += $tmpSumaOcg;

				$tmpSumaFirmeU = (isset($tmpArrayDemanda[$itemComprador]['dFirmeU'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dFirmeU'][$llaveDestino] : 0;
				$demanda[$tmpIdCampoFirmeU]['firmeU']   += $tmpSumaFirmeU;

				$tmpSumaCfcU = (isset($tmpArrayDemanda[$itemComprador]['dCfcC'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dCfcC'][$llaveDestino] : 0;
				$demanda[$tmpIdCampoCfcU]['cfcU']   += $tmpSumaCfcU;

				$tmpSumaOcgU = (isset($tmpArrayDemanda[$itemComprador]['dOcgC'][$llaveDestino])) ? $tmpArrayDemanda[$itemComprador]['dOcgC'][$llaveDestino] : 0;
				$demanda[$tmpIdCampoOcgU]['ocgU']   += $tmpSumaOcgU;
				
				//El arreglo guarda a donde se van los comercializadores
				if($tmpSumaFirme>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampo]['fijo'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				if($tmpSumaCfc>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampoCfc]['condicional'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				if($tmpSumaOcg>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampoOcg]['opcional'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				if($tmpSumaFirmeU>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampoFirmeU]['fijoU'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				if($tmpSumaCfcU>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampoCfcU]['condicionalU'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				if($tmpSumaOcgU>0)
					$tmpArrayCampoComercializadorCiudad[$tmpIdCampoOcgU]['opcionalU'][$itemComprador . "|" . $itemDestino] = $itemComprador . "|" . $itemDestino;
				
			}
		}
		
		return array ( 0 => $demanda , 1 => $tmpArrayCampoComercializadorCiudad) ;
	}
	

	function verificarDemanda ($ronda, $mejorOferta, $arrayDemanda, $tmpArrayCampoComercializadorCiudad, $unSesion) {
	
		$tmpArrayRondas = $unSesion->obtenerVariable ('rondas');
		$numRonda = $unSesion->obtenerVariable ('numeroRondas');
		$arrayExceso = $unSesion->obtenerVariable ('arrayExceso');
		$tmpArrayDemanda  = $unSesion->obtenerVariable ('listDemanda');
		$tmpArrayDestino  = $unSesion->obtenerVariable ('listDestino');
		
		//Reviso todos los campos que tuvieron exceso de demanda para ver como quedaron
		foreach ($arrayExceso as $campo) {
			foreach ($campo as $producto) {
				//Se vuela el id del campo
				if(!is_numeric($producto)) {
					
					$nombreProducto = $this->darNombreProductoReal($producto);
					$nombreValorProducto = $this->darNombreValorProductoReal($producto);
					$nombreProductoDemanda = $this->darNombreProductoDemanda($producto);
					$nombreElasticidadProducto =$this->darNombreElasticidadProducto($producto);
					
					//Reviso que en la ronda actual la demanda sea igual o mayor
//					echo "<pre> oferta vs demanda";
//					echo $mejorOferta[$campo[1]][$nombreProducto] . "--" . $arrayDemanda[$campo[1]][$nombreValorProducto];
//					echo "</pre>";
//					echo "<pre> Comercializado Ciudad </br>";
					if ($mejorOferta[$campo[1]][$nombreProducto] <= $arrayDemanda[$campo[1]][$nombreValorProducto]) {
						foreach($tmpArrayCampoComercializadorCiudad[$numRonda][$campo[1]][$nombreProducto] as $ids) {
//					print_r($tmpArrayCampoComercializadorCiudad[$numRonda][$campo[1]][$nombreProducto]) . "</br>";
							$tmpIds = explode ("|", $ids);
							$tmpIdComercializador = $tmpIds[0];
							$tmpIdCiudad = $tmpIds[1];
							
							//Busca la ciudad
							$pos = 0;
							$entro = false;
							foreach($tmpArrayDestino[$tmpIdComercializador] as $idCiudad) {
								if($tmpIdCiudad == $idCiudad || $entro)
									$entro = true;
								else
									$pos = $pos + 1;
							}
//					echo "<br> pos de ciudad </br>";
//					echo $pos . "</br>";
							
							$Q0 = $tmpArrayDemanda[$tmpIdComercializador][$nombreProductoDemanda][$pos];
							$P0 = $tmpArrayRondas[count($tmpArrayRondas)-1]['mejorOferta'][$campo[1]][$nombreValorProducto];
							$P1 = $mejorOferta[$campo[1]][$nombreValorProducto];
							$e1 = $tmpArrayDemanda[$tmpIdComercializador][$nombreElasticidadProducto][$pos];
/*					echo "<br> Q0 </br>";
					echo $Q0 . "</br>";
					echo "<br> P0 </br>";
					echo $P0 . "</br>";
					echo "<br> P1 </br>";
					echo $P1 . "</br>";
					echo "<br> e1 </br>";
					echo $e1 . "</br>";*/
							
							$Q1 = $this->calcularQ1conElasticidad($Q0, $P1, $P0, $e1);
//					echo "<br> Q1 </br>";
//					echo $Q1 . "</br>";
//					echo $tmpArrayDemanda[$tmpIdComercializador][$nombreProductoDemanda][$pos] . "</br>";
							$tmpArrayDemanda[$tmpIdComercializador][$nombreProductoDemanda][$pos] = $Q1;
						}
					//echo "</pre>";
					}
					else {
						//echo "<pre>";
						//print_r($tmpArrayRondas[$numRonda]['mejorOferta'][$campo[1]]);
						//echo "</pre>";
					}
				}
			}
		}
	}
	
	
	
	// Método que valida la demanda
	// se valida que no se asigne una demanda en una oferta = 0 segun el tipo de producto
	// se usa el criterio de comprador tipo A y B
	function validaDemandaNoEnOfertaCero ($mejorOferta, $arrayDemanda, $tmpArrayCampoComercializadorCiudad) {
	   foreach ($mejorOferta as $llaveOferta => $itemOferta) {
		   if ($itemOferta['fijo'] == 0 && $itemOferta['fijoU'] > 0) {
			   $arrayDemanda[$llaveOferta]['firmeU'] += $arrayDemanda[$llaveOferta]['firme'] ;
			   $arrayDemanda[$llaveOferta]['firme'] = 0; 
			   
			   if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'])) {
				   foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'] as $ids){
						$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'][$arrayDemanda[$llaveOferta]['firme']] = $ids;
				   }
					unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][fijo]']);
				}
		   }
		   elseif ($itemOferta['fijo'] > 0 && $itemOferta['fijoU'] == 0) {
			   $arrayDemanda[$llaveOferta]['firme'] += $arrayDemanda[$llaveOferta]['firmeU'] ;
			   $arrayDemanda[$llaveOferta]['firmeU'] = 0; 

			   if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'])) {
				   foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'] as $ids){
						$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'][$arrayDemanda[$llaveOferta]['firmeU']] = $ids;
				   }
					unset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU']);
				}
		   }

		   if ($itemOferta['condicional'] == 0) {
				if($itemOferta['condicionalU'] > 0) {
				   $arrayDemanda[$llaveOferta]['cfcU'] += $arrayDemanda[$llaveOferta]['cfc'] ;
				   $arrayDemanda[$llaveOferta]['cfc'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'][$arrayDemanda[$llaveOferta]['cfc']] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicional]']);
					}
				}
				elseif ($itemOferta['fijoU'] > 0) {
				   $arrayDemanda[$llaveOferta]['firmeU'] += $arrayDemanda[$llaveOferta]['cfc'] ;
				   $arrayDemanda[$llaveOferta]['cfc'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicional]']);
					}
				}
				elseif ($itemOferta['fijo'] > 0) {
				   $arrayDemanda[$llaveOferta]['firme'] += $arrayDemanda[$llaveOferta]['cfc'] ;
				   $arrayDemanda[$llaveOferta]['cfc'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicional]']);
					}
				}
			}
		   
		   if ($itemOferta['condicionalU'] == 0) {
				if($itemOferta['condicional'] > 0) {
				   $arrayDemanda[$llaveOferta]['cfc'] += $arrayDemanda[$llaveOferta]['cfcU'] ;
				   $arrayDemanda[$llaveOferta]['cfcU'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicional'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicionalU]']);
					}
				}
				elseif ($itemOferta['fijoU'] > 0) {
				   $arrayDemanda[$llaveOferta]['firmeU'] += $arrayDemanda[$llaveOferta]['cfcU'] ;
				   $arrayDemanda[$llaveOferta]['cfcU'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicionalU]']);
					}
				}
				elseif ($itemOferta['fijo'] > 0) {
				   $arrayDemanda[$llaveOferta]['firme'] += $arrayDemanda[$llaveOferta]['cfcU'] ;
				   $arrayDemanda[$llaveOferta]['cfcU'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['condicionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][condicionalU]']);
					}
				}
		   }

		   if ($itemOferta['opcional'] == 0) {
				if($itemOferta['opcionalU'] > 0) {
					$arrayDemanda[$llaveOferta]['ocgU'] += $arrayDemanda[$llaveOferta]['ocg'] ;
					$arrayDemanda[$llaveOferta]['ocg'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcional]']);
					}
				}
				elseif ($itemOferta['fijoU'] > 0) {
				   $arrayDemanda[$llaveOferta]['firmeU'] += $arrayDemanda[$llaveOferta]['ocg'] ;
				   $arrayDemanda[$llaveOferta]['ocg'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcional]']);
					}

				}
				elseif ($itemOferta['fijo'] > 0) {
				   $arrayDemanda[$llaveOferta]['firme'] += $arrayDemanda[$llaveOferta]['ocg'] ;
				   $arrayDemanda[$llaveOferta]['ocg'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcional]']);
					}
				}
		   }
		   
		   if ($itemOferta['opcionalU'] == 0) {
				if($itemOferta['opcional'] > 0) {
					$arrayDemanda[$llaveOferta]['ocg'] += $arrayDemanda[$llaveOferta]['ocgU'] ;
					$arrayDemanda[$llaveOferta]['ocgU'] = 0; 

					if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcional'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcionalU]']);
					}
				}
				elseif ($itemOferta['fijoU'] > 0) {
				   $arrayDemanda[$llaveOferta]['firmeU'] += $arrayDemanda[$llaveOferta]['ocgU'] ;
				   $arrayDemanda[$llaveOferta]['ocgU'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijoU'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcionalU]']);
					}
				}
				elseif ($itemOferta['fijo'] > 0) {
				   $arrayDemanda[$llaveOferta]['firme'] += $arrayDemanda[$llaveOferta]['ocgU'] ;
				   $arrayDemanda[$llaveOferta]['ocgU'] = 0; 

				   	if(isset($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'])) {
						foreach($tmpArrayCampoComercializadorCiudad[$llaveOferta]['opcionalU'] as $ids){
							$tmpArrayCampoComercializadorCiudad[$llaveOferta]['fijo'][$ids] = $ids;
						}
						unset($GLOBALS['tmpArrayCampoComercializadorCiudad[' . $llaveOferta . '][opcionalU]']);
					}
				}
		   }
		}
		
		return array ( 0 => $arrayDemanda , 1 => $tmpArrayCampoComercializadorCiudad) ;
	}

	// Retorna el HTMl que muestra la última ronda con el exceso de oferta
	function generaHtml ($rondas, $ronda, $arrayExceso, $tmpListaCampo, $ofertaReglaExceso, $tmpArrayRondaOfertas ) {

		foreach ($rondas[$ronda]['mejorOferta'] as $llave => $valor) {
			$ofertaReglaExceso .= '			<tr>
					<td> ' . $tmpListaCampo[$llave] . ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['fijo'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['firme'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['fijo'], $rondas[$ronda]['demanda'][$llave]['firme'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['firme']. ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['condicional'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['cfc'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['condicional'], $rondas[$ronda]['demanda'][$llave]['cfc'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['cfc']. ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['opcional'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['ocg'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['opcional'], $rondas[$ronda]['demanda'][$llave]['ocg'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['ocg']. ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['fijoU'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['firmeU'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['fijoU'], $rondas[$ronda]['demanda'][$llave]['firmeU'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['firmeU']. ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['condicionalU'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['cfcU'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['condicionalU'], $rondas[$ronda]['demanda'][$llave]['cfcU'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['cfcU']. ' </td>
					<td> ' . $tmpArrayRondaOfertas[$valor['idCampo']]['opcionalU'] . ' </td>
					<td> ' . $rondas[$ronda]['mejorOferta'][$llave]['ocgU'] . ' </td>
					<td ' . $this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['opcionalU'], $rondas[$ronda]['demanda'][$llave]['ocgU'])  . '> ' . $rondas[$ronda]['demanda'][$llave]['ocgU']. ' </td>
				</tr>';	
				
				//En los siguientes condicionales se agrega todos los campos por tipo de producto que tienen sobre demanda
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['fijo'], $rondas[$ronda]['demanda'][$llave]['firme'])) {			
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][2] = 'Firme 1 año';
				}
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['condicional'], $rondas[$ronda]['demanda'][$llave]['cfc'])) {
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][3] = 'Condicional 1 año';
				}
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['opcional'], $rondas[$ronda]['demanda'][$llave]['ocg'])) {
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][4] = 'Opcional 1 año';
				}
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['fijoU'], $rondas[$ronda]['demanda'][$llave]['firmeU'])) {
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][5] = 'Firme 5 años';
				}
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['condicionalU'], $rondas[$ronda]['demanda'][$llave]['cfcU'])) {
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][6] = 'Condicional 5 años';
				}
				if ($this->darEstilo ($rondas[$ronda]['mejorOferta'][$llave]['opcionalU'], $rondas[$ronda]['demanda'][$llave]['ocgU'])) {
					$arrayExceso[$llave][1] = $llave;
					$arrayExceso[$llave][7] = 'Opcional 5 años';
				}
			}
			return 	array ($ofertaReglaExceso, $arrayExceso);	
		}
		
	/*
	* genera html de las rondas corridas
	*/          
	function generaHtmlHistorico ($rondas, $ronda, $tmpListaCampo ) {
	   $tmpHtml = '';
	   $ronda = count ( $rondas ) - 1 ; 
	   krsort( $rondas ) ;
	   foreach ($rondas as $tRonda) {
		   foreach ($tmpListaCampo as $llave => $campo) {
			   $tmpHtml .= '<tr>';
			   $tmpHtml .= '<td>' . $ronda . "</td>";
			   $tmpHtml .= '<td>' . $campo . "</td>";
			   
			   $tmpSumaOferta = (isset($rondas[$ronda]['firme']['campo'][$llave])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['firme']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaDemanda = (isset($rondas[$ronda]['firme']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['firme']['campo'][$llave]['comprador']) : 0;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			   $tmpHtml .= '<td>';
			   $tmpHtml .= (isset($rondas[$ronda]['firme']['campo'][$llave])) ? $rondas[$ronda]['firme']['campo'][$llave]['precio'] : 0;
			   $tmpHtml .= '</td>';
			   
/*			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['firme']['campo'][$llave]['exceso'] = true ;
			   else
				 $rondas[$ronda]['firme']['campo'][$llave]['exceso'] = false ; */
				 
			   $tmpHtml .= '<td ' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . ' >' . $tmpSumaDemanda . "</td>";
			   $temp = (isset($rondas[$ronda]['condicional']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['condicional']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaOferta = (isset($rondas[$ronda]['condicional']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['condicional']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaDemanda = (isset ($rondas[$ronda]['condicional']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['condicional']['campo'][$llave]['comprador']) : 0;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			   $tmpHtml .= '<td>';
			   $tmpHtml .= (isset($rondas[$ronda]['condicional']['campo'][$llave]['precio'])) ? $rondas[$ronda]['condicional']['campo'][$llave]['precio'] : 0;
			   $tmpHtml .= '</td>';
			   
/*			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['condicional']['campo'][$llave]['exceso'] = true ;         
			   else
				 $rondas[$ronda]['condicional']['campo'][$llave]['exceso'] = false ;     */    
							 
			   $tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			   $temp = (isset($rondas[$ronda]['opcional']['campo'][$llave])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['opcional']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaOferta = $temp;
			   $tmpSumaDemanda = (isset ($rondas[$ronda]['opcional']['campo'][$llave]['comprador'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['opcional']['campo'][$llave]['comprador']) : 0;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
				   $tmpHtml .= '<td>' . $rondas[$ronda]['opcional']['campo'][$llave]['precio'] . "</td>";
/*			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				  $rondas[$ronda]['opcional']['campo'][$llave]['exceso'] = true ;
			   else
				  $rondas[$ronda]['opcional']['campo'][$llave]['exceso'] = false ; */
											 
			   $tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			   $tmpSumaOferta = (isset($rondas[$ronda]['firmeC']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['firmeC']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaDemanda = (isset ($rondas[$ronda]['firmeC']['campo'][$llave]['comprador'])) ?  $this->darSumaDemandaProductoCampo ($rondas[$ronda]['firmeC']['campo'][$llave]['comprador']) : 0 ;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			   $vacio = (isset($rondas[$ronda]['firmeC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['firmeC']['campo'][$llave]['precio'] : 0 ;
			   $tmpHtml .= '<td>' . $vacio . "</td>";
	/*		   
			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				  $rondas[$ronda]['firmeC']['campo'][$llave]['exceso'] = true ;
			   else
				  $rondas[$ronda]['firmeC']['campo'][$llave]['exceso'] = false ; 
		*/	  
			   $tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			   $tmpSumaOferta = (isset ($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa'])) ? $this->darSumaOfertaProductoCampo ($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa']) : 0;
			   $tmpSumaDemanda = (isset($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['condicionalC']['campo'][$llave]['empresa']) : 0 ;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			   $vacio = (isset($rondas[$ronda]['condicionalC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['condicionalC']['campo'][$llave]['precio'] : 0 ;
			   $tmpHtml .= '<td>' . $vacio . "</td>";
/*			   
			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				$rondas[$ronda]['condicionalC']['campo'][$llave]['exceso'] = true;
			   else
				$rondas[$ronda]['condicionalC']['campo'][$llave]['exceso'] = false;      
			  */
			   $tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			   $tmpSumaOferta = (isset ($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa'])) ?$this->darSumaOfertaProductoCampo ($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa']) : 0 ;
			   $tmpSumaDemanda = (isset ($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa'])) ? $this->darSumaDemandaProductoCampo ($rondas[$ronda]['opcionalC']['campo'][$llave]['empresa']) : 0 ;
			   $tmpHtml .= '<td>' . $tmpSumaOferta . "</td>";
			   $vacio = (isset($rondas[$ronda]['opcionalC']['campo'][$llave]['precio'])) ? $rondas[$ronda]['opcionalC']['campo'][$llave]['precio'] : 0 ;
			   $tmpHtml .= '<td>' . $vacio . "</td>";
/*			   
			   if ($this->darEstilo($tmpSumaOferta, $tmpSumaDemanda))
				  $rondas[$ronda]['opcionalC']['campo'][$llave]['exceso'] = true ;
			   else
				  $rondas[$ronda]['opcionalC']['campo'][$llave]['exceso'] = false ;           
	*/		  
			   $tmpHtml .= '<td' . $this->darEstilo($tmpSumaOferta, $tmpSumaDemanda) . '>' . $tmpSumaDemanda . "</td>";
			   $tmpHtml .= '<tr>';
			}
			$ronda--;
		}
	   return $tmpHtml;
	}	
	
	//Devuelve el estilo que debe adoptar una celda al tener sobre oferta
	function darEstilo ($a , $b) {
		if ($a < $b) {
			return ' style="background-color:#FFFF99" ';
			}
		else
			return false;	
		}	
		
	/* 
	* Actualiza registro exceso campo por producto
	*/
	function actulizaExcesoCampo () {
//		$rondas = 
		}
		
	//Devuelve el nombre correcto del valor del tipo de producto
	function darNombreValorProductoReal($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'Firme 1 año' )
			$tipoProductoReal = 'firme' ;
		elseif ($tipoProducto == 'Condicional 1 año')
			$tipoProductoReal = 'cfc' ;
		elseif ($tipoProducto == 'Opcional 1 año')
			$tipoProductoReal = 'ocg' ;
		elseif ($tipoProducto == 'Firme 5 años')
			$tipoProductoReal = 'firmeU' ;
		elseif ($tipoProducto == 'Condicional 5 años')
			$tipoProductoReal = 'cfcU' ;
		elseif ($tipoProducto == 'Opcional 5 años')
			$tipoProductoReal = 'ocgU' ;

		return $tipoProductoReal;
	}
	
	//Devuelve el nombre correcto del valor del producto para demanda
	function darNombreProductoDemanda($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'Firme 1 año' )
			$tipoProductoReal = 'dFirme' ;
		elseif ($tipoProducto == 'Condicional 1 año')
			$tipoProductoReal = 'dCfcU' ;
		elseif ($tipoProducto == 'Opcional 1 año')
			$tipoProductoReal = 'dOcgU' ;
		elseif ($tipoProducto == 'Firme 5 años')
			$tipoProductoReal = 'dFirmeC' ;
		elseif ($tipoProducto == 'Condicional 5 años')
			$tipoProductoReal = 'dCfcC' ;
		elseif ($tipoProducto == 'Opcional 5 años')
			$tipoProductoReal = 'dOcgC' ;

		return $tipoProductoReal;
	}

	//Devuelve el nombre correcto de la elasticidad para el producto para demanda
	function darNombreElasticidadProducto($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'Firme 1 año' )
			$tipoProductoReal = 'elasticidadFirme' ;
		elseif ($tipoProducto == 'Condicional 1 año')
			$tipoProductoReal = 'elasticidadDcfc' ;
		elseif ($tipoProducto == 'Opcional 1 año')
			$tipoProductoReal = 'elasticidadDocg' ;
		elseif ($tipoProducto == 'Firme 5 años')
			$tipoProductoReal = 'elasticidadDfirmeC' ;
		elseif ($tipoProducto == 'Condicional 5 años')
			$tipoProductoReal = 'elasticidadDcfcC' ;
		elseif ($tipoProducto == 'Opcional 5 años')
			$tipoProductoReal = 'elasticidadDocgC' ;

		return $tipoProductoReal;
	}

	//Devuelve el nombre correcto del tipo de producto
	function darNombreProductoReal($tipoProducto) {
		
		$tipoProductoReal = '' ;
		if ($tipoProducto == 'Firme 1 año' )
			$tipoProductoReal = 'fijo' ;
		elseif ($tipoProducto == 'Condicional 1 año')
			$tipoProductoReal = 'condicional' ;
		elseif ($tipoProducto == 'Opcional 1 año')
			$tipoProductoReal = 'opcional' ;
		elseif ($tipoProducto == 'Firme 5 años')
			$tipoProductoReal = 'fijoU' ;
		elseif ($tipoProducto == 'Condicional 5 años')
			$tipoProductoReal = 'condicionalU' ;
		elseif ($tipoProducto == 'Opcional 5 años')
			$tipoProductoReal = 'opcionalU' ;

		return $tipoProductoReal;
	}
	
	/**
	*
	* Valida en rondas que no haya exceso para el producto
	*
	*/ 	
	
	function validaExcesoCampo ($unSesion, $productos) {
		$tmpRondas = $unSesion->obtenerVariable('rondas');
		$tmpUltimaRonda = $tmpRondas[count($tmpRondas) - 1 ] ;
		foreach ($productos as $producto) {
			foreach ($tmpUltimaRonda[$producto]['campo'] as $tmpEmpresa ) {
				if ($tmpEmpresa['exceso']) 
					return true ;
				}
			}
		return false ;	
		}
	
	function darContrato($unSesion, $conDB, $productos, $tmpListaCampo) {
		$tmpListVendedor = $unSesion->obtenerVariable ('listVendedor');
		$tmpListComprador = $unSesion->obtenerVariable ('listComprador');
		
		$htmlA = '<div id="myModalContrato" style="width:800px;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Contrato</h3>
    <p >';
	
		$htmlB = '</p>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    </div>
    </div>';
		
		$tabla = '<table width="600" border="0" cellspacing="0" class="table table-striped table-hover">
		<thead>
  <tr>
    <th>Campo</th>
	<th>Producto</th>
    <th>Vendedor</th>
    <th>Oferta</th>
    <th>Comprador</th>
	<th>Pro. demanda</th>
    <th>Demanda</th>
  </tr>
  </thead><tbody>';
  
		$tmpRondas = $unSesion->obtenerVariable('rondas');
		$tmpUltimaRonda = $tmpRondas[count($tmpRondas) - 1 ] ;
		// se recorren los campos
		foreach ($tmpListaCampo as $llaveCampo => $tmpCampo) {
			// SE RECORREN LOS PRODUCTOS
			foreach ($productos as $producto) {

					$campo = $tmpUltimaRonda[$producto]['campo'][$llaveCampo];
					
					
					if (isset ($campo['empresa']) && isset ($campo['comprador']) ) {
						$campo['empresa'] = $this->ordenarDeMayorAmenorOferta($campo['empresa']);
						$campo['comprador'] = $this->ordenarDeMayorAmenorDemanda($campo['comprador']);
						$distribucion = $this->darAsigancionesContrato($campo['empresa'], $campo['comprador']);
							
						foreach ($distribucion['oferta'] as $disOferta) {
							$sql = "select nombre from empresa where id = " . $disOferta['idEmpresa'];
							$res = $conDB->SQL($sql);
							$infoVendedor = mysql_fetch_object ($res) ;
							
							foreach ($distribucion['demanda'][$disOferta['idEmpresa']] as $disDemanda ) {
								$sql = "select nombre from comprador where id = " . $disDemanda['idComprador'];
								$res = $conDB->SQL($sql);
								$infoComprador = mysql_fetch_object ($res) ;
								
								$tabla .= '<tr><td>' .$tmpCampo . '</td>' ;
								
								$tabla .= '<td>' . $producto . '</td>' ;
	
								$tabla .= '<td>' . $infoVendedor->nombre . '</td>' ;
								$tabla .= '<td>' . $disOferta['oferta'] . '</td>' ;
	
	
								$tabla .= '<td>' . $infoComprador->nombre . '</td>' ;
								$tabla .= '<td>' . $disDemanda['tipProducto'] . '</td>' ;
								$tabla .= '<td>' . $disDemanda['demanda'] . '</td></tr>' ;
							}
							
							
						}
					}
			}
		}
		$tabla .= '</tbody></table>' ;			
		return $htmlA . $tabla . $htmlB;	
		}	
		
		
		function ordenarDeMayorAmenorOferta ($tmpArray) {
			$array = array();
			$arrayT = array();
			foreach ($tmpArray as $llave => $empresa) {
				$array[$llave] = $empresa['oferta'];
				}
			arsort ($array) ;
			foreach ($array as $llave => $orden) {
				$arrayT[] = $tmpArray[$llave] ;
				}
			return $arrayT;
			}


		function ordenarDeMayorAmenorDemanda ($tmpArray) {
			$array = array();
			$arrayT = array();
			foreach ($tmpArray as $llave => $comprador) {
				$array[$llave] = $comprador['demanda'];
				}
			arsort ($array) ;
			foreach ($array as $llave => $orden) {
				$arrayT[] = $tmpArray[$llave] ;
				}
			return $arrayT;
			}
			
		function darAsigancionesContrato ($ofertas, $demandas) {
			$arrayCampoOferta = array ();
			foreach ($ofertas as $llaveOferta => $oferta) {
				$arrayCampoOferta['oferta'][] = $oferta;
				foreach ($demandas as $llaveDemanda => $demanda) {
//					echo $oferta['oferta'] . " || " . $demanda['demanda'] . " || ";
					$resta = $oferta['oferta'] - $demanda['demanda'] ;
					// si la oferta es mayor que la demanda
					if ($resta > 0) {
						$oferta['oferta'] = $resta;
						$arrayCampoOferta['demanda'][$oferta['idEmpresa']][] = $demanda;
						unset ($demandas[$llaveDemanda]);
					}
					else {
						$demanda['demanda'] = $demanda['demanda'] - $oferta['oferta'] ;
						$demandas[$llaveDemanda] = $demanda;
						$arrayCampoOferta['demanda'][$oferta['idEmpresa']][] = $demanda ;
//						$demandas[$llaveDemanda] = $demandas[$llaveDemanda]['demanda'] - $oferta['oferta'] ;
						break ;
//						echo "Nelson " . $oferta['oferta'] . " - - - ";
					}
						
//					echo $resta . " <br /> " ;
					
				}
			}
			
//			echo "<pre>";
//			print_r ($arrayCampoOferta);
//			echo "</pre>";
			
 			return $arrayCampoOferta;	
		}

}