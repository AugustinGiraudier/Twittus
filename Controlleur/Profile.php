<?php
session_start();
require '../Model/Profile.php';
const MAX_TWEET_LEN = 140;
$recherche = null;
$erreurs = null;
$infos = null;
$AlreadyFollowed = null;
function verifierTweet()
 {
    if(strlen($_POST['NewTweet']) > MAX_TWEET_LEN || strlen($_POST['NewTweet']) < 1)
    {
        return 'Erreur, nombre de caractères non compris entre 1 et ' . MAX_TWEET_LEN . "...";
    }
    return null;
 }
 function GetFollowed():array
 {
    $followed = [];
    $fetch = DB_GetFollowed();
    if(!isset($fetch[0]))
    {
        $followed[]=$_SESSION['id'];
        return $followed;
    }
    foreach($fetch as $follower)
    {
        $followed[]=$follower[0];
    }
    $followed[]=$_SESSION['id'];
    return $followed;
 }
 function GetTweets(array $followed):array
 {
    $tweets2 = [];
    foreach($followed as $id)
    {
        ///////////tweets//////////
        $fetch = DB_GetTweets($id);
        if($fetch)
        {
            $tweets[]=$fetch;
        }
        ///////////retweets//////////
        $fetch2 = DB_GetReTweets($id);
        if($fetch2)
        {
            $tweets[]=$fetch2;
        }
    }
    if(isset($tweets))
    {
        foreach($tweets as $users)
        {
            foreach($users as $ttweet)
            {
                $tweets2[]=$ttweet;
            }
        }
    } 
    return $tweets2;
 }
 function verifyTweeter($Tid, $Uid)
 {
    $fetch = DB_GetTweetsSenders($Tid);
    if($fetch['sender_id'] !== $Uid)
    {
        return false;
    }
    return true;
 }
 function DelTweet($Tid)
 {
    DB_DeleteTweetsWithId($Tid);
    //supprime les retweets associés à ce tweet :
    DB_DeleteRetweetswithId($Tid);
 }
 $tweetSortByDate = function ($a,$b)
 {
    $ta = new DateTime($a['publish_date']);
    $tb = new DateTime($b['publish_date']);
    return ($ta > $tb) ? -1 : 1;
 };
if(isset($_GET['delTweet']))
{
    if(VerifyTweeter($_GET['delTweet'],$_SESSION['id']))
    {
        DelTweet($_GET['delTweet']);
        $succes = 'Tweet supprimé';
    }
    else{
        $erreurs = "Vous ne pouvez pas supprimer un tweet qui ne vous appartient pas...";
    }
}
$followeds = GetFollowed();
if(!isset($followeds[1]))
{
    $infos = 'Vous ne suivez encore personne...';
}
$tweets = GetTweets($followeds);
uasort($tweets, $tweetSortByDate);
if(!isset($tweets[0]))
{
    $infos2 = "Vous n'avez aucun tweet";
}
if(isset($_GET['step']))
{
    if($_GET['step'] === 'deconnexion')
    {
        session_destroy();
        header('Location: /');
        exit();
    }
    if($_GET['step'] === 'changeinfos')
    {
        header('Location: ChangeInfos');
        exit();
    }
    if($_GET['step'] === 'follow')
    {
        if(isset($_SESSION['followedId']))
        {
            DB_AddFollow();
            unset($_SESSION['followedId']);
            header('Location: Profile');
            exit();
        }
    }
    if($_GET['step'] === 'unfollow')
    {
        if(isset($_SESSION['followedId']))
        {
            DB_DeleteFollow();
            unset($_SESSION['followedId']);
            header("location: Profile");
            exit();
        }
    }
}
if(isset($_GET['retweetid']))
{
    DB_AddRetweet();
    header('location: Profile');
    exit();
}
if(!(isset($_SESSION['email'])))
{
    header('Location: /');
    exit();
}
if(isset($_POST['recherche']))
{
    //trouver des résultats : 
    $fetch = DB_GetUsersWithPostEmail();
    if($fetch)//si la recherche a reussie :
    {
        $fetch2 = DB_GetFollows($fetch);
        if($fetch2)
        {
            $AlreadyFollowed = false;
        }
        else{
            $AlreadyFollowed = true;
        }
        //la mettre en tableau :
        $recherche = [
            'prenom'=> $fetch['first_name'],
            'nom' => $fetch['last_name'],
            'email' => $_POST['recherche'],
            'id'    => $fetch['user_id']
        ];
        $_SESSION['followedId'] = $fetch['user_id'];
        if($_SESSION['followedId']===$_SESSION['id'])
        {
            $AlreadyFollowed = null;
        }
    }
    else{
        //le faire savoir
        $erreurs = "Aucun membre trouvé...";
    }
}
if(isset($_POST['NewTweet']))
{
    $erreurs = verifierTweet();
    if(!isset($erreurs))
    {
        //envoit à la base de données
        DB_AddTweet();
        header('location: Profile?success=1');
        exit();
    }
}
if(isset($_GET['success']))
{
    $succes = 'Tweet envoyé !';
}
$title = "Twittus - Profile";
require '../Vue/Profile.php';
?>