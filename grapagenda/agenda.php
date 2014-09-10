<?php
if (!defined('IN_WWW'))
    exit();

if (!$_Connected)
    exit('Espace réservé : accès interdit');


$anneTitre['jan'] = "Janvier";
$anneTitre['jun'] = "Juin";
$anneTitre['feb'] = "Février";
$anneTitre['aug'] = "Aout";
$anneTitre['mar'] = "Mars";
$anneTitre['sep'] = "Septembre";
$anneTitre['apr'] = "Avril";
$anneTitre['oct'] = "Octobre";
$anneTitre['may'] = "Mai";
$anneTitre['nov'] = "Novembre";
$anneTitre['jul'] = "Juillet";
$anneTitre['dec'] = "Décembre";


if (!isset($_REQUEST['date'])) {
    $date = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
} else {
    $date = $_REQUEST['date'];
}
$la = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);

$month_start = mktime(0, 0, 0, $month, 1, $year);
$month_name = date('M', $month_start);
$monthTitre = $anneTitre[strtolower($month_name)];
$month_start_day = date('D', $month_start);

switch ($month_start_day) {
    case "Sun": $offset = 0;
        break;
    case "Mon": $offset = 1;
        break;
    case "Tue": $offset = 2;
        break;
    case "Wed": $offset = 3;
        break;
    case "Thu": $offset = 4;
        break;
    case "Fri": $offset = 5;
        break;
    case "Sat": $offset = 6;
        break;
}

if ($month == 1) {
    $num_days_last = cal_days_in_month(0, 12, ($year - 1));
} else {
    $num_days_last = cal_days_in_month(0, ($month - 1), $year);
}

$num_days_current = cal_days_in_month(0, $month, $year);

for ($i = 1; $i <= $num_days_current; $i++) {
    $num_days_array[] = $i;
}

for ($i = 1; $i <= $num_days_last; $i++) {
    $num_days_last_array[] = $i;
}

if ($offset > 0) {
    $offset_correction = array_slice($num_days_last_array, -$offset, $offset);
    $new_count = array_merge($offset_correction, $num_days_array);
    $offset_count = count($offset_correction);
} else {
    $offset_count = 0;
    $new_count = $num_days_array;
}

$current_num = count($new_count);


if ($current_num > 35) {
    $num_weeks = 6;
    $outset = (42 - $current_num);
} elseif ($current_num < 35) {
    $num_weeks = 5;
    $outset = (35 - $current_num);
}
if ($current_num == 35) {
    $num_weeks = 5;
    $outset = 0;
}

for ($i = 1; $i <= $outset; $i++) {
    $new_count[] = $i;
}

$weeks = array_chunk($new_count, 7);

$previous_link = "<a href=\"./?a=agenda&date=";
if ($month == 1) {
    $previous_link .= mktime(0, 0, 0, 12, $day, ($year - 1));
} else {
    $previous_link .= mktime(0, 0, 0, ($month - 1), $day, $year);
}
$previous_link .= "\"><< Pr&eacute;c&eacute;dent</a>";

$next_link = "<a href=\"./?a=agenda&date=";
if ($month == 12) {
    $next_link .= mktime(0, 0, 0, 1, $day, ($year + 1));
} else {
    $next_link .= mktime(0, 0, 0, ($month + 1), $day, $year);
}
$next_link .= "\">Suivant >></a>";

$site_Content .= "<br /><table align=\"center\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" width=\"600\" class=\"calendar\">" .
        "<tr>" .
        "<td colspan=\"7\">" .
        "<table width=\"100%\" align=\"center\">" .
        "<tr>" .
        "<td colspan=\"2\" width=\"100\" align=\"left\">$previous_link</td>" .
        "<td colspan=\"3\" align=\"center\">$monthTitre $year</td>" .
        "<td colspan=\"2\" width=\"100\" align=\"right\">$next_link</td>" .
        "</tr>" .
        "</table>" .
        "</td>" .
        "<tr height=\"10\">" .
        "<td class=\"small\">Dimanche</td><td class=\"small\">Lundi</td><td class=\"small\">Mardi</td><td class=\"small\">Mercredi</td><td class=\"small\">Jeudi</td><td class=\"small\">Vendredi</td><td class=\"small\">Samedi</td>" .
        "</tr>";

$i = 0;

foreach ($weeks AS $week)
{

    $site_Content .= "<tr height=\"20\">";

    foreach ($week as $d)
    {

        if ($i < $offset_count) {
            $day_link = $d;
            $site_Content .= "<td class=\"nonmonthdays\">$day_link</td>";
        }

        if (($i >= $offset_count) && ($i < ($num_weeks * 7) - $outset)) {

            $dateLa = mktime(0, 0, 0, $month, $d, $year);
            $extraire1 = mysql_query("select * from agenda_events WHERE date='$dateLa'");
            $nbrEvents1 = mysql_num_rows($extraire1);

            if ($nbrEvents1 > 0) {
                $eventsHere = " <span class=\"gros\">*</span>";
            } else {
                $eventsHere = "";
            }

            $day_link = "<a href=\"./?a=agenda&date=" . mktime(0, 0, 0, $month, $d, $year) . "\">$d</a> $eventsHere";
            if ($la == mktime(0, 0, 0, $month, $d, $year)) {
                $site_Content .= "<td class=\"today\">$day_link</td>";
            } elseif ($day == $d) {
                $site_Content .="<td class=\"clic\">$day_link</td>";
            } else {
                $site_Content .= "<td class=\"days\">$day_link</td>";
            }

        } elseif (($outset > 0)) {

            if (($i >= ($num_weeks * 7) - $outset)) {
                $day_link = $d;
                $site_Content .= "<td class=\"nonmonthdays\">$day_link</td>";
            }

        }

        $i++;
    }
    $site_Content .= "</tr>";
}

$site_Content .= '</table>';

$site_Content .= '
<br /><br />

<table width="100%"><tr><td colspan="2">
            <h2>Infos pour le <b>'.$day . '/' . $month . '/' . $year.'</b></h2>
        </td></tr>

    <tr><td valign="top">';

            $toutAf = '';
            
            $extraire = mysql_query("SELECT * FROM agenda_events WHERE date='".(int)$date."'");
            $nbrEvents = mysql_num_rows($extraire);

            if ($nbrEvents > 0)
            {

                $site_Content .='Il y\'a <b>' . $nbrEvents . '</b> &eacute;v&egrave;nement(s) pour cette date. <br />';
                $toutAf.='<table width="98%" class="pts">';
                for ($i = 0; $i < $nbrEvents; $i++)
                {
                    $id = mysql_result($extraire, $i, "id");
                    $mbr_idd = mysql_result($extraire, $i, "id_membre");
                    $type = mysql_result($extraire, $i, "type");
                    $texte = mysql_result($extraire, $i, "texte");
                    $titre = mysql_result($extraire, $i, "titre");

                    $extraire11 = mysql_query("SELECT id,titre FROM agenda_theme WHERE id='$type'");
                    $nbrEvents11 = mysql_num_rows($extraire11);
                    if ($nbrEvents11 == 1) {
                        $type = mysql_result($extraire11, 0, "titre");
                    } else {
                        $type = "Inconnu";
                    }

                    $req = "SELECT id,login FROM agenda_membre WHERE id='".(int)$mbr_idd."'";
                    $sql = mysql_query($req);
                    $User = mysql_fetch_assoc($sql);

                    $toutAf .= '
                    <tr>
                        <td>
                            <b>' . safest($titre) . '</b> - de ' . safest($User['login']) . '  &nbsp; <i>Ref : evts' . $id . '</i><br />
                            Type : <b>' . safest($type) . '</b><br />
                            Description : <i>' . safest($texte) . '</i>
                        </td>
                    </tr>';
                }

                $toutAf .= '</table>';

                $site_Content .= $toutAf;
                $toutAf = "";

            } else {
                $site_Content .='<span class="red">Aucun &eacute;v&egrave;nement pour cette date.</span>';
            }


        $site_Content .= '
        </td><td valign="top" width="250" align="right">';


            if($_Connected && ($_SESSION["mbr_rank"] == "admin" || $_SESSION["mbr_rank"] == "supermembre"))
            {

                $site_Content .='<b>Ajouter un &eacute;v&egrave;nement</b>';

                $listeSelect = '';

                $select = "SELECT * FROM agenda_theme ORDER BY titre ASC";
                $result = mysql_query($select) or die('<b>Erreur MySQL [S&eacute;lection des th&egrave;mes]</b> : <br />' . mysql_error());
                $nbr = mysql_num_rows($result);

                if ($nbr > 0)
                {
                    while ($row = mysql_fetch_assoc($result))
                    {
 
                        $listeSelect.='<option value="' . $row["id"] . '">' . safest($row["titre"]) . '</option>';
                    }
                }

                $site_Content .= '
                <form name="form1" id="form1" method="post" action="./?a=add_events&date='.$date.'">
                <table width="250" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="81" height="30">Titre</td>
                        <td width="169" height="31"><input name="titre" type="text" id="titre" /></td>
                    </tr>
                    <tr>
                        <td height="30">Type</td>
                        <td width="169" height="31"><select name="type" id="select">'.$listeSelect.'</select></td>
                    </tr>
                    <tr>
                        <td>Texte</td>
                        <td width="169"><textarea name="texte" id="textarea"></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td width="169"><input type="submit" name="Submit" value="Envoyer" /></td>
                    </tr>
                </table>
                </form>';
            }

            $site_Content .= '
        </td></tr>
</table>';