<?php 
require '../Model/PublicProfile.php';
$title = "Twittus - Profile Public";
$infos = null;

//recupere les données de l'utilisateur avec son id
function FindUserWithId(int $id)
{
    $fetch = DB_GetUserWithId($id);
    if ($fetch)
    {
        return $fetch;
    }
    echo 'Profile inconnu...';
    exit();
}

//recupere les 3 derniers tweets de l'utilisateur
function FindUserTweetsWithId(int $id)
{
    global $infos;
    $fetch = DB_GetTweetsWithUserId($id);
    if (empty($fetch))
    {
        $infos = "cet utilisateur n'a écrit aucun tweet...";
    }
    return $fetch;
}

//si l'id est définit
if(isset($_GET['id']))
{
    //on trouve l'utilisateur
    $user = FindUserWithId($_GET['id']);
    //on trouve ses 3 derniers tweets
    $Tweets = FindUserTweetsWithId($_GET['id']);
}
else{
    echo 'Profile inconnu...';
    exit();
}
require '../Vue/PublicProfile.php';
?>