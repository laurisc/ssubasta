<?php $archivoEjecucion =  basename($_SERVER['PHP_SELF']);  ?>     
      <div class="masthead">
        <ul class="nav nav-tabs">
          <li <?php echo ($archivoEjecucion == 'index.php') ? 'class="active"' : ''; ?>><a href="index.php">Inicio</a></li>
          <li <?php echo ($archivoEjecucion == 'producto.php') ? 'class="active"' : ''; ?>><a href="producto.php">Producto</a></li>
          <?php if ($unSesion->obtenerVariable('usuario')) : ?>
	          <li <?php switch ($archivoEjecucion) {
//			  		case 'producto.php':
					case 'subasta.php':
					case 'verEscenarios.php':
					case 'iniciarEscenario.php':
					case 'completarVendedores.php':
					case 'transporte.php':
					case 'simulacion.php':
						echo 'class="active"';
						break;		
				    default:
				       echo ' ';
				}
			  ?> ><a href="subasta.php">Subasta</a></li>
	          <li <?php echo ($archivoEjecucion == 'vendedor.php') ? 'class="active"' : ''; ?>><a href="vendedor.php">Vendedor</a></li>
	          <li <?php echo ($archivoEjecucion == 'comprador.php') ? 'class="active"' : ''; ?>><a href="comprador.php">Comprador</a></li>
          <?php endif ; ?>
        </ul>
      </div>