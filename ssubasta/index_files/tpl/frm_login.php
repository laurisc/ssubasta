<form id="form1" name="form1" method="post" action="">
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="3">
    <tr>
      <td colspan="3" align="center"><?php echo (isset($msjErrorRegistro)) ? $msjErrorRegistro : ""; ?>&nbsp;</td>
    </tr>
    <tr>
      <td width="105" align="right">Usuario:</td>
      <td width="144"><label>
        <input type="text" name="login" id="login" />
      </label></td>
      <td width="151">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Clave:</td>
      <td><label>
        <input type="password" name="clave" id="clave" />
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="Ingresar" class="btn"/>
        <input name="menu-0" type="hidden" id="menu-0" value="Préstamos" />
        <input name="archivo-0" type="hidden" id="archivo-0" value="prestamos.php" />
        <input name="cambioModulo" type="hidden" id="cambioModulo" value="1" />
      </label></td>
      <td></td>
    </tr>
  </table>
</form>
