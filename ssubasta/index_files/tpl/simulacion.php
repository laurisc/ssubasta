<script src="js/general.js"></script>
<script src="js/proximaRonda.js"></script>
<div class="row-fluid marketing">
	<h3>Paso 4 - Simulación</h3>
    <p class="alert">
    <!-- Button to trigger modal -->
    <?php if (isset($contrato)) : ?>
    <a href="#myModalContrato" role="button"  data-toggle="modal">Ver Contrato</a>
    <?php else : ?>
    <a href="#myModal" role="button"  data-toggle="modal">Próxima ronda</a>
    <?php endif; ?>
    | <?php echo $guardarEscenario; ?> | <a href="#modalBorrar" role="button"  data-toggle="modal"> Borrar última ronda</a>    <!-- Modal -->
    </p>
	<!--NUEVO CODIGO-->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Incrementos próxima ronda</h3>
    <p >A continuación se presentan solamente las fuentes de suministros que tiene sobre oferta. </p>
    </div>
    <div class="modal-body">
	<form id="form2" name="form2" method="post" action="simulacion.php">
    <input type="hidden" name="correrRonda" id="correrRonda" value="<?php  echo (($unSesion->obtenerVariable ('numeroRonda')) + 1)?>"/>
		<table width="100%" border="0" cellspacing="0">
		<tr>
			<td>
			<table width="500" border="0" cellspacing="0" name="incrementos" id="incrementos">
			<?php
				$fila = 0 ;
//				echo $rondas ;
				foreach ($productos as $idItemProducto => $itemProducto) {
					$rondas = $unSesion->obtenerVariable ('rondas') ;
					foreach ($rondas[$ronda][$itemProducto]['campo'] as $itemCampo ) {
						if ($itemCampo['exceso']) {
/*							echo "<pre>" . $itemProducto;
							print_r ($tmpListaCampo[$itemCampo['idCampo']]) ;
							echo "</pre>";  */
							echo "<tr>";
							echo "<td>";
							echo $tmpListaCampo[$itemCampo['idCampo']] . " " . $itemProducto;
							echo "</td>";
							echo "<td>";
							echo '<input name="campoProductoProximaRonda[]" type="hidden" id="campoProductoProximaRonda" value="' . $itemProducto.'|'.$itemCampo['idCampo'] . '">';
							echo '<input name="tipoProducto[]" type="hidden" id="tipoProducto' . $fila . '" />
									 <select class="combobox, input-medium" id="tipoincremento' . $fila . '" name="tipoincremento[]" onchange="noValorEnOptimo(' . $fila . ')"> 
											<option value="1" selected="true" >Porcentual</option>
											<option value="2">Nominal</option>
											<option value="3">Nuevo valor</option>
											<option value="4">Valor óptimo</option>';
							echo "</td>";
							echo "<td>";
							echo '<input class="input-small" type="text" name="fila[]" id="fila' . $fila . '" />';
							echo "</td>";							
							echo "</tr>";
							$fila++;
							}
						}
					} 
/*				echo "<pre>";
					print_r ($arrayExceso);
				echo "</pre>"; */
/*				foreach ($arrayExceso as $llave => $valor) {
					for($i = 2; $i<=7; $i++) {
						if(isset($valor[$i])) {
							echo "<tr>";
								echo "<td>";
									echo $tmpListaCampo[$valor[1]] . " - " . $valor[$i];
								echo "</td>";
								echo '<td> <input name="numeroOptimoCorrido[]" type="hidden" id="numeroOptimoCorrido" value="';
								echo ($unSesion->obtenerVariable ( $valor[1].$valor[$i] ) ) ? $unSesion->obtenerVariable ( $valor[1].$valor[$i] )  : 0 ;
								echo '">';
								echo '
								<input name="idCampoRonda[]" type="hidden" id="idCampoRonda" value="' . $valor[1] . '" />
											<input name="tipoProducto[]" type="hidden" id="tipoProducto" value="' . $valor[$i] . '" />
									 <select class="combobox, input-medium" id="tipoincremento' . $fila . '" name="tipoincremento[]" onchange="cambiarPorTipoDeIncremento(this,' . $fila . ')"> 
											<option value="1" selected="true" >Porcentual</option>
											<option value="2">Nominal</option>
											<option value="3">Nuevo valor</option>';	
								echo ($unSesion->obtenerVariable ( $valor[1].$valor[$i] ) >= 3) ? '' : '<option value="4">Valor con óptimo</option>';
								echo		'</select> </td>';
								echo "<td>";
									echo '<input class="input-small" type="text" name="fila[]" id="fila' . $fila . '" />';
								echo "</td>";
							echo "</tr>";
							$fila++;
						}
					}
                } */
            ?>
            </table>
          </td>
        </tr>
      </table>
    </form>
    </div>
    <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn" onclick="document.getElementById('form2').submit();">Guardar</button>
    </div>
    </div>  
	<!--FIN CODIGO NUEVO-->

	<!-- si ya se terminaron las rondas muestra el contrato -->
    <?php  echo (isset($contrato)) ? $contrato : '';?>    
	
	<!--Este es el popup para guardar el escenario-->
    <div id="modalGuardar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Incrementos próxima ronda</h3>
		</div>
		<div class="modal-body">
			<form id="form1" name="form1" method="post" action="guardar.php">
			<?php
			if (!$unSesion->obtenerVariable ('idEscenario'))
				include BASETPL . 'guardar.php';
			else 
				include BASETPL . 'guardarDos.php';
			?>
			
			</form> 
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
			<button class="btn" onclick="document.getElementById('form1').submit();">Guardar</button>
		</div>
    </div>  


	<!--Este es el popup para borrar ultimo registro-->
    <div id="modalBorrar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Borrar Ronda</h3>
		</div>
		<div class="modal-body">
			<form id="formBorrar" name="formBorrar" method="post" action="borrarRonda.php">
				¿Realmente desea borrar la última ronda corrida?
			</form> 
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
			<button class="btn" onclick="document.getElementById('formBorrar').submit();">Borrar</button>
		</div>
    </div>  
    
    
    

  <form action="transporte.php" method="post" enctype="multipart/form-data" id="frm_iniesc">
		<table class="table table-striped table-hover">
			<?php echo $linea; ?>
			</tbody>
		</table>
		<hr>
        <h4>Oferta de gas</h4>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th> </th>
                    <th> 1 año - Firme </th>
					<th> 1 año - CFC </th>
					<th> 1 año - OCG </th>
					<th> 5 años - fijo </th>
					<th> 5 años - CFC </th>
					<th> 5 años - OCG </th>
				</tr>
            </thead>
			<tbody>
            	<?php echo $ofertaContratoFirme ; ?>
            </tbody>            
        </table> 
		<hr>
		<h4>Oferta de gas para regla de exceso de oferta</h4>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th colspan=1> 1 año - Firme </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 1 año - CFC </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
					<th colspan=1> 1 año - OCG </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 5 años - Firme </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 5 años - CFC </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
					<th colspan=1> 5 años - OCG </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
				</tr>
            </thead>
			<tbody>
            	<?php echo $ofertaReglaExceso ; ?>
            </tbody>            
        </table>
        <hr />
        <h4> Ronda </h4> 
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Ronda</th>
                    <th>&nbsp;</th>
					<th colspan=1> 1 año - Firme </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 1 año - CFC </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
					<th colspan=1> 1 año - OCG </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 5 años - Firme </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>
					<th colspan=1> 5 años - CFC </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
					<th colspan=1> 5 años - OCG </th>
					<th colspan=1> Precio </th>
                    <th colspan=1> Demanda </th>                    
				</tr>
            </thead>
			<tbody>
            	<?php echo $rondasCorridas ; ?>
            </tbody>            
        </table>
        <?php if ($ronda==0) { echo '<a class="btn" onclick="href=\'transporte.php\'">atrás</a>';} ?>
                               
		</form>
      </div>