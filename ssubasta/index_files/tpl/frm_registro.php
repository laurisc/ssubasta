
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
		<input name="documento" type="text" id="documento" onchange="esCorrectoNit()"> </label></td>
      </tr>
      <tr>
        <td>Razón social *</td>
        <td><label>
		<input name="razonSocial" type="text" id="razonSocial" onchange="esCorrectoAlfa()"> </label></td>
      </tr>
      <tr>
        <td>Representante Legal</td>
        <td><label>
		<input name="representanteLegal" type="text" id="representanteLegal" onchange="esCorrectoAlfa()"> </label></td>
      </tr>
      <tr>
        <td>Actividad Ecónomica * <a href="http://www.dian.gov.co/descargas/normatividad/Resolucion_00432_Actividades_Economicas_2008.pdf" target="_blank" rel="tooltip" class="icon-question-sign" data-original-title="La actividad económica es dada por la resolución 00432 de la DIAN. Haz click en el símbolo de interrogación para ir."></a></td>
        <td><label>
	  <input name="actividadEconomica" type="text" id="actividadEconomica" onchange="esCorrectoAlfa()"> </label></td>
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
          <input name="telefonoUno" type="text" id="telefonoUno" onchange="esCorrectoDigitos()"> 
</label></td>
      </tr>
      <tr>
        <td>Télefono 2 </td>
        <td><label>
		<input name="telefonoDos" type="text" id="telefonoDos" onchange="esCorrectoDigitos()"> </label></td>
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
	<script src="./js/funcionesjavascript.js"></script>
	<script src="./js/funcionesRevisarDatos.js"></script>
