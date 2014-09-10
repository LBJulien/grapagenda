<?php
if(!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

$menu_Content = '
<p>
    <a href="./">Accueil</a> <br />
    <a href="./?a=setup">Configuration</a> <br />
    <a href="./?a=membres">Gerer les membres</a> <br />
    <a href="./?a=events">Ev&egrave;nements</a>
</p>

<p>
    <a href="../">Retour au site</a><br />
    <a href="../?a=login&dc=1">Deconnexion</a>
</p>';