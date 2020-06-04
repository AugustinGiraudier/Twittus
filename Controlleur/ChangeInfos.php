<?php
use Twittus\Inscription;
require '../Model/ChangeInfos.php';
$changeStep = null;
$title = "Twittus - Modifier mes infos";
$succes = null;
session_start();

//si l'utilisateur n'ets pas en session :
if(!(isset($_SESSION['email'])))
{   
    //redirection au home
    header('Location: /');
    exit();
}

//si un nouveau prenom est entré :
if(isset($_POST['NewPrenom']))
{
    try{    //vérification
        Inscription::VerifyName($_POST['NewPrenom'],"xxxxxxx");
        DB_UpdatePrenom();
        $_SESSION['prenom'] = htmlentities($_POST['NewPrenom']);
        $succes = 'Modification effectuée avec succès !';
    }
    catch (Exception $e)
    {$erreurs = "nouveau prénom trop court ou trop long (non compris entre 5 et 30)...";}
    unset($_GET['step']);
}

//si un nouveau nom est entré :
if(isset($_POST['NewNom']))
{
    try{    //vérification
        Inscription::VerifyName("xxxxxxx",$_POST['NewNom']);
        DB_UpdateNom();
        $_SESSION['nom'] = htmlentities($_POST['NewNom']);
        $succes = 'Modification effectuée avec succès !';
    }
    catch (Exception $e)
    {$erreurs = "nouveau Nom trop court ou trop long (non compris entre 5 et 30)...";}
    unset($_GET['step']);
}

//si un nouvel email est entré :
if(isset($_POST['NewEmail']))
{
    try{    //vérification
        Inscription::VerifyEmail($_POST['NewEmail']);
        DB_UpdateEmail();
        $_SESSION['email'] = htmlentities($_POST['NewEmail']);
        $succes = 'Modification effectuée avec succès !';
    }
    catch(Exception $e)
    {
        $erreurs = $e->getMessage();
    }
    unset($_GET['step']);
}

//si un nouveau mot de passe est entré :
if(isset($_POST['NewMdp']))
{
    try{
        //test du pass
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
    }
    catch(Exception $e)
    {$erreurs = 'Nouveau mot de passe non sécurisé (ajoutez des majuscules, chiffres et caractères particuliers...';}
}

//étape servant à l'affichage du champs de texte correspondant
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

