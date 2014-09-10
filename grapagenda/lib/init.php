<?php
if(!defined('IN_WWW'))
    exit();

session_start();

error_reporting(E_ALL);

$rootdir = dirname(__FILE__).'/../';
$page_Dir = $rootdir;

if(defined('IN_ADMIN'))
    $page_Dir .= 'admin/';


// On inclus la configuration
include($rootdir . "lib/config.php");


// On inclus les fonctions
include($rootdir . "lib/fonction.php");

// Connexion à MySQL
include($rootdir . "lib/sql.php");


// Configuration :: salt
$requete = "SELECT salt FROM agenda_config LIMIT 1";
$sql = mysql_query($requete);
$n = mysql_num_rows($sql);

if($n == 1)
{
    $Row = mysql_fetch_object($sql);
    $CFG['salt'] = $Row->salt;
}

// --
// Définition de la page à inclure

$page_Include = $page_Dir.'accueil.php';

if(!empty($_GET["a"]))
{
    $a = $_GET["a"];

    $a = str_replace(':/', '', $a);
    $a = str_replace('./', '', $a);

    if(file_exists($page_Dir.$a.'.php'))
        $page_Include = $page_Dir.$a.'.php';
}


// test de connexion
$_Connected = false;
$_IsAdmin = false;
if(connected())
{
    $_Connected = true;
    if(is_admin())
        $_IsAdmin = true;
}

// Include de la page qui gère le menu
if(defined('IN_ADMIN'))
    include($page_Dir . "menu.php");
else
    include($rootdir . "lib/menu.php");