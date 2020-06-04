<?php
use Twittus\Inscription;
function DB_GetUserWithId($id)
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT users.first_name as prenom, users.last_name as nom, users.e_mail as email FROM users WHERE users.user_id = :id");
    $query->execute([
        'id' => $id
    ]);
    $fetch = $query->fetch();
    return $fetch;
}
function DB_GetTweetsWithUserId($id)
{
    $Pdo = Inscription::GetPdo();
    $query = $Pdo->prepare("SELECT tweets.content, tweets.publish_date FROM tweets WHERE tweets.sender_id = :id ORDER BY tweets.publish_date DESC LIMIT 3");
    $query->execute([
        'id' => $id
    ]);
    $fetch = $query->fetchAll();
    return $fetch;
}
?>