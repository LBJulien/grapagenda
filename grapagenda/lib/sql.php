<?php
if(!defined('IN_WWW'))
    exit();

// Connexion MySQL
if(!@mysql_connect($BDD['host'], $BDD['user'], $BDD['pass']))
    exit('Erreur de connexion à la base de donnée.');

if(!@mysql_select_db($BDD['db']))
    exit('Erreur sur la base de donnée [No Selection].');

mysql_query("SET NAMES ".$CFG['charset']);
mysql_set_charset($CFG['charset']);
// --


// Test si l'install à eu lieux .. les tables existe ?
if(!mysql_table_exists('agenda_config', $BDD['db']))
{
    header("location: ./install/");
    exit();
}

unset($BDD);