<?php
require "../vendor/autoload.php";
$router = new AltoRouter();
$router->map('GET','/',function () {
    require "../Controlleur/Home.php";
    
});
$router->map('POST','/',function () {
    require "../Controlleur/Home.php";
    
});
$router->map('GET','/Profile',function () {
    require "../Controlleur/Profile.php";
    
});
$router->map('POST','/Profile',function () {
    require "../Controlleur/Profile.php";
    
});
$router->map('GET','/ChangeInfos',function () {
    require "../Controlleur/ChangeInfos.php";
    
});
$router->map('POST','/ChangeInfos',function () {
    require "../Controlleur/ChangeInfos.php";
    
});
$router->map('GET','/PublicProfile',function () {
    require "../Controlleur/PublicProfile.php";
    
});
$router->map('POST','/PublicProfile',function () {
    require "../Controlleur/PublicProfile.php";
    
});


$match = $router->match();

if($match !== null && $match !== false)
{
    $match['target']();
}
else
{
    echo "erreur 404 - page introuvable...";
}
