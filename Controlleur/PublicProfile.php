<?php 
require_once "vendor/autoload.php";
use Twittus\Inscription;
$infos = null;
function FindUserWithId(int $id)
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT users.first_name as prenom, users.last_name as nom, users.e_mail as email FROM users WHERE users.user_id = :id");
    $query->execute([
        'id' => $id
    ]);
    $fetch = $query->fetch();
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
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT tweets.content, tweets.publish_date FROM tweets WHERE tweets.sender_id = :id ORDER BY tweets.publish_date DESC LIMIT 3");
    $query->execute([
        'id' => $id
    ]);
    $fetch = $query->fetchAll();
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
?>