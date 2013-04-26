<?php
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	# variables estaticas para incluir librerias y plantillas
	define ('BASELIB', 'lib/');
	define ('BASETPL', 'tpl/');
	
	$nuevoRegistro = false;
	
	# se incluye las  librerias de inicio de sesion y conexion a la base de datos
	require_once ( BASELIB . 'class.db.php' );
	require_once ( BASELIB . 'class.sesion.php');
	require_once ( BASELIB . 'class.usuario.php');
	require_once ( BASELIB . 'class.ciudad.php');

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	$unSesion->redirecInicioSesion ('../index.php') ;
	
	$sql = "SELECT * FROM `ciudad` order by `ciudad`";
	$res = $conDB->SQL($sql);
	while ($row = mysql_fetch_object ($res)) {
		$tmpArrayCiudad[$row->id] = $row->ciudad; 
		}
	
	$unCiudad = new ciudad ($tmpArrayCiudad);
	$selectCiudad = $unCiudad->creaOptionSelect (0);
	
	
	# si se envia peticion boton guardar nuevo comprador
	if (isset ($_POST['guardar2']) && $_POST['guardar2'] == 'Guardar') {
		$sql = "INSERT INTO comprador (nombre, id_ciudad, padre) VALUES ('" . trim ($_POST['comprador']) . "', " . $_POST['ciudad'] . ", " . $unSesion->obtenerVariable('id') . ")";
		$conDB->SQL ($sql);
		}



	# si se envia peticion boton guardar cambios
	if (isset ($_POST['guardar']) && $_POST['guardar'] == 'Guardar') {
		if (isset ($_POST['ch_comprador']) )
			foreach ($_POST['ch_comprador'] as $idBorrar ) {
				$sql = "delete from comprador where id = " . $idBorrar;
				$conDB->SQL ($sql);
				}
		}
	
	$sql = "SELECT comprador.*, ciudad FROM `comprador`, `ciudad` WHERE  id_ciudad = ciudad.id and comprador.padre in (184 , " . $unSesion->obtenerVariable('id') . ")";
	$res = $conDB->SQL($sql);
	$contador = 1;
	$tmpComprador = '';
	while ($row = mysql_fetch_object ($res)) {
		$tmpComprador .= '<tr>';
		$tmpComprador .= '<td> ' . $contador . ' </td>';
		$tmpComprador .= '<td> ' . $row->nombre . ' </td>';
		$tmpComprador .= '<td> ' . $row->ciudad . ' </td>';
		if ($unSesion->obtenerVariable('id') == $row->padre)
			$tmpComprador .= '<td>Borrar <input type="checkbox"  name="ch_comprador[]" id="ch_comprador' . $contador . '" value="' . $row->id . '" /><label for="ch_comprador' . $contador . '"></label> </td>';
		else
			$tmpComprador .= '<td> </td>';		
		$tmpComprador .= '</tr>';
		$contador++;
		}	
	
//			$tmpComprador .= '<td> <input type="checkbox" onclick="cambiaClaseborrarCh (this)" name="ch_comprador[]" class="ch_borrar" id="ch_comprador' . $contador . '" value="' . $row->id . '" /><label for="ch_comprador' . $contador . '"></label> </td>';	
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	include BASETPL . 'list_comprador.php';
	include BASETPL . 'pie.php';
	$conDB->desconectar ();
/*	echo "<pre>";
	print_r ($_SESSION);	
	echo "</pre>"; */  
?>