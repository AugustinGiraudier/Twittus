<?php
require "../vendor/autoload.php";
$router = new AltoRouter();
require "../Vendor/Routes.php";

$match = $router->match();

require "../Vue/Header.php";    //header commun à toutes les pages

if($match !== null && $match !== false)
{
    $match['target']();
}
else
{
    echo "erreur 404 - page introuvable...";
}

require '../Vue/Footer.php';    //footer commun à toutes les pages
