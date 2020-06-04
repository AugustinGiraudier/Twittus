<?php
use Twittus\Inscription;
function DB_UpdatePrenom()  //change le prenom par celui en POST
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `first_name` = :prenom WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'prenom'    => htmlentities($_POST['NewPrenom']),
        'id'        => htmlentities($_SESSION['id'])
    ]);
}
function DB_UpdateNom() //change le nom par celui en POST
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `last_name` = :nom WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'nom'    => htmlentities($_POST['NewNom']),
        'id'        => htmlentities($_SESSION['id'])
    ]);
}
function DB_UpdateEmail() //change l'email par celui en POST
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `e_mail` = :email WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'email'    => htmlentities($_POST['NewEmail']),
        'id'        => htmlentities($_SESSION['id'])
    ]);
}
function DB_GetPass()   //retourne le mot de passe crypté actuel
{
    $Pdo = Inscription::GetPdo();
    $query6 = $Pdo->prepare("SELECT users.pass as pass FROM users WHERE users.user_id = :id");
    $query6->execute([
        'id'  => $_SESSION['id']
    ]);
    $fetch = $query6->fetch();
    return $fetch;
}
function DB_UpdatePass()    //change le mot de passe par celui en POST
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `pass` = :pass WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'pass'    => password_hash($_POST['NewMdp'],PASSWORD_DEFAULT, ['cost'=>12]),
        'id'        => htmlentities($_SESSION['id'])
    ]);
}
?>