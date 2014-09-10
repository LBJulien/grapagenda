<?php
if(!defined('IN_WWW'))
    exit();


if(!empty($_GET['dc']))
{
    session_destroy();

    header('location: ./');
    exit();
}


if(!$_POST)
{
    $site_Content .= '
    <h2>Connexion</h2>
    <form name="form" method="post" action="">
        <table width="100%">
            <tr>
                <td width="80">Login</td>
                <td><input name="login" type="text" id="login" /></td>
            </tr>
            <tr>
                <td>Mot de passe</td>
                <td><input name="mdp" type="password" id="mdp" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Connexion" /></td>
            </tr>
        </table>
    </form>';
}
else
{
    if(empty($_POST['login']) || empty($_POST['mdp']))
      exit("Vous devez mettre un login ET un mot de passe");

    $login = smartQuote($_POST['login']);
    $mdp = md5($_POST['mdp'] . $CFG['salt']);

    $requete = "SELECT * FROM agenda_membre WHERE login='$login' && mdp='$mdp' LIMIT 1";
    $sql = mysql_query($requete);
    $nb = mysql_num_rows($sql);

    if($nb != 1)
        exit("Vous avez entr&eacute; un mauvais login ou mot de passe.");


    $User = mysql_fetch_object($sql);

    $_SESSION["mbr_id"] = $User->id;
    $_SESSION["mbr_login"] = $User->login;
    $_SESSION["mbr_mdp"] = $mdp;
    $_SESSION["mbr_rank"] = $User->rank;

    $expire = 365*24*3600;
    setcookie("plan_login", $login, time()+$expire, "/", "", 0);
    setcookie("plan_mdp", $mdp, time()+$expire, "/", "", 0);

    header("location: ./");
    exit();
}