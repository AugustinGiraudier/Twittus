<?php
use Twittus\Inscription;
function DB_GetFollowed()   //retourne la liste des id des personnes suivies par l'utilisateur courrant
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT follows.followed_id FROM follows WHERE follows.follower_id = :id");
    $query->execute([
        'id'=>$_SESSION['id']
    ]);
    $fetch = $query->fetchAll();
    return $fetch;
}
function DB_GetTweets($id)  //retourne les tweets envoyés par l'utilisateur ayant l'id passé en paramètre
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT users.first_name, users.last_name, users.e_mail, tweets.tweet_id, tweets.content, tweets.publish_date FROM tweets INNER JOIN users ON tweets.sender_id = users.user_id WHERE tweets.sender_id = :senderid ORDER BY tweets.publish_date DESC LIMIT 5");        
    $query->execute([
        'senderid' => $id
    ]);
    $fetch = $query->fetchAll();
    return $fetch;
}
function DB_GetReTweets($id)    //retourne les retweets envoyés par l'utilisateur ayant l'id passé en paramètre
{
    $Pdo = Inscription::GetPdo();
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
    return $fetch2;
}
function DB_GetTweetsSenders($Tid)  //retourne l'id de l'utilisateur ayant émis le tweet dont l'id est passé en paramètre
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT tweets.sender_id FROM tweets WHERE tweets.tweet_id = :id");
    $query->execute([
        'id'    =>  $Tid
    ]);
    $fetch = $query->fetch();
    return $fetch;
}
function DB_DeleteTweetsWithId($Tid)    //supprime un tweet avec son id
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("DELETE FROM `tweets` WHERE `tweets`.`tweet_id` = :id");
    $query->execute([
        'id'    =>  $Tid
    ]);
}
function DB_DeleteRetweetswithId($Tid)  //supprime les reweets relatifs au tweet dont l'id est passé en parmaètre
{
    $Pdo = Inscription::GetPdo();
    $query2 = $Pdo->prepare("DELETE FROM `retweets` WHERE `retweets`.`retweeted_tweet_id` = :id");
    $query2->execute([
        'id'    =>  $Tid
    ]);
}
function DB_AddFollow() //ajoute le follow en cours
{
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("INSERT INTO `follows` (`follow_id`, `follower_id`, `followed_id`) VALUES (NULL, :idfollower, :idfollowed);");
    $querry->execute([
        'idfollower'    => $_SESSION['id'],
        'idfollowed'    => $_SESSION['followedId']
    ]);
}
function DB_DeleteFollow()  //supprime le follow en cours
{
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("DELETE FROM `follows` WHERE follows.follower_id = :idfollower AND follows.followed_id = :idfollowed;");
    $querry->execute([
        'idfollower'    => $_SESSION['id'],
        'idfollowed'    => $_SESSION['followedId']
    ]);
}
function DB_AddRetweet()    //ajoute le retweet en cours
{
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("INSERT INTO `retweets` (`retweet_id`, `retweet_user_id`, `retweeted_tweet_id`, `publish_date`) VALUES (NULL, :userid, :tweetid, CURRENT_TIME());");
    $querry->execute([
        'userid' => $_SESSION['id'],
        'tweetid' => $_GET['retweetid']
    ]);
}
function DB_GetUsersWithPostEmail() //retourne les infos utilisateurs avec un email
{
    $Pdo = Inscription::GetPdo();
    $querry = $Pdo->prepare("SELECT users.first_name, users.last_name, users.user_id FROM users WHERE users.e_mail = :email");
    $querry->execute([
        'email' => htmlentities($_POST['recherche'])
    ]);
    $fetch = $querry->fetch();
    return $fetch;
}
function DB_GetFollows($fetch)  //retourne l'id de follow entre l'utilisateur en cours et celui passé en paramètre (s'il existe)
{
    $Pdo = Inscription::GetPdo();
    $query2 = $Pdo->prepare("SELECT follows.follow_id FROM follows WHERE follows.follower_id = :followerId AND follows.followed_id = :followedId");
    $query2->execute([
        'followerId'    => $_SESSION['id'],
        'followedId'    => $fetch['user_id']
    ]);
    $fetch2 = $query2->fetch();
    return $fetch2;
}
function DB_AddTweet()  //ajoute le tweet en cours a la base de donnée
{
    $Pdo = Inscription::GetPdo();
    $query4 = $Pdo->prepare("INSERT INTO `tweets` (`tweet_id`, `sender_id`, `content`, `publish_date`) VALUES (NULL, :id, :content, CURRENT_TIME());");
    $test = $query4->execute([
        'id'        => $_SESSION['id'],
        'content'   => $_POST['NewTweet']
    ]);
}
?>