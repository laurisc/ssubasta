
    <div class="container-narrow">
      <div>
      <h2 class="muted">SSubasta - Registro de Usuario</h2>
      </div>

      <hr>

	<form ACTION="" METHOD="post" enctype="multipart/form-data" id="frm_registro" name="frm_registro">
    <table width="600" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><?php echo (isset ($msjErrorRegistro)) ? $msjErrorRegistro : ''; ?></td>
      </tr>
	  <tr>
		<small><b> Los campos marcados con  un * son obligatorios </b></small>
	  </tr>
	  <p> &nbsp; </p>
      <tr>
		<td>Tipo documento *</td>
		<td><label for="tipoDocumento"></label>
			<select name="tipoDocumento" id="tipoDocumento" onchange="desactivarRepresentanteLegal()">
				<option value="1" >C.C</option>
				<option value="2" selected>NIT</option>
			</select>
		</td>
	  </tr>
      <tr>
        <td>Número de documento *</td>
        <td><label>
		<input name="documento" type="text" id="documento"> </label></td>
      </tr>
      <tr>
        <td>Razón social *</td>
        <td><label>
		<input name="razonSocial" type="text" id="razonSocial"> </label></td>
      </tr>
      <tr>
        <td>Representante Legal</td>
        <td><label>
		<input name="representanteLegal" type="text" id="representanteLegal"> </label></td>
      </tr>
      <tr>
        <td>Actividad Ecónomica * <a href="http://www.dian.gov.co/descargas/normatividad/Resolucion_00432_Actividades_Economicas_2008.pdf" target="_blank" rel="tooltip" class="icon-question-sign" data-original-title="La actividad económica es dada por la resolución 00432 de la DIAN. Haz click en el símbolo de interrogación para ir."></a></td>
        <td><label>
	  <input name="actividadEconomica" type="text" id="actividadEconomica"> </label></td>
      </tr>
      <tr>
        <td>Dirección *</td>
        <td><label>
		<input name="direccion" type="text" id="direccion"> </label></td>
      </tr>
      <tr>
        <td>Ciudad	*</td>
        <td><label for="ciudad"></label>
          <select name="ciudad" id="ciudad">
            <?php echo $selectCiudad; ?>
        </select></td>
      </tr>
      <tr>
        <td>Télefono 1 *</td>
        <td><label>
          <input name="telefonoUno" type="text" id="telefonoUno"> 
</label></td>
      </tr>
      <tr>
        <td>Télefono 2 </td>
        <td><label>
		<input name="telefonoDos" type="text" id="telefonoDos"> </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="button" id="button" value="Guardar" class="btn" /></td>
        <td><a href=index.php class="btn" ><i class="icon-remove"></i>Cancelar</a></td>
      </tr>
    </table>
    <br>
		
		
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
	<script language="JavaScript">
		function desactivarRepresentanteLegal()
		{			
			var indice = document.frm_registro.tipoDocumento.selectedIndex;
			var valor = document.frm_registro.tipoDocumento.options[indice].value;
			if(valor==1)
			{
				document.frm_registro.representanteLegal.disabled = true;
			}
			else if(valor==2)
			{
				document.frm_registro.representanteLegal.disabled = false;
			}
		}
	</script>
	<script type="text/javascript">
		$(function () {
			$("[rel='tooltip']").tooltip();
		});
	</script>