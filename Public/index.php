<?php
require "../vendor/autoload.php";
$router = new AltoRouter();
$router->map('GET','/',function () {
    require "../Vue/Home.php";
    
});
$router->map('POST','/',function () {
    require "../Vue/Home.php";
    
});
$router->map('GET','/Profile',function () {
    require "../Vue/Profile.php";
    
});
$router->map('POST','/Profile',function () {
    require "../Vue/Profile.php";
    
});
$router->map('GET','/ChangeInfos',function () {
    require "../Vue/ChangeInfos.php";
    
});
$router->map('POST','/ChangeInfos',function () {
    require "../Vue/ChangeInfos.php";
    
});
$router->map('GET','/PublicProfile',function () {
    require "../Vue/PublicProfile.php";
    
});
$router->map('POST','/PublicProfile',function () {
    require "../Vue/PublicProfile.php";
    
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
