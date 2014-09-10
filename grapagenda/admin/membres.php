<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

$site_Content .= '<h2>Membres</h2>
                  <p><a href="./?a=membres">La liste des membres</a> - <a href="./?a=membres_actions&action=new"">Ajouter un membre</a></p>';
  
$select = 'SELECT * FROM agenda_membre';
$result = mysql_query($select);
  
$site_Content .= '
<table width="100%" align="center">
    <tr height="30">
        <td><b>Nom / pr&eacute;nom</b></td>
        <td><b>Login</b></td>
        <td><b>Rang</b></td>
        <td align="right"><b>Actions</a></td>
    </tr>';

while($Row = mysql_fetch_object($result))
{
    $site_Content .= '
    <tr height="20">
        <td>'.safest($Row->nom).' '.safest($Row->prenom).'</td>
        <td>'.safest($Row->login).'</td>
        <td>'.$rankTitle[$Row->rank].'</td>
        <td align="right">
            <a href="./?a=membres_actions&id='.$Row->id.'&action=edit">Modifier</a> - 
            <a href="./?a=membres_actions&id='.$Row->id.'&action=supprimer">Supprimer</a></td>
    </tr>';

}
$site_Content .= '</table>';
