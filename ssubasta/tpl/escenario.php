<script src="js/resumenescenario.js"></script>
<div class="row-fluid marketing">
	<script type="text/javascript" src="./js/funcionesDeRevisionPasoAPaso.js"></script>
<form action="completarVendedores.php" method="post" enctype="multipart/form-data" id="frm_iniesc" onsubmit="return revisarHayVendedorEscogido()">
		<h3>Paso 1 - Escoger vendedores</h3>
		<hr>
		<table class="table table-striped table-hover" name="vendedores" id="vendedores">
			<thead>
				<tr>
					<th> ID </th>
					<th> Fuente de suministro </th>
					<th> Empresa </th>
                    <th>  </th>
				</tr>
			</thead>
			<tbody>
				<?php echo $tmpVendedores; ?>
			</tbody>
			<input type="hidden" name="tVendedoresH" id="tVendedoresH" value="nuevo" />
			<input type="hidden" name="tCompradoresH" id="tCompradoresH" value="0" >
			<input type="hidden" name="tCamposH" id="tCamposH" value="nuevo" />
		</table>
		<hr>
		<hr>
		<h4>Resumen</h4>
		<div  class="bs-docs-grid" >
			<div  class="row-fluid show-grid" >
				<div class="span2"> Vendedores </div>
				<div class="span1" name="tVendedores" id="tVendedores">&nbsp;
				</div>
			</div>
			<div  class="row-fluid show-grid" >
				<div class="span2"> Compradores </div>
				<div class="span1" name="tCompradores" id="tCompradores"> <?php echo $tCompradores ?> 
				</div>
			</div>
			<div  class="row-fluid show-grid" >
				<div class="span2"> Campos </div>
				<div class="span1" name="tCampos" id="tCampos">&nbsp;
				</div>
			</div>
		</div>
		<hr>
		<a class="btn" onclick="href='subasta.php'">atrás</a>
		<input class="btn" type="submit" name="siguiente" id="siguiente" value="siguiente" />
	</form>
    </div>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./index_files/jquery.js"></script>
    <script src="./index_files/bootstrap-transition.js"></script>
    <script src="./index_files/bootstrap-alert.js"></script>
    <script src="./index_files/bootstrap-modal.js"></script>
    <script src="./index_files/bootstrap-dropdown.js"></script>
    <script src="./index_files/bootstrap-scrollspy.js"></script>
    <script src="./index_files/bootstrap-tab.js"></script>
    <script src="./index_files/bootstrap-tooltip.js"></script>
    <script src="./index_files/bootstrap-popover.js"></script>
    <script src="./index_files/bootstrap-button.js"></script>
    <script src="./index_files/bootstrap-collapse.js"></script>
    <script src="./index_files/bootstrap-carousel.js"></script>
    <script src="./index_files/bootstrap-typeahead.js"></script>
	<script type="text/javascript" src="./js/funcionesjavascript.js"></script>
	<script type="text/javascript" src="./js/registro.js"></script>
	<script type="text/javascript">
		$(function () {
			$("[rel='tooltip']").tooltip();
		});
	</script>
    <script>resumenVendedor();</script>
