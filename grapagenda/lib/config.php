<?php
if(!defined('IN_WWW'))
    exit();

$BDD = array();
$CFG = array();

/*
 * Infos de connexion à la base de donnée
*/
$BDD['host'] = 'localhost';
$BDD['user'] = 'root';
$BDD['pass'] = '';
$BDD['db'] = 'BDD';


// Encodage HTML
$CFG['charset'] = 'UTF-8';


// Grades
$rankTitle["admin"] = "Administrateur";
$rankTitle["membre"] = "Membre";
$rankTitle["supermembre"] = "Super Membre";