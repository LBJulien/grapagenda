<?php
if(!defined('IN_WWW'))
    exit();


$menu_Content = '<p> > <a href="./">Accueil</a></p>';


if($_Connected)
{
    $menu_Content .= '<p> > <a href="./?a=agenda">Agenda</a></p>';

    $menu_Content .= '<p>Bonjour <b>'.safest($_SESSION["mbr_login"]).'</b><br />
                         <a href="./?a=login&dc=1">Deconnexion</a></p>';
}
else
{
    $menu_Content .= '<p><a href="./?a=login">Connexion</a></p>';
}


if($_IsAdmin)
{
    $menu_Content .= '<p style="margin-top:10px; border-top:1px dotted #dedede"><a href="./admin/">Administration</a></p>';
}