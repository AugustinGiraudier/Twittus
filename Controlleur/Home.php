<?php
use Twittus\Connexion;
use Twittus\Inscription;
session_start();
$title = "Twittus - Home";
$error = null;
$step_Inscription = null;
$step_connexion = null;

//si un utilisateur est en session :
if(isset($_SESSION['email']))
{
  //redirection vers son profile
  header('Location: Profile');
  exit();
}

//active ou désactive les boutons de connection/inscription
if(isset($_GET['step']))
{
  if($_GET['step']==='connexion')
  {
    $step_connexion = true;
  }
  else{
    $step_Inscription = true;
  }
}
else{
  $step_Inscription = true;
}

//si des infos de connection sont entrées :
if(isset($_POST['Cemail']))
{
  try{
    //test des infos
    $connexion = new Connexion($_POST['Cemail'], $_POST['Cpassword']);
    $connexion->VerifyConnexionInformations();
  }
  catch(Exception $e)
  {
    //gestion des erreurs
    $error = $e->getMessage();
  }
}

//si des infos d'inscription sont entrées :
if(isset($_POST['Iemail']))
{
  try
  {
    //test des infos :
    $Inscription = new Inscription($_POST['Iemail'], $_POST['Iprenom'], $_POST['Inom'], $_POST['Ipassword']);
    if($Inscription->VerfifyInfos())
    {
      $Inscription->SetNewUser();
      header("Location: ?step=connexion&success=1");
      exit();
    }
  }
  catch(Exception $e)
  {
    //gestion des erreurs
    $error = $e->getMessage();
  }
}
require "../Vue/Home.php";
?>