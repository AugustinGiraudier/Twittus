<?php
use Twittus\Inscription;
require '../Model/ChangeInfos.php';
$changeStep = null;
$title = "Twittus - Modifier mes infos";
$succes = null;
session_start();

if(!(isset($_SESSION['email'])))
{
    header('Location: /');
    exit();
}
if(isset($_POST['NewPrenom']))
{
    DB_UpdatePrenom();
    $_SESSION['prenom'] = htmlentities($_POST['NewPrenom']);
    $succes = 'Modification effectuée avec succès !';
    unset($_GET['step']);
}
if(isset($_POST['NewNom']))
{
    DB_UpdateNom();
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
        DB_UpdateEmail();
        $_SESSION['email'] = htmlentities($_POST['NewEmail']);
        $succes = 'Modification effectuée avec succès !';
    }
    unset($_GET['step']);
}
if(isset($_POST['NewMdp']))
{
    if(Inscription::VerifyPassword($_POST['NewMdp']))
    {
        $fetch = DB_GetPass();
        if($fetch)
        {
            if(password_verify($_POST['AncienMdp'],$fetch['pass']))
            {   
                DB_UpdatePass();
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
require '../Vue/ChangeInfos.php';
?>

