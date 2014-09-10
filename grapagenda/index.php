<?php
/*
 * GrapAgenda
 * Version 0.9 Beta
 * Auteur : GUNNING Sky
 */

define('IN_WWW', 1); // ne pas toucher ceci

include("./lib/init.php");

// include de la page
$site_Content = '';
include($page_Include);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <title>GrapAgenda</title>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $CFG['charset']; ?>" />
    <link href="./medias/style.css" rel="stylesheet" media="screen" />
</head>

<body>

    <div id="conteneur">

        <h1>GrapAgenda</h1>

        <table width="800" border="0" align="center" class="cadre">
            <tr valign="top">
                <td width="130" style="border-right:1px solid #dedede">
                    <?php echo $menu_Content ?>
                </td>
                <td style="padding-left:10px">
                    <?php echo $site_Content ?>
                </td>
            </tr>
        </table>

        <p id="footer">Une création <a href="http://www.viaphp.net/" target="_blank">viaPHP</a></p>

    </div>

</body>
</html>
