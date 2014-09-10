<?php
/* !! Pré-Requis : Le fichier config.php doit être correctement renseigné !!
 *
 * > Test si la connexion MySQL se fait bien.
 * > Demander un mot de passe pour le compte admin
 * > Création des tables MySQL
 * > Générer un grain de sel
 * > Enregistrer le tout
 *
 * L'install est considéré comme OK si le salt existe
 */

define('IN_WWW', 1);

include('../lib/config.php');
include('../lib/fonction.php');

// Connexion MySQL + TEST
if(!mysql_connect($BDD['host'], $BDD['user'], $BDD['pass']))
    exit('INSTALLATION IMPOSSIBLE :: Connexion à la base de donnée impossible.');

if(!mysql_select_db($BDD['db']))
    exit('NSTALLATION IMPOSSIBLE :: Erreur de sélection de base de donnée.');

mysql_query("SET NAMES ".$CFG['charset']);
mysql_set_charset($CFG['charset']);
// --


// déjà installé ?
if(mysql_table_exists('agenda_config', $BDD['db']))
    exit('INSTALLATION IMPOSSIBLE :: Vous avez déjà installé GrapAgenda.');



// On demande le mot de passe
if(!$_POST)
{
    echo '
    <h1>Installation</h1>
    <h2>Mot de passe administrateur</h2>
    <form name="form" method="post" action="">
        <table width="100%">
            <tr>
                <td width="130">Mot de passe</td>
                <td><input name="password" type="text" id="password" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Valider et terminer l\'installation >" /></td>
            </tr>
        </table>
    </form>';
}
else
{

    if(empty($_POST['password']) || strlen($_POST['password']) < 4)
    {
        exit('Le mot de passe est vide OU fais moins de 4 caractères. Merci de cliquer sur Retour.');
    }
    
    // le grain de sel pour sécurisé les mots de passe
    $salt = uniqid('H', 3);

    // encodage du mot de passe
    $password = md5($_POST['password'] . $salt);


    // Création des tables MySQL
    $data_Sql = array();

    $data_Sql[] = '
    CREATE TABLE `agenda_config` (
      `salt` varchar(50) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

    $data_Sql[] = '
    CREATE TABLE `agenda_events` (
      `id` int(11) NOT NULL auto_increment,
      `date` int(100) NOT NULL,
      `type` mediumint(4) NOT NULL,
      `id_membre` int(6) NOT NULL,
      `titre` varchar(100) character set utf8 NOT NULL,
      `texte` text character set utf8 NOT NULL,
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

    $data_Sql[] = '
    CREATE TABLE `agenda_membre` (
      `id` int(6) NOT NULL auto_increment,
      `login` varchar(30) character set utf8 NOT NULL,
      `mdp` varchar(32) character set utf8 NOT NULL,
      `rank` varchar(15) character set utf8 NOT NULL,
      `nom` varchar(100) character set utf8 NOT NULL,
      `prenom` varchar(100) character set utf8 NOT NULL,
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

    $data_Sql[] = '
    CREATE TABLE `agenda_theme` (
      `id` mediumint(1) NOT NULL auto_increment,
      `titre` varchar(100) character set utf8 NOT NULL,
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';

    $data_Sql[] = "
        INSERT INTO `agenda_theme` (`id`, `titre`) VALUES
        (1, 'Fêtes'),
        (2, 'RDV Travail'),
        (3, 'A penser'),
        (4, 'Autres');";


    foreach($data_Sql as $n => $requete)
    {
        if(!mysql_query($requete))
            exit('Erreur lors de la création des tables MySQL : '.mysql_error());
    }


    // Les tables sont ajouté à la BDD :: Cool !

    // On Ajoute la config du grain de sel
    $requete = "INSERT INTO agenda_config SET salt='$salt'";
    if(!mysql_query($requete))
        exit('Erreur lors de la configuration de la table agenda_config : '.mysql_error());

    // On ajout l'admin
    $requete = "INSERT INTO agenda_membre SET login='admin', mdp='$password', rank='admin', nom='ADMIN', prenom='Admin'";
    if(!mysql_query($requete))
        exit('Erreur lors l\'ajout de l\'administrateur : '.mysql_error());


    // On affiche le message comme quoi tout va bien
    echo '
    <h1>Installation</h1>
    <h2>SUCCES</h2>
    <p>L\'installation est terminée.</p>
    <p>
        Login : admin<br />
        Mot de passe : '.safest($_POST['password']).'
    </p>
    <p><a href="../">Accès à l\'agenda</a></p>';
}