<?php
if(!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

$site_Content .= '
<h2>Admin</h2>
<p><b>Les 15 derniers &eacute;v&egrave;nement enregistr&eacute; dans l\'agenda</b></p>';


  $extraire11 = mysql_query("select id,login from agenda_membre");
  $nbrEvents11 = mysql_num_rows($extraire11);
  for($i=0; $i<$nbrEvents11; $i++){
    $id=mysql_result($extraire11,$i,"id"); $login=mysql_result($extraire11,$i,"login");
    $toutMembre[$id]=$login;
  }
  
  
$select = 'select * FROM agenda_events ORDER BY id LIMIT 0,15';
$result = mysql_query($select)  or die ('<b>Erreur MySQL [S&eacute;lection des &eacute;v&egrave;nement]</b> : <br />'.mysql_error() );
$nbrEvents=mysql_num_rows($result);

  if($nbrEvents>0){
  $site_Content .= '<table width="100%">';
  while($row=mysql_fetch_array($result)) {
    $id=$row["id"]; $titre=stripslashes(htmlentities($row["titre"])); $type=stripslashes(htmlentities($row["type"]));
    $texte=stripslashes(htmlentities($row["texte"])); $idM=$row["id_membre"];
    
    $site_Content .= '<tr height="20">';
      $site_Content .= '<td> - <b>'.$titre.'</b>, post&eacute; par '.stripslashes(htmlentities($toutMembre[$idM])).'<br />
      <i>'.$texte.'</i>
      </td>';
    $site_Content .= '</tr>';
    
  }
  $site_Content .= '</table>';
  }else{
    $site_Content .= 'Aucun &eacute;v&egrave;nement enregistrer';
  }