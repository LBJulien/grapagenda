<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();


$site_Content .= '<h2>Gestion des membres</h2>
                  <p><a href="./?a=membres">La liste des membres</a> - <a href="./?a=membres_actions&action=new"">Ajouter un membre</a></p>';


$action = '';
if(!empty($_GET["action"]))
    $action = $_GET["action"];


//cas ou on edite un membre
if (!empty($_GET["id"]) && $action == "edit")
{

    $id = (int)$_GET['id'];

    $select = "SELECT * FROM agenda_membre WHERE id='$id' LIMIT 1";
    $result = mysql_query($select);
    $row = mysql_fetch_assoc($result);


    if($_POST)
    {
        $site_Content .= '<br /><br/><div class="operation">Mise &agrave; jour en cours ...';

        $prenom = smartQuote($_POST['prenom']);
        $login = smartQuote($_POST['login']);
        $nom = smartQuote($_POST['nom']);
        $rank = smartQuote($_POST['rank']);


        $password = $row['mdp'];
        if(!empty($_POST['mdp']))
        {
            $password = md5($_POST['mdp'] . $CFG['salt']);
        }

        mysql_query("UPDATE agenda_membre SET
                                      login='$login' ,
                                      mdp='$password' ,
                                      nom='$nom' , 
                                      rank='$rank' ,
                                      prenom='$prenom' WHERE id='$_GET[id]'");

        $site_Content .= '<br />La fiche de ' . safest($row["nom"]) . ' ' . safest($row["prenom"]) . ' est bien mis &agrave; jours !</div>';
    }
    else
    {

        $site_Content .= '<br /><br />Fiche de <b>' . safest($row["nom"]) . ' ' . safest($row["prenom"]) . '</b> <br /><br />';

        $id = $row["id"];
        $login = $row["login"];
        $nom = $row["nom"];
        $prenom = $row["prenom"];
        $rank = $row["rank"];

        include("./form_membres.php");
    }

//cas ou on ajoute un membre
}
elseif ($action == "new")
{

    $site_Content .= '<br /><br /><b>Ajouter un membre</b> <br /><br />';

    if (!$_POST) {
        include("form_membres.php");
    }
    else
    {
        if (!$_POST['login'] OR !$_POST['mdp'] OR !$_POST['prenom']) {
            exit("Les champs Login, mot de passe et pr&eacute;nom sont obligatoire.");
        }

        $prenom = smartQuote($_POST['prenom']);
        $login = smartQuote($_POST['login']);
        $nom = smartQuote($_POST['nom']);
        $rank = smartQuote($_POST['rank']);
        $password = md5($_POST['mdp'] . $CFG['salt']);

        $requete = "INSERT INTO agenda_membre SET login='$login' ,
                                                  mdp='$password' ,
						  nom='$nom' ,
						  prenom='$prenom' ,
						  rank='$rank'";

        $resultat = mysql_query($requete);

        $site_Content .= '<div class="operation">';
        $site_Content .= 'Le membre ' . safest($_POST['nom']) . ' ' . safest($_POST['prenom']) . ' est bien ajouter &agrave; la base </div>';
    }


//sinon, on pourrai aussi supprimer non?
}
elseif ($action == "supprimer" && !empty($_GET["id"]))
{

    $id = (int)$_GET['id'];

    if (!isset($_GET['verif']))
    {
        $site_Content .= '<p>Confirmer la suppression ? - <a href="./?a=membres_actions&id='.$id.'&action=supprimer&verif=ok">Oui</a> - <a href="./?a=membres">Non</a>';
    }
    elseif ($_GET['verif'] == 'ok')
    {
        mysql_query("DELETE FROM agenda_membre WHERE id='$id'");
        $site_Content .= '<br /><br />Membre supprim&eacute;.';
    }
}