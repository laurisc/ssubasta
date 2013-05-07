<script src="js/resumenescenario.js"></script>
<script src="./js/funcionesDeRevisionPasoAPaso.js"></script>
<div class="row-fluid marketing">
<form action="transporte.php" method="post" enctype="multipart/form-data" id="frm_iniesc" onsubmit="return revisarInformacionCorrectaCompradores()">
		<h3>Paso 3 - Ingresar compradores</h3>
		<hr>
		<table class="table table-striped table-hover" name="compradores" id="compradores">
			<col width="2%">
			<col width="2%">
			<col width="17%">
			<col width="10%">
			<col width="10%">
			<col width="20%">
			<col width="3%">
			<col width="3%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<thead>
				<tr>
					<th>&nbsp;  </th>
					<th>&nbsp; </th>
					<th>&nbsp;  </th>
					<th>&nbsp; </th>
					<th>&nbsp;  </th>
					<th>&nbsp;  </th>
					<th>&nbsp; </th>
					<th>&nbsp;  </th>
					<th colspan=3 align="center" > 1 año </th>
					<th colspan=3 align="center" > 5 años </th>
					<th>&nbsp; </th>
				</tr>
				<tr>
					<th> ID </th>
                    <th> &nbsp </th>
					<th> Nombre </th>
                    <th> Tipo * </th>
					<th> Demanda a suplir </th>
					<th> Ciudad </th>
                    <th> &nbsp </th>
                    <th> &nbsp </th>
                    <th> Firme  </th>
                    <th> CFC </th>
                    <th> OCG </th>
                    <th> Firme </th>
                    <th> CFC </th>
                    <th> OCG </th>
					
				</tr>
			</thead>
			<tbody>
				<?php echo $tmpComprador; ?>
			</tbody>
		</table>
		<label> * Existen dos tipos de compradores. El tipo A, que puede comprar Firme y Firme Condicional (CFC). El tipo B, que puede comprar Firme y Opcion de Compra (OCG)</label>
		<hr>
		<h4>Resumen</h4>
		<table  width=200>
			<thead>
				<tr>
					<th> Vendedores </th>
					<td name="tVendedores" id="tVendedores"><?php echo $tVendedores; ?></td>
				</tr>
			</thead>
			<thead>
				<tr>
					<th> Compradores </th>
					<td name="tCompradores" id="tCompradores"><?php echo $tCompradores; ?>&nbsp;</td>
				</tr>
			</thead>
			<thead>
				<tr>
					<th> Campos </th>
					<td name="tCampos" id="tCampos"><?php echo $tCampos; ?></td>
				</tr>
			</thead>
		</table>
		<hr>
		<a class="btn" onclick="href='completarVendedores.php'">atr&aacute;s</a>
		<input class="btn" type="submit" name="siguiente" id="siguiente" value="siguiente" />
	</form>
    </div>
	<script src="./js/resumenEscenario ()"></script>
	<script type="text/javascript" src="./js/funcionesjavascript.js"></script>
	<script type="text/javascript" src="./js/funcionesRevisarDatos.js"></script>
	<script src="./js/completarCompradores.js"></script>