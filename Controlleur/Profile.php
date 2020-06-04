<?php
session_start();
require '../Model/Profile.php';
const MAX_TWEET_LEN = 140;
$title = "Twittus - Profile";
$recherche = null;
$erreurs = null;
$infos = null;
$AlreadyFollowed = null;

//si l'utilisateur n'a pas de session ouverte :
if(!(isset($_SESSION['email'])))
{
    //redirigé au home
    header('Location: /');
    exit();
}

//enleve les emojis d'une chaine :
function remove_emoji($string) {

    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, ' ', $string);
    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, ' ', $clear_string);
    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, ' ', $clear_string);
    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, ' ', $clear_string);
    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, ' ', $clear_string);

    return $clear_string;
}


function verifierTweet()
 {
    remove_emoji($_POST['NewTweet']);
    trim($_POST['NewTweet']);
    if(strlen($_POST['NewTweet']) > MAX_TWEET_LEN || strlen($_POST['NewTweet']) < 1)
    {
        return 'Erreur, nombre de caractères non compris entre 1 et ' . MAX_TWEET_LEN . "...";
    }
    return null;
 }

 //renvoie les personnes suivies
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

 //recupere tous les tweets qui doivent etre affichés
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

 //verifie si le tweet appartient à l'utilisateur
 function verifyTweeter($Tweetid, $Userid)
 {
    $fetch = DB_GetTweetsSenders($Tweetid);
    if($fetch['sender_id'] !== $Userid)
    {
        return false;
    }
    return true;
 }

 //supprime le tweet avec son id
 function DelTweet($Tid)
 {
    DB_DeleteTweetsWithId($Tid);
    //supprime les retweets associés à ce tweet :
    DB_DeleteRetweetswithId($Tid);
 }

 //fonction permettant le tri des tweets par date
 $tweetSortByDate = function ($a,$b)
 {
    $ta = new DateTime($a['publish_date']);
    $tb = new DateTime($b['publish_date']);
    return ($ta > $tb) ? -1 : 1;
 };

 //si un tweet est référencé pour etre supprimé :
if(isset($_GET['delTweet']))
{   
    // si l'utilisateur courrant est bien le propriétaire du tweet :
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
//si aucun user suivit n'est trouvé :
if(!isset($followeds[1]))
{
    $infos = 'Vous ne suivez encore personne...';
}
$tweets = GetTweets($followeds);
//tri des tweets par date :
uasort($tweets, $tweetSortByDate);

//si aucun tweet n'a été récupéré :
if(!isset($tweets[0]))
{
    $infos2 = "Vous n'avez aucun tweet";
}

//en fonction de l'étape passée en url :
if(isset($_GET['step']))
{
    if($_GET['step'] === 'deconnexion')
    {
        session_destroy();
        header('Location: /');
        exit();
    }
    elseif($_GET['step'] === 'changeinfos')
    {
        header('Location: ChangeInfos');
        exit();
    }
    elseif($_GET['step'] === 'follow')
    {
        if(isset($_SESSION['followedId']))
        {
            DB_AddFollow();
            unset($_SESSION['followedId']);
            header('Location: Profile');
            exit();
        }
    }
    elseif($_GET['step'] === 'unfollow')
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

//si un id de retweet est passé par l'url :
if(isset($_GET['retweetid']))
{
    DB_AddRetweet();
    header('location: Profile');
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

//si "success" est present en parametre de l'url :
if(isset($_GET['success']))
{
    $succes = 'Tweet envoyé !';
}
require '../Vue/Profile.php';
?>