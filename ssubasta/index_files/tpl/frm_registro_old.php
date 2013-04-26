
    <div class="container-narrow">
      <div>
      <h2 class="muted">SSubasta - Registro de Usuario</h2>
      </div>

      <hr>

	<form ACTION="" METHOD="post" enctype="multipart/form-data" id="frm_registro">
    <table width="600" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><?php echo (isset ($msjErrorRegistro)) ? $msjErrorRegistro : ''; ?></td>
      </tr>
      <tr>
      <td>Tipo documento</td><td><label for="tipoDocumento"></label>
        <select name="tipoDocumento" id="tipoDocumento">
          <option value="1">C.C</option>
          <option value="2">NIT</option>
        </select></td></tr>
      <tr>
        <td>Documento</td>
        <td><label>
		<input name="documento" type="text" id="documento"> </label></td>
      </tr>
      <tr>
        <td>Razón Social</td>
        <td><label>
		<input name="razonSocial" type="text" id="razonSocial"> </label></td>
      </tr>
      <tr>
        <td>Representante Legal</td>
        <td><label>
		<input name="representanteLegal" type="text" id="representanteLegal"> </label></td>
      </tr>
      <tr>
        <td>Actividad Ecónomica</td>
        <td><label>
	  <input name="actividadEconomica" type="text" id="actividadEconomica"> </label></td>
      </tr>
      <tr>
        <td>Dirección</td>
        <td><label>
		<input name="direccion" type="text" id="direccion"> </label></td>
      </tr>
      <tr>
        <td>Ciudad	</td>
        <td><label for="ciudad"></label>
          <select name="ciudad" id="ciudad">
            <?php echo $selectCiudad; ?>
        </select></td>
      </tr>
      <tr>
        <td>Télefono 1</td>
        <td><label>
          <input name="telefonoUno" type="text" id="telefonoUno"> 
</label></td>
      </tr>
      <tr>
        <td>Télefono 2</td>
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

  
