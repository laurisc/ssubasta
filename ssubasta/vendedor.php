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

	# se inician las variables de sesion, usuario y base de datos 
	$conDB = new db ;
	$conDB->conectarDB ();
	$conDB->seleccionDB ();	
		
	$unSesion = new sesion ('sSubasta');
	$unSesion->redirecInicioSesion ('../index.php') ;
	
	$sql = "SELECT * FROM `campo` order by `nombre`";
	$res = $conDB->SQL($sql);
	$optionCampo = '';
	while ($row = mysql_fetch_object ($res)) {
		$optionCampo .=  '<option value="' . $row->id .'" >' . $row->nombre .'</option>'; 
		}
	
	
	# si se envia peticion boton guardar nuevo vendedor
	if (isset ($_POST['guardar2']) && $_POST['guardar2'] == 'Guardar') {
		$sql = "INSERT INTO empresa (nombre, padre) VALUES ('" . trim ($_POST['empresa']) . "', " . $unSesion->obtenerVariable('id') . ")";
		$conDB->SQL ($sql);
		$tmpId = mysql_insert_id();
		if ($tmpId >= 1) {
			$sql = "INSERT INTO campo_empresa (campo, empresa) VALUES (" . $_POST['campo'] . ", " . $tmpId . ")";
			$conDB->SQL ($sql);
//			echo $sql;
			}
		}


	# si se envia peticion boton guardar cambios
	if (isset ($_POST['guardar']) && $_POST['guardar'] == 'Guardar') {
		if (isset ($_POST['ch_vendedor']))
			foreach ($_POST['ch_vendedor'] as $llave => $valor) {
				$tmpLlave = explode ("|", $valor);
				$sql = "delete from empresa where id  = " .  $tmpLlave[1];
				$conDB->SQL ($sql);
				$sql = "delete from campo_empresa where campo  = " .  $tmpLlave[0] . " and empresa = " . $tmpLlave[1] ;
				$conDB->SQL ($sql);
				}

		}
	
//	$sql = "SELECT campo.id as cid, campo.nombre as cn, empresa.id as eid, empresa.nombre as en, ptdvf, empresa.padre as epadre FROM `campo`, `empresa`, `campo_empresa` WHERE campo.id = campo and empresa.id = empresa and padre in (1, " . $unSesion->obtenerVariable('id') . ")";
	$sql = "SELECT campo.id as cid, campo.nombre as cn, empresa.id as eid, empresa.nombre as en, empresa.padre as epadre FROM `campo`, `empresa`, `campo_empresa` WHERE campo.id = campo and empresa.id = empresa and padre in (184, " . $unSesion->obtenerVariable('id') . ")";
	$res = $conDB->SQL($sql);
//	echo $sql;
	$contador = 1;
	$tmpVendedores = '';
	
//			$tmpVendedores .= '<td><input type="checkbox" onclick="cambiaClaseborrarCh (this)" class="ch_borrar" name="ch_vendedor[]" id="ch_vendedor' . $contador . '" value="' . $row->cid . '|' . $row->eid . '" /> <label for="ch_vendedor' . $contador . '"> </label> </td>';	
	
	while ($row = mysql_fetch_object ($res)) {
		$tmpVendedores .= '<tr>';
		$tmpVendedores .= '<td> ' . $contador . ' </td>';
		$tmpVendedores .= '<td> ' . $row->cn . ' </td>';
		$tmpVendedores .= '<td> ' . $row->en . ' </td>';
		if ($unSesion->obtenerVariable('id') == $row->epadre)
			$tmpVendedores .= '<td>Borrar <input type="checkbox"  name="ch_vendedor[]" id="ch_vendedor' . $contador . '" value="' . $row->cid . '|' . $row->eid . '" /> <label for="ch_vendedor' . $contador . '">  </label></td>';
		else
			$tmpVendedores .= '<td> </td>';	
//		$tmpVendedores .= '<td> <input class="input-small" type="text" name="ptdvf|' . $row->cid . '|' . $row->eid . '" id="ptdvf|' . $row->cid . '|' . $row->eid . '" value="' . $row->ptdvf . '" /> </td>';
//		$tmpVendedores .= '<td> 000 </td>';
		$tmpVendedores .= '</tr>';
		$contador++;
		}	
	
	
	include BASETPL . 'cabezote.php';
	include BASETPL . 'menu.php';
	
	include BASETPL . 'list_vendedor.php';
			
	include BASETPL . 'pie.php';
	
	$conDB->desconectar ();
/*	echo "<pre>";
	print_r ($_POST);	
	echo "</pre>";  */
?>