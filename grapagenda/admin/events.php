<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

$site_Content .= '<h2>Gestion des &eacute;v&egrave;nement</h2>
                  <p><a href="./?a=events">Ev&egrave;nement &agrave; venir</a> | <a href="./?a=events&op=all">Tous les &eacute;v&egrave;nements</a></p>';


$site_Content .= 'Rechercher un &eacute;v&egrave;nement pr&eacute;cis (ex.: evts1)
<form name="form1" id="form1" method="post" action="./?a=search">
    R&eacute;f&eacute;rence : <input name="ref" type="text" id="ref" /> <input type="submit" name="Submit" value="Chercher" />
</form>';




$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

if (!isset($_GET['l']))
    $limite = 0;
else
    $limite = (int)$_GET['l'];

$nombre = 2;



if (!isset($_GET['op']))
{
    $site_Content .= '<br /><span class="operation">Liste des &eacute;v&egrave;nement &agrave; venir</span><br /><br />';


    $extraire = mysql_query("SELECT id,date FROM agenda_events WHERE date>'$today'");
    $total = mysql_num_rows($extraire);

    $verifLimite = verifLimite($limite, $total, $nombre);
    if (!$verifLimite) {
        $limite = 0;
    }

    if ($total > $nombre) {
        $site_Content .= '<p>Pages : ';
        $site_Content .= affichePages($nombre, $total, $limite, "./?a=events&l=[l]").'</p>';
    }

    $select = "select * FROM agenda_events WHERE date>='$today' ORDER BY date LIMIT $limite,$nombre";
    $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des &eacute;v&egrave;nement]</b> : <br />' . mysql_error());
    $nbrEvents = mysql_num_rows($result);

    if ($nbrEvents > 0)
    {

        $site_Content .= '<table width="100%">';
        while ($row = mysql_fetch_array($result))
        {
            $id = $row["id"];
            $titre = safest($row["titre"]);
            $type = safest($row["type"]);
            $texte = safest($row["texte"]);
            $idM = $row["id_membre"];
            $date = $row["date"];
            $texteDate = date('d', $date) . ' / ' . date('m', $date) . ' / ' . date('Y', $date);

            $req = "SELECT id,login FROM agenda_membre WHERE id='".(int)$idM."'";
            $sql = mysql_query($req);
            $User = mysql_fetch_assoc($sql);

            $site_Content .= '
            <tr valign="top">
                <td>
                    <p style="margin-bottom:0">' . $titre . '</b>, post&eacute; par ' . safest($User['login']) . ' pour le <i><b>' . $texteDate . '</b></p>
                    <p><i>' . $texte . '</i></p>
                </td>
                <td>
                    <span class="adminMenu">[<a href="./?a=events&op=edit&k=' . $id . '" title="Editer cette &eacute;v&egrave;nement">Modifier</a> |
                        <a href="./?a=events&op=erase&k=' . $id . '" title="Supprimer cette événement">Supprimer</a>]</span><b>
                </td>
            </tr>';
        }
        $site_Content .= '</table>';
    } else {
        $site_Content .= 'Aucun &eacute;v&egrave;nement enregistrer';
    }

}

elseif (!empty($_GET['op']) && $_GET['op'] == 'all')
{
    $site_Content .= '<br /><span class="operation">Tous les événements ...</span><br /><br />';


    $extraire = mysql_query("SELECT id FROM agenda_events");
    $total = mysql_num_rows($extraire);

    $verifLimite = verifLimite($limite, $total, $nombre);
    if (!$verifLimite) {
        $limite = 0;
    }

    if ($total > $nombre) {
        $site_Content .= '<p>Pages : ';
        $site_Content .= affichePages($nombre, $total, $limite, "./?a=events&op=all&l=[l]").'</p>';
    }

    $select = "select * FROM agenda_events ORDER BY date LIMIT $limite,$nombre";
    $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des &eacute;v&egrave;nement]</b> : <br />' . mysql_error());
    $nbrEvents = mysql_num_rows($result);

    if ($nbrEvents > 0)
    {

        $site_Content .= '<table width="100%">';
        while ($row = mysql_fetch_array($result))
        {
            $id = $row["id"];
            $titre = safest($row["titre"]);
            $type = safest($row["type"]);
            $texte = safest($row["texte"]);
            $idM = $row["id_membre"];
            $date = $row["date"];
            $texteDate = date('d', $date) . ' / ' . date('m', $date) . ' / ' . date('Y', $date);

            $req = "SELECT id,login FROM agenda_membre WHERE id='".(int)$idM."'";
            $sql = mysql_query($req);
            $User = mysql_fetch_assoc($sql);

            $site_Content .= '
            <tr valign="top">
                <td>
                    <p style="margin-bottom:0">' . $titre . '</b>, post&eacute; par ' . safest($User['login']) . ' pour le <i><b>' . $texteDate . '</b></p>
                    <p><i>' . $texte . '</i></p>
                </td>
                <td>
                    <span class="adminMenu">[<a href="./?a=events&op=edit&k=' . $id . '" title="Editer cette &eacute;v&egrave;nement">Modifier</a> |
                        <a href="./?a=events&op=erase&k=' . $id . '" title="Supprimer cette événement">Supprimer</a>]</span><b>
                </td>
            </tr>';
        }
        $site_Content .= '</table>';
    } else {
        $site_Content .= 'Aucun &eacute;v&egrave;nement enregistrer';
    }
}
elseif(!empty($_GET['op']) && $_GET['op'] == "edit" && !empty($_GET['k']))
{

    $id = (int)$_GET['k'];

    if (!$_POST)
    {

        $extraire = mysql_query("SELECT id FROM agenda_events WHERE id='$id'");
        $nbr = mysql_num_rows($extraire);
        if ($nbr != 1) {
            $site_Content .= '<br /><span class="erreurTexte">R&eacute;f&eacute;rence invalide.</span>';
        }
        else
        {
            $select = "SELECT * FROM agenda_events WHERE id='$id' LIMIT 0,1";
            $result = mysql_query($select);
            $row = mysql_fetch_array($result);


            $titreEvents = safest($row['titre']);
            $texteEvents = safest($row['texte']);
            $type = $row['type'];
            $idEvents = $row['id'];


            $listeSelect = '';
            
            $select = "SELECT * FROM agenda_theme ORDER BY titre ASC";
            $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des th&egrave;mes]</b> : <br />' . mysql_error());
            $nbr = mysql_num_rows($result);

            if ($nbr > 0)
            {
                while ($row = mysql_fetch_array($result))
                {
                    $idCat = $row["id"];
                    $titre = safest($row["titre"]);

                    if ($idCat == $type)
                        $listeSelect.='<option value="' . $idCat . '" selected="selected">' . $titre . '</option>';
                    else
                        $listeSelect.='<option value="' . $idCat . '">' . $titre . '</option>';
                }
            }

                $site_Content .= '<br /><br />Edition de l\'&eacute;v&egrave;nement <b>etvs' . $idEvents . '</b> (' . $titreEvents . ') <br />';

                $site_Content .= '
                <form name="form1" id="form1" method="post" action="">
                    <table width="250" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="81" height="30">Titre</td>
                        <td width="169" height="31"><input name="titre" type="text" id="titre" value="' . stripslashes($titreEvents) . '" /></td>
                    </tr>
                    <tr>
                        <td height="30">Type</td>
                        <td width="169" height="31"><select name="type" id="select">' . $listeSelect . '</select></td>
                    </tr>
                    <tr>
                        <td>Texte</td>
                        <td width="169"><textarea name="texte" id="textarea">' . stripslashes($texteEvents) . '</textarea></td>
                    </tr>
                    <tr>
                        <td><div align="left"></div></td>
                        <td width="169">&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td width="169"><input type="submit" name="Submit" value="Envoyer" /></td>
                    </tr>
                    </table>
                </form>';
        }

    }
    else
    {

        if(!empty($_POST['titre']) && !empty($_POST['texte']) && !empty($_POST['type']))
        {
            $titre = smartQuote($_POST['titre']);
            $texte = smartQuote($_POST['texte']);
            $cat = smartQuote($_POST['type']);

            if (mysql_query("UPDATE agenda_events SET titre='$titre', type='$cat', texte='$texte' WHERE id='$id'")) {
                $site_Content .= '<br /><center><span class="erreurTexte">Ev&egrave;nement evts' . $id . ' bien mis &agrave; jours.</span> <br /><a href="./?a=events">Retour</a></center>';
            }

        } 
        else
        {
            $site_Content .= 'Param&egrave;tres absents ...';
        }
    }


//Cas ou l'on supprime un events
}
elseif (!empty($_GET['op']) && $_GET['op'] == "erase" && !empty($_GET['k']))
{
    $id = (int)$_GET['k'];

    $extraire = mysql_query("SELECT id FROM agenda_events WHERE id='$id'");
    if (mysql_num_rows($extraire) != 1) {
        Exit("Cette &eacute;v&egrave;nement n'existe pas");
    }

    if (empty($_GET['verif']))
    {
        $site_Content .= '<br /><br />Confirmez-vous la suppression ? - <a href="?a=events&op=erase&k='.$id.'&verif=1">Oui</a> - <a href="./?a=events">Non</a>';
    }
    else
    {
        if (mysql_query("DELETE FROM agenda_events WHERE id='$id'")) {
            $site_Content .= '<br /><center><span class="erreurTexte">Ev&egrave;nement evts' . $id . ' bien SUPPRIME.</span> <br />
          <a href="./?a=events">Retour</a></center>';
        }
    }
}