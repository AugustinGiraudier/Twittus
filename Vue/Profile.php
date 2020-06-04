<?php
$title = "Twittus - Profile";
$recherche = null;
$erreurs = null;
$infos = null;
$AlreadyFollowed = null;
require "../Vue/Header.php";
require_once "../Vendor/Autoload.php";
use Twittus\Inscription;
?>


<?php
const MAX_TWEET_LEN = 140;
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
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT follows.followed_id FROM follows WHERE follows.follower_id = :id");
    $query->execute([
        'id'=>$_SESSION['id']
    ]);
    $fetch = $query->fetchAll();
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
    $Pdo = Inscription::GetPdo();
    foreach($followed as $id)
    {
        ///////////tweets//////////
        $query = $Pdo->prepare("SELECT users.first_name, users.last_name, users.e_mail, tweets.tweet_id, tweets.content, tweets.publish_date FROM tweets INNER JOIN users ON tweets.sender_id = users.user_id WHERE tweets.sender_id = :senderid ORDER BY tweets.publish_date DESC LIMIT 5");        
        $query->execute([
            'senderid' => $id
        ]);
        $fetch = $query->fetchAll();
        if($fetch)
        {
            $tweets[]=$fetch;
        }
        ///////////retweets//////////
        $query2 = $Pdo->prepare("SELECT u2.first_name as retweeter_first_name, u2.last_name as retweeter_last_name, u2.e_mail as retweeter_e_mail, u1.first_name, u1.last_name, u1.e_mail, tweets.tweet_id, tweets.content, retweets.publish_date 
        FROM retweets 
        INNER JOIN tweets ON retweets.retweeted_tweet_id = tweets.tweet_id 
        INNER JOIN users as u2 ON retweets.retweet_user_id = u2.user_id 
        INNER JOIN users as u1 ON tweets.sender_id = u1.user_id
        WHERE retweets.retweet_user_id = :userid LIMIT 5");        
        $query2->execute([
            'userid' => $id
        ]);
        $fetch2 = $query2->fetchAll();
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
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT tweets.sender_id FROM tweets WHERE tweets.tweet_id = :id");
    $query->execute([
        'id'    =>  $Tid
    ]);
    $fetch = $query->fetch();
    if($fetch['sender_id'] !== $Uid)
    {
        return false;
    }
    return true;
 }
 $tweetSortByDate = function ($a,$b)
 {
    $ta = new DateTime($a['publish_date']);
    $tb = new DateTime($b['publish_date']);
    return ($ta > $tb) ? -1 : 1;
 }
?>


<?php
session_start();
if(isset($_GET['delTweet']))
{
    if(VerifyTweeter($_GET['delTweet'],$_SESSION['id']))
    {
        //////suppr tweet
        $succes = 'tweet supr';
    }
    else{
        $erreurs = "supr pas celui d'un autre wesh";
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
            $Pdo = Inscription::GetPdo();
            $querry = $Pdo->prepare("INSERT INTO `follows` (`follow_id`, `follower_id`, `followed_id`) VALUES (NULL, :idfollower, :idfollowed);");
            $querry->execute([
            'idfollower'    => $_SESSION['id'],
            'idfollowed'    => $_SESSION['followedId']
        ]);
        unset($_SESSION['followedId']);
        header('Location: Profile');
        }
    }
    if($_GET['step'] === 'unfollow')
    {
        if(isset($_SESSION['followedId']))
        {
            $Pdo = Inscription::GetPdo();
            $querry = $Pdo->prepare("DELETE FROM `follows` WHERE follows.follower_id = :idfollower AND follows.followed_id = :idfollowed;");
            $querry->execute([
            'idfollower'    => $_SESSION['id'],
            'idfollowed'    => $_SESSION['followedId']
        ]);
        unset($_SESSION['followedId']);
        header("location: Profile");
        }
    }
}
if(isset($_GET['retweetid']))
{
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("INSERT INTO `retweets` (`retweet_id`, `retweet_user_id`, `retweeted_tweet_id`, `publish_date`) VALUES (NULL, :userid, :tweetid, CURRENT_TIME());");
    $querry->execute([
        'userid' => $_SESSION['id'],
        'tweetid' => $_GET['retweetid']
    ]);
    header('location: Profile');
}
if(!(isset($_SESSION['email'])))
{
    header('Location: /');
    exit();
}
if(isset($_POST['recherche']))
{
    //trouver des résultats : 
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("SELECT users.first_name, users.last_name, users.user_id FROM users WHERE users.e_mail = :email");
    $querry->execute([
        'email' => htmlentities($_POST['recherche'])
    ]);
    $fetch = $querry->fetch();
    if($fetch)//si la recherche a reussie :
    {
        $query2 = $Pdo->prepare("SELECT follows.follow_id FROM follows WHERE follows.follower_id = :followerId AND follows.followed_id = :followedId");
        $query2->execute([
            'followerId'    => $_SESSION['id'],
            'followedId'    => $fetch['user_id']
        ]);
        $fetch2 = $query2->fetch();
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
        $succes = 'Tweet envoyé !';
        $Pdo = Inscription::GetPdo();
        $query4 = $Pdo->prepare("INSERT INTO `tweets` (`tweet_id`, `sender_id`, `content`, `publish_date`) VALUES (NULL, :id, :content, CURRENT_TIME());");
        $test = $query4->execute([
            'id'        => $_SESSION['id'],
            'content'   => $_POST['NewTweet']
        ]);
    }
}

?>




<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <div class ="pt-1 sticky-top">
                <div class = "list-group-item active rounded mt-4">
                    <div class="ml-4">
                        <div>
                            <h4 class = "font-weight-bold ml-1"><?=htmlentities($_SESSION['prenom']) . ' ' . htmlentities($_SESSION['nom'])?></h4>
                            <li class = "badge badge-light"><?=htmlentities($_SESSION['email'])?></li>
                        </div>
                        <div>
                            <li class = "badge"><a href ="Profile?step=changeinfos" class = "font-weight-light text-white">modifier mes informations</a></li>
                        </div>
                        <li class = "badge"><a href ="Profile?step=deconnexion" class = "font-weight-light text-white">déconnexion</a></li>
                    </div>
                </div><!--  2eme rectangle (recherche) -->
                <div class = "list-group-item active rounded mt-4">
                    <h6>Rechercher un utilisateur :</h6>
                    <form action="" method="POST">
                        <input type="email" name="recherche" class = "mt-3 form-control" placeholder="EX : John.Doe@Twittus.com">
                        <button type='submit' class = "mt-3 btn btn-outline-light">Rechercher</button>
                    </form>
                    <?php if(isset($recherche)): ?>
                        <h6 class='mt-3'>Résultat de recherche :</h6>
                        <div class = 'list-group-item bg-light rounded mt-3'>
                            <h5><li class = "badge badge-primary"><?=htmlentities($recherche['prenom']) . ' ' . htmlentities($recherche['nom'])?></li></h5>
                            <h5><li class = "badge font-weight-light text-dark"><?=htmlentities($recherche['email'])?></li></h5>
                            <div class = "row mt-3">
                                <div  class = "ml-1 col-8" >
                                    <a target="_blank" href="PublicProfile?id=<?=htmlentities($recherche['id'])?>">Profile Public</a>
                                </div>
                                <?php if(isset($AlreadyFollowed)):?>
                                    <?php if($AlreadyFollowed):?>
                                        <a  href= "Profile?step=follow"><button class = 'col-12 btn btn-outline-success'>Follow</button></a>
                                    <?php else:?>
                                        <a  href= "Profile?step=unfollow"><button class = 'col-12 btn btn-outline-danger'>UnFollow</button></a>
                                    <?php endif?>
                                <?php endif?>
                                
                            </div>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col-6 list-group-item rounded mt-4">
            <!--  massages erreurs / succes -->
            <?php if(isset($erreurs)):?>
                <div class="alert alert-danger text-center"><?=$erreurs?></div>
            <?php endif ?>
            <?php if(isset($succes)):?>
                <div class="alert alert-success text-center"><?=$succes?></div>
            <?php endif ?>
            <?php if(isset($infos)):?>
                <div class="alert alert-info text-center"><?=$infos?></div>
            <?php endif ?>
            <!-- formulaire -->
            <div class = "shadow p-3 mb-5 bg-light rounded">
                <h7 class = "">Ecrire un nouveau tweet : (limité à 140 caractères)</h7>
                <form action="" method="POST">
                    <input type="text" name="NewTweet" maxlength="140" class="form-control mt-4 mb-2" placeholder="Entez votre pensée du jour...">
                    <div class= "text-right">
                    <button class = "mt-3 btn btn-primary ">Envoyer</button>
                    </div>
                </form>
            </div>
            <?php if(isset($infos2)):?>
                <div class="alert alert-light text-center"><?=$infos2?></div>
            <?php endif ?>
            <!-- Tweets -->
            <?php if(isset($tweets)):?>
                <?php foreach($tweets as $tweet):?>
                    <div class = "shadow p-3 mb-5 bg-light rounded">
                        <?php if(isset($tweet['retweeter_first_name'])):?>
                            <div class = "alert alert-warning">
                                <div>
                                    <li class = "badge">retweeté par <?=htmlentities($tweet['retweeter_first_name']) . " " . htmlentities($tweet['retweeter_last_name'])?></li>
                                </div>
                                <li class = "badge font-weight-light"><?=htmlentities($tweet['retweeter_e_mail'])?></li>
                            </div>
                        <?php endif?>
                        <div <?=isset($tweet['retweeter_first_name']) ? "class= \"ml-5\"" : ""?>>
                            <div>
                                <li class = "badge badge-primary"><?=htmlentities($tweet['first_name']) . " " . htmlentities($tweet['last_name'])?></li>
                                <li class = "badge badge_light"><?=htmlentities($tweet['e_mail'])?></li>
                                <li class = "badge font-weight-light"><?=htmlentities($tweet['publish_date'])?></li>
                            </div>
                            <p class = "mt-4"><?=htmlentities($tweet['content'])?></p>
                            <a href = "Profile?retweetid=<?=$tweet['tweet_id']?>"><li class = "badge">retweeter</li></a>
                            <a href = "Profile?delTweet=<?=$tweet['tweet_id']?>"><li class = "badge text-danger">supprimer</li></a>
                        </div>
                    </div>
                <?php endforeach?>
            <?php endif ?>
        </div>
    </div>
</div>


<?php 
require "../Vue/Footer.php";
?>