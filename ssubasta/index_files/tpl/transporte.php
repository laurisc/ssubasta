<div class="row-fluid marketing">
		<h3>Paso 4 - Transporte</h3>
		<hr>
        <form action="simulacion.php" method="post" enctype="multipart/form-data" id="frm_iniesc" onsubmit="return revisar()">
		<table class="table table-striped table-hover">
				<?php echo $linea; ?>
			</tbody>
		</table>
		<hr>
		<a class="btn" onclick="href='completarCompradores.php'">atrás</a>
		<input class="btn" type="submit" name="button" id="button" value="siguiente" />
        </form>
      </div>
	    <script type="text/javascript" src="./js/registro.js"></script>
	    <script type="text/javascript" src="./js/funcionesjavascript.js"></script>
	    <script type="text/javascript" src="./js/funcionesRevisarDatos.js"></script>
	    <script type="text/javascript" src="./js/funcionesDeRevisionPasoAPaso.js"></script>
