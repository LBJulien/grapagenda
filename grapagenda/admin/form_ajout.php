<form name="form1" id="form1" method="post" action="add_events.php?date=<?php echo $date; ?>">
  <table width="250" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="81" height="30">
        Titre
      </td>
      <td width="169" height="31"><input name="titre" type="text" id="titre" /></td>
    </tr>
    <tr>
      <td height="30">
        Type
      </td>
      <td width="169" height="31">
      <select name="type" id="select">
        <?php echo $listeSelect; ?>
      </select></td>
    </tr>
    <tr>
      <td>Texte</td>
      <td width="169"><textarea name="texte" id="textarea"></textarea></td>
    </tr>
    <tr>
      <td><div align="left"></div></td>
      <td width="169">&nbsp;</td>
    </tr>
    <tr>
      <td>              <div align="left"></div></td>
      <td width="169"><input type="submit" name="Submit" value="Envoyer" /></td>
    </tr>
  </table>
</form>