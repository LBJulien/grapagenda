<?php
if (!defined('IN_WWW'))
    exit();

if (!$_Connected)
    exit('Espace réservé : accès interdit');

if($_Connected && ($_SESSION["mbr_rank"] == "admin" || $_SESSION["mbr_rank"] == "supermembre"))
{

    if(empty($_POST['titre']) OR empty($_POST['type']) OR empty($_POST['texte']))
      exit("Vous n'avez pas remplies tous les champs.");


    $date = (int)$_GET['date'];
    $user_id = (int)$_SESSION['mbr_id'];
    $type = (int)$_POST['type'];
    $texte = smartQuote($_POST['texte']);
    $titre = smartQuote($_POST['titre']);

    $requete = "INSERT INTO agenda_events SET
                date='$date',
                id_membre='$user_id' ,
                type='$type', 
                titre='$titre', 
                texte='$texte'";

    if(!mysql_query($requete))
        exit(mysql_error());

    header("location: ./?a=agenda&date=".$date);
    exit();
}
else
    exit('Accès restreint.');