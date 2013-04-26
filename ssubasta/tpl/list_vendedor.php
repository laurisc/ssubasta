<div class="row-fluid marketing">
  <h3>Vendedor</h3>
		<hr>
		<a href="javascript:;" onclick="document.getElementById('frm_nuevovendedor').style.display='block'">+ Agregar</a>
		<form action="" method="post" enctype="multipart/form-data" name="frm_nuevovendedor" id="frm_nuevovendedor" style="display:none;">
		  <table width="600" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td>Nombre empresa:</td>
		      <td><label for="empresa"></label>
	          <input type="text" name="empresa" id="empresa" /></td>
	        </tr>
		    <tr>
		      <td>Campo</td>
		      <td><label for="campo"></label>
		        <select name="campo" id="campo">
                <?php echo $optionCampo; ?>
              </select></td>
	        </tr>
		    <!-- <tr>
		      <td>PTDVF</td>
		      <td><input type="text" name="ptdvf" id="ptdvf" class="input-small"/></td>
	        </tr> -->
		    <tr>
		      <td><a class="btn" href="javascript:;" onclick="document.getElementById('frm_nuevovendedor').style.display='none'">Cancelar</a></td>
		      <td><input type="submit" name="guardar2" id="guardar2" value="Guardar" class="btn" /></td>
	        </tr>
	      </table>
  </form>
		<hr>
  <form method="post" enctype="multipart/form-data" id="frm_vendedor">
<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th> ID </th>
					<th> Campo </th>
					<th> Empresa </th>
                    <th>&nbsp;  </th>
                   <!-- <th> PTDVF </th> -->
				</tr>
			</thead>
			<tbody>
			<?php echo $tmpVendedores; ?>
			</tbody>
		</table>        
	<a class="btn" onclick="href='producto.php'">atrás</a>
        
    <input  class="btn" type="submit" name="guardar" id="guardar" value="Guardar" />
        </form>
      </div>