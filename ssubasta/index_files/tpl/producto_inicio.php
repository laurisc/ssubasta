<div class="row-fluid marketing">
		<h3>Página inicial</h3>
		<hr>
		<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		<a class="btn" onclick="href='verEscenarios.php'">Ver escenarios</a>
		<hr>
        <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>
		<a class="btn" onclick="javascrip:iniciaEscenario();">Iniciar escenario</a>
      </div>
      <script>
      	function iniciaEscenario () {
			if (confirm("Aceptar. Nuevo escenario\nCancelar. Cargar escenario previo"))
				document.location.href ="iniciarEscenario.php?nuevo=true";
			else
				document.location.href ="iniciarEscenario.php";
			}
      </script>