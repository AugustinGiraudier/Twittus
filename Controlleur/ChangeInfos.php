<?php
use Twittus\Inscription;
$changeStep = null;
session_start();
$succes = null;

if(!(isset($_SESSION['email'])))
{
    header('Location: /');
    exit();
}
if(isset($_POST['NewPrenom']))
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `first_name` = :prenom WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'prenom'    => htmlentities($_POST['NewPrenom']),
        'id'        => htmlentities($_SESSION['id'])
    ]);
    $_SESSION['prenom'] = htmlentities($_POST['NewPrenom']);
    $succes = 'Modification effectuée avec succès !';
    unset($_GET['step']);
}
if(isset($_POST['NewNom']))
{
    $Pdo = Inscription::GetPdo();
    $query5 = $Pdo->prepare("UPDATE `users` SET `last_name` = :nom WHERE `users`.`user_id` = :id;");
    $query5->execute([
        'nom'    => htmlentities($_POST['NewNom']),
        'id'        => htmlentities($_SESSION['id'])
    ]);
    $_SESSION['nom'] = htmlentities($_POST['NewNom']);
    $succes = 'Modification effectuée avec succès !';
    unset($_GET['step']);
}
if(isset($_POST['NewEmail']))
{
    $continue = true;
    try{
        Inscription::VerifyEmail($_POST['NewEmail']);
    }
    catch(Exception $e)
    {
        $erreurs = $e->getMessage();
        $continue = false;
    }
    if($continue)
    {
        $Pdo = Inscription::GetPdo();
        $query5 = $Pdo->prepare("UPDATE `users` SET `e_mail` = :email WHERE `users`.`user_id` = :id;");
        $query5->execute([
            'email'    => htmlentities($_POST['NewEmail']),
            'id'        => htmlentities($_SESSION['id'])
        ]);
        $_SESSION['email'] = htmlentities($_POST['NewEmail']);
        $succes = 'Modification effectuée avec succès !';
    }
    unset($_GET['step']);
}
if(isset($_POST['NewMdp']))
{
    if(Inscription::VerifyPassword($_POST['NewMdp']))
    {
        $Pdo = Inscription::GetPdo();
        $query6 = $Pdo->prepare("SELECT users.pass as pass FROM users WHERE users.user_id = :id");
        $query6->execute([
            'id'  => $_SESSION['id']
        ]);
        $fetch = $query6->fetch();
        if($fetch)
        {
            if(password_verify($_POST['AncienMdp'],$fetch['pass']))
            {   
                $query5 = $Pdo->prepare("UPDATE `users` SET `pass` = :pass WHERE `users`.`user_id` = :id;");
                $query5->execute([
                    'pass'    => password_hash($_POST['NewMdp'],PASSWORD_DEFAULT, ['cost'=>12]),
                    'id'        => htmlentities($_SESSION['id'])
                ]);
                $succes = 'Modification effectuée avec succès !';
                unset($_GET['step']);;
            }
            else{$erreurs = "L'ancien mot de passe ne correspond pas";}
        }
        else{$erreurs = "problème de base de donnée...";}
    }
    else{$erreurs = 'Nouveau mot de passe non sécurisé (ajoutez des majuscules, chiffres et caractères particuliers...';}
}

if(isset($_GET['step']))
{
    switch($_GET['step'])
    {
    case 'changeNom':
        $changeStep = 'n';
        break;
    case 'changePrenom':
        $changeStep = 'p';
        break;
    case 'changeEmail':
        $changeStep = 'e';
        break;
    case 'changeMdp':
        $changeStep = 'm';
    }
    
}
?>