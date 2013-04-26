<?php
$idGrupoActualizar = (isset($_GET['verUsuario'])) ? $_GET['verUsuario'] : false;
?>
    <link type="text/css" rel="stylesheet" href="src/css/jscal2.css" />
    <link type="text/css" rel="stylesheet" href="src/css/border-radius.css" />
    <!-- <link type="text/css" rel="stylesheet" href="src/css/reduce-spacing.css" /> -->

    <link id="skin-win2k" title="Win 2K" type="text/css" rel="alternate stylesheet" href="src/css/win2k/win2k.css" />
    <link id="skin-steel" title="Steel" type="text/css" rel="alternate stylesheet" href="src/css/steel/steel.css" />
    <link id="skin-gold" title="Gold" type="text/css" rel="alternate stylesheet" href="src/css/gold/gold.css" />
    <link id="skin-matrix" title="Matrix" type="text/css" rel="alternate stylesheet" href="src/css/matrix/matrix.css" />

    <link id="skinhelper-compact" type="text/css" rel="alternate stylesheet" href="src/css/reduce-spacing.css" />

    <script src="src/js/jscal2.js"></script>
    <script src="src/js/unicode-letter.js"></script>

    <!-- this must stay last so that English is the default one -->
    <script src="src/js/lang/es.js"></script>

<form id="frmUsuarios" name="frmUsuarios" method="post" action="../diee/?verUsuario=">
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="18%">Login Uniandes:</td>
      <td width="20%"><label>
        <input name="loginUsuario" type="text" id="loginUsuario" value="<?php echo (isset($_POST['loginUsuario'])) ? $_POST['loginUsuario'] : ''; ?>" size="30" />
      </label></td>
      <td width="4%">&nbsp;</td>
      <td width="58%" rowspan="8" align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>Men&uacute;</td>
      <td><?php echo $listaGrupos; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <label></label>
        
        <label></label>      <label></label></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td><input name="idGrupoActualizar" type="hidden" id="idGrupoActualizar" value="<?php echo (isset($idGrupoActualizar)) ? $idGrupoActualizar : '' ; ?>" /></td>
      <td><table width="100%" border="0" cellpadding="2">
        <tr>
          <td><label>
            <input type="submit" name="guardar" id="guardar" value="<?php echo $accion ?>" class="boton" />
          </label></td>
          <td><label>
            <input type="submit" name="archivar" id="archivar" value="Retirar" class="boton" <?php echo ($accion == 'Reintegro') ? 'disabled="disabled"' : ''; ?> />
          </label></td>
          <td><label>
            <input type="submit" name="cancelar" id="cancelar" value="Cancelar" class="boton" />
          </label></td>
        </tr>
      </table></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
