<div class="row-fluid marketing">
  <h3>Comprador</h3>
		<hr>
		<a href="javascript:;" onclick="document.getElementById('frm_nuevovendedor').style.display='block'">+ Agregar</a>
		<form action="" method="post" enctype="multipart/form-data" name="frm_nuevovendedor" id="frm_nuevovendedor" style="display:none;">
		  <table width="600" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td>Comprador:</td>
		      <td><label for="comprador"></label>
	          <input type="text" name="comprador" id="comprador" /></td>
	        </tr>
		    <tr>
		      <td>Localización:</td>
		      <td><label for="ciudad"></label>
		        <select name="ciudad" id="ciudad">
                 <?php echo $selectCiudad; ?>
              </select></td>
	        </tr>
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
					<th> Comprador </th>
					<th> Localización </th>
                    <th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php echo $tmpComprador; ?>
			</tbody>
		</table>        
	<a class="btn" onclick="href='producto.php'">atrás</a>
        
    <input  class="btn" type="submit" name="guardar" id="guardar" value="Guardar" />
        </form>
      </div>