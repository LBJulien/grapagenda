<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

$site_Content .= '<h2>Configuration</h2>';

$site_Content .= '
    <p><b>Les types d\'&eacute;v&egrave;nement</b><br />
    <a href="./?a=setup&op=add">Ajouter un type</a> | <a href="./?a=setup">Liste des types</a></p>';


if(!empty($_GET['op']) && $_GET['op'] == "add")
{

    if (!$_POST)
    {
        $site_Content .= '<p>Ajouter un type d\'&eacute;v&egrave;nement</p>';
        $site_Content .= '
        <form name="form1" id="form1" method="post" action="">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" height="30">Titre</td>
                <td><input name="titre" value="" type="text" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Ajouter" /></td>
            </tr>
        </table>
        </form>';
    }
    else
    {
        if(!empty($_POST['titre']))
        {
            $titre = smartQuote($_POST['titre']);

            $requete = "INSERT INTO agenda_theme SET titre='$titre'";
            $resultat = mysql_query($requete) or die("Requête invalide : <br />" . mysql_error());

            $site_Content .= '<p class="ok"><b>Cat&eacute;gorie ajout&eacute;.</b></p>';
        }
        else
            exit('Titre vide.');
    }

}
elseif(!empty($_GET['op']) && $_GET['op'] == "edit")
{

    $id = (int)$_GET['k'];

    $requete = "SELECT * FROM agenda_theme WHERE id='$id'";
    $sql = mysql_query($requete);
    $Row = mysql_fetch_object($sql);

    if (!$_POST)
    {
        $site_Content .= '<p>Modifier un type d\'&eacute;v&egrave;nement</p>';
        $site_Content .= '
        <form name="form1" id="form1" method="post" action="">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" height="30">Titre</td>
                <td><input name="titre" type="text" value="'.safest($Row->titre).'" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Modifier" /></td>
            </tr>
        </table>
        </form>';
    }
    else
    {
        if(!empty($_POST['titre']))
        {
            $titre = smartQuote($_POST['titre']);

            $requete = "UPDATE agenda_theme SET titre='$titre' WHERE id='$id'";
            $resultat = mysql_query($requete) or die("Requête invalide : <br />" . mysql_error());

            $site_Content .= '<p class="ok"><b>Cat&eacute;gorie modifié.</b></p>';
        }
        else
            exit('Titre vide.');
    }

}
elseif(!empty($_GET['op']) && $_GET['op'] == "del")
{

    if(!empty($_GET['k']))
    {
        $id = (int)$_GET['k'];

        if (mysql_query("DELETE FROM agenda_theme WHERE id='$id'")) {
            $site_Content .= '<p class="ok"><b>Le type d\'&eacute;v&egrave;nement est bien supprimé.</b></p>';
        }
    }

}
elseif(empty($_GET['op']))
{

    $extraire = mysql_query("SELECT id FROM agenda_theme");
    $total = mysql_num_rows($extraire);

    $select = "SELECT * FROM agenda_theme ORDER BY titre ASC";
    $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des th&egrave;mes]</b> : <br />' . mysql_error());
    $nbr = mysql_num_rows($result);

    if ($nbr > 0)
    {
        $site_Content .= '<table width="100%">';

        while ($row = mysql_fetch_array($result))
        {
            $titre = safest($row["titre"]);

            $site_Content .= '
            <tr>
                <td width="200"><b>' . $titre . '</b></td>
                <td width="60"><a href="./?a=setup&op=edit&k=' . $row["id"] . '">Modifier</a></td>
                <td><a href="./?a=setup&op=del&k=' . $row["id"] . '">Supprimer</a></td>
            </tr>';
        }

        $site_Content .= '</table>';
    }
}
