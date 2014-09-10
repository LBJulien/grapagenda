<?php
if(!defined('IN_WWW'))
    exit();

if(!$_Connected)
{
    $site_Content .= '
    <h2>Accueil</h2>
    <p>Pour utiliser cette agenda, vous devez &ecirc;tre un membre enregistr&eacute;.</p>';
}
else
{
    $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

    $site_Content .= '
    <h2>Accueil</h2>
    <p>Les prochains événements : </p>';


    $select = "select * FROM agenda_events WHERE date>='$today' ORDER BY date LIMIT 0,10";
    $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des &eacute;v&egrave;nement]</b> : <br />' . mysql_error());
    $nbrEvents = mysql_num_rows($result);

    if ($nbrEvents > 0)
    {

        $site_Content .= '<table width="100%">';

        while ($row = mysql_fetch_assoc($result))
        {
            $site_Content .= '
            <tr>
                <td width="200">'.safest($row["titre"]).'</td>
                <td><a href="./?a=agenda&date='.$row["date"].'">En savoir plus</a></td>
            </tr>';
        }

        $site_Content .= '</table>';
    }
    else
        $site_Content .= '<p><em>aucun événement à venir ...</em></p>';
}