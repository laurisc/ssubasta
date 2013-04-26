<script src="js/general.js"></script>
<div class="row-fluid marketing">
	<h3>Paso 4 - Simulación</h3>
    <p class="alert">
    <!-- Button to trigger modal -->
    <a href="#myModal" role="button"  data-toggle="modal">Próxima ronda</a> | <?php echo $guardarEscenario; ?>
    <!-- Modal -->
    </p>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Incrementos próxima ronda</h3>
    </div>
    <div class="modal-body">
    <form id="form2" name="form2" method="post" action="">
      <table width="100%" border="0" cellspacing="0">
        <tr>
          <td>Tipo de incremento:</td>
          <td><!--<label for="radio">Porcentual</label>-->
            Porcentual&nbsp;&nbsp;&nbsp;<input type="radio" name="tipoincremento" id="radio" value="1" /></td>
          <td><!--<label for="radio2">Nominal</label>-->
            Nominal&nbsp;&nbsp;&nbsp;<input class="btn btn-primary" type="radio" name="tipoincremento" id="radio2" value="2" /></td>
          <td><!--<label for="radio3">Valor</label>-->
            Valor&nbsp;&nbsp;&nbsp;<input class="btn btn-primary" type="radio" name="tipoincremento" id="radio3" value="3" /></td>
        </tr>
        <tr>
          <td colspan="4">
                <table width="400" border="0" cellspacing="0">
				  <?php
                    foreach ($arrayExceso as $valor) {
						echo "<tr>";
						echo "<td>";
                        echo $tmpListaCampo[$valor] ;
						echo "</td>";
						echo "<td>";
						echo '<input class="input-small" type="text" name="textfield" id="textfield" />';
						echo "</td>";
                        }
                   ?>                
                  
                </table>          

          </td>
        </tr>
      </table>
    </form>
    </div>
    <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn">Guardar</button>
    </div>
    </div>  
    
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

    
    
    

  <form action="transporte.php" method="post" enctype="multipart/form-data" id="frm_iniesc">
		<table class="table table-striped table-hover">
			<?php echo $linea; ?>
			</tbody>
		</table>
		<hr>
        <h4>Oferta de gas para contratos firmes</h4>
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
					<th colspan=1> 1 año - CFC </th>
					<th colspan=1> 1 año - OCG </th>
					<th colspan=1> 5 años - Firme </th>
					<th colspan=1> 5 años - CFC </th>
					<th colspan=1> 5 años - OCG </th>
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
					<th colspan=1> 1 año - CFC </th>
					<th colspan=1> 1 año - OCG </th>
					<th colspan=1> 5 años - Firme </th>
					<th colspan=1> 5 años - CFC </th>
					<th colspan=1> 5 años - OCG </th>
				</tr>
            </thead>
			<tbody>
            	<?php echo $rondasCorridas ; ?>
            </tbody>            
        </table>                                       
		<a class="btn" onclick="href='transporte.php'">atrás</a>
        </form>
      </div>