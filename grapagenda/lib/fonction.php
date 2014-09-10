<?php
if (!defined('IN_WWW'))
    exit();

function mysql_table_exists($table , $db) {
	$requete = 'SHOW TABLES FROM '.$db.' LIKE \''.$table.'\'';
	$exec = mysql_query($requete);
	return mysql_num_rows($exec);
}


//Fonction qui calcul el nbr de jour etc...
function days_in_month($month, $year) {
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

function is_admin() {
    if (!empty($_SESSION['mbr_rank']) && $_SESSION['mbr_rank'] == "admin")
        return true;
    else
        return false;
}

function connected() {
    if (!empty($_SESSION["mbr_id"]) &&
            !empty($_SESSION["mbr_login"]) &&
            !empty($_SESSION["mbr_mdp"]) &&
            !empty($_SESSION["mbr_rank"])) {
        $login = smartQuote($_SESSION["mbr_login"]);
        $mdp = smartQuote($_SESSION["mbr_mdp"]);

        $requete = "SELECT login,mdp FROM agenda_membre WHERE login='$login' && mdp='$mdp' LIMIT 1";
        $sql = mysql_query($requete);
        $nb = mysql_num_rows($sql);

        if ($nb == 1)
            return true;
        else
            return false;
    }
    else
        return false;
}

function safest($str) {
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

function smartQuote($value) {
    if ((!isset($value)) || (is_null($value)) || ($value === ""))
        $value = '';
    else {
        if (is_string($value))
            $value = mysql_real_escape_string($value);
        else
            $value = (is_numeric($value)) ? $value : '';
    }

    return $value;
}

//Convert the date into francais
function frDate() {
    $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
    $Mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $datefr = $Jour[date("w")] . " " . date("d") . " " . $Mois[date("n")] . " " . date("Y");
    return $datefr;
}

//Fonction qui traduit le nom des mois en fracais
function convert($month) {
    if ($month == "Mar") {
        $month_out = "Mars";
    } elseif ($month == "Apr") {
        $month_out = "Avril";
    } elseif ($month == "May") {
        $month_out = "Mai";
    } elseif ($month == "Jun") {
        $month_out = "Juin";
    } elseif ($month == "Jul") {
        $month_out = "Juillet";
    } elseif ($month == "Aug") {
        $month_out = "Août";
    } elseif ($month == "Sep") {
        $month_out = "Septembre";
    } elseif ($month == "Oct") {
        $month_out = "Octobre";
    } elseif ($month == "Nov") {
        $month_out = "Novembre";
    } elseif ($month == "Dec") {
        $month_out = "Décembre";
    } elseif ($month == "Jan") {
        $month_out = "Janvier";
    } elseif ($month == "Feb") {
        $month_out = "Février";
    } else {
        $month_out = $month;
    }
    return $month_out;
}

function affichePages($nbr_par_page,$total,$page_actuel,$theme)
{
    $nbpages = ceil($total/$nbr_par_page);
    $numeroPages = 1;
    $compteurPages = 1;
    $limite = 0;
    $output = '';

    while($numeroPages <= $nbpages)
    {
        if($page_actuel == $limite)
            $output .= ' <span class="selected">'.$numeroPages.'</span> ';
        else
        {
            $link = str_replace('[l]',$limite,$theme);
            $output .= ' <span><a href="'.$link.'">'.$numeroPages.'</a></span> ';
        }

        $limite += $nbr_par_page;
        $numeroPages++;
        $compteurPages++;

        if($compteurPages == $nbr_par_page)
            $compteurPages = 1;
    }

    return $output;
}

function verifLimite($limite, $total, $nombre) {
    if (is_numeric($limite)) {
        if (($limite >= 0) && ($limite <= $total) && (($limite % $nombre) == 0)) {
            $valide = 1;
        } else {
            $valide = 0;
        }
    } else {
        $valide = 0;
    }
    return $valide;
}