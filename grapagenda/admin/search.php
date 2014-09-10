<?php
if (!defined('IN_WWW') || !defined('IN_ADMIN'))
    exit();

if($_POST && !empty($_POST['ref']))
{
    $idTemp = $_POST['ref'];
    $id = (int)str_replace("evts" ,"", $idTemp);

    $extraire = mysql_query("SELECT id FROM agenda_events WHERE id='$id'");
    $nbr = mysql_num_rows($extraire);

    if($nbr == 1)
    {
        header("Location: ./?a=events&op=edit&k=$id");
        exit();
    }
    else
        $site_Content .= '<p>Evénement inexistant.</p>';
}