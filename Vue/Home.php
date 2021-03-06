<head>
  <title> <?= $title; ?> </title>
</head>
<div class="container mt-5 border rounded">
  <?php if(isset($_GET['success'])):?> 
    <div class="alert alert-success container mt-4 text-center">Vous avez été enregistré avec succès !</div>
  <?php endif?>
  <?php if($error):?> 
    <div class="alert alert-danger container mt-4 text-center"><?=$error?></div> 
  <?php endif?>
  <div class="row justify-content-md-center mt-5">
    <div class="col text-center mx-5">
      <!-- boutons de switch "inscription/connection" -->
      <a class="list-group-item rounded <?= $step_Inscription ? 'active' : ''?>" href="?step=inscription">Inscription</a>
      <a class="list-group-item border rounded mt-4 <?= $step_connexion ? 'active' : ''?>" href="?step=connexion">Connexion</a>
    </div>
    <!-- formulaire -->
    <?php 
    if($step_connexion):
      //écrit le formulaire de connexion
      echo ConnectionForm();
    else:
      //écrit le formulaire d'inscription
      echo InscriptionForm();
    endif 
    ?>
  </div>
</div>

