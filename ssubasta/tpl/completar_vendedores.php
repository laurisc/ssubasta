<script src="js/completarVenderores.js"></script>
<script src="./js/funcionesDeRevisionPasoAPaso.js"></script>
<script src="./js/funcionesjavascript.js"></script>
<script src="./js/funcionesRevisarDatos.js"></script>
<div class="row-fluid marketing">
		<h3>Paso 2 - Completar vendedores</h3>
		<hr>
        <form action="completarCompradores.php" method="post" enctype="multipart/form-data" id="frm_iniesc" onsubmit="return revisarInformacionCorrectaVendedores()">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>&nbsp;  </th>
					<th>&nbsp; </th>
					<th>&nbsp;  </th>
					<th>&nbsp; </th>
					<th>&nbsp;  </th>
					<th colspan=3 align="center" > 1 año </th>
					<th colspan=3 align="center" > 5 años </th>
					<th>&nbsp; </th>
				</tr>
				<tr>
					<th> ID </th>
					<th> Empresa </th>
					<th> Fuente de suministro </th>
					<th> PTDVF/ICDVF </th>
					<th>&nbsp;  </th>
					<th> Firme </th>
					<th> CFC </th>
					<th> OCG </th>
					<th> Firme </th>
					<th> CFC </th>
					<th> OCG </th>
				</tr>
			</thead>
			<tbody>
			<?php echo $linea; ?>
			</tbody>
		</table>
		<hr>
		<a class="btn" onclick="href='iniciarEscenario.php'">atrás</a>
		<input class="btn" type="submit" name="button" id="button" value="siguiente" />
        </form>
      </div>
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
	<script type="text/javascript">
		$(function () {
			$("[rel='tooltip']").tooltip();
		});
	</script>