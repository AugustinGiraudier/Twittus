<?php 
require '../Model/PublicProfile.php';
$title = "Twittus - Profile Public";
$infos = null;
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
if(isset($_GET['id']))
{
    $user = FindUserWithId($_GET['id']);
    $Tweets = FindUserTweetsWithId($_GET['id']);
}
require '../Vue/PublicProfile.php';
?>