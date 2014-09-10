<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();


if(!isset($login))
    $login = '';

if(!isset($prenom))
    $prenom = '';

if(!isset($nom))
    $nom = '';

$selectedStatut = '';
if(!empty($rank))
    $selectedStatut = '<option selected="selected" value="'.$rank.'">'.$rankTitle[$rank].'</option>';


$statutOptions = $selectedStatut.'
    <option value="admin">Administrateur</option>
    <option value="supermembre">super membre</option>
    <option value="membre">Membre</option>';


$site_Content .= '
<form name="form1" id="form1" method="post" action="">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="120" height="30">Login</td>
      <td><input name="login" value="'.$login.'" type="text" id="login" /></td>
      <td width="100">Mot de passe </td>
      <td><input name="mdp" value="" type="text" id="mdp" /> (laisser vide pour ne pas changer)</td>
    </tr>
    <tr>
      <td height="30">Nom</td>
      <td><input name="nom" value="'.$nom.'" type="text" id="nom" /></td>
      <td>Pr&eacute;nom</td>
      <td><input name="prenom" value="'.$prenom.'" type="text" id="prenom" /></td>
    </tr>
    <tr height="30">
      <td>Statut </td>
      <td>
      <select name="rank">
        '.$statutOptions.'
      </select></td>
      
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4"><br />
      <input type="submit" name="Submit" value="Mettre à jours" /></td>
    </tr>
  </table>';