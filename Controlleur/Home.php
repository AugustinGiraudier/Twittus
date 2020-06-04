<?php
use Twittus\Connexion;
use Twittus\Inscription;
session_start();
$title = "Twittus - Home";
$error = null;
$step_Inscription = null;
$step_connexion = null;
if(isset($_SESSION['email']))
{
  header('Location: Profile');
  exit();
}
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

 if(isset($_POST['Cemail']))
 {
  try{
    $connexion = new Connexion($_POST['Cemail'], $_POST['Cpassword']);
    $connexion->VerifyConnexionInformations();
  }
  catch(Exception $e)
  {
    $error = $e->getMessage();
  }
 }

 if(isset($_POST['Iemail']))
 {
  try{
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
    $error = $e->getMessage();
  }
}
require "../Vue/Home.php";
?>