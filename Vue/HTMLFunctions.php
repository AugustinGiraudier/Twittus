<?php
function navlink(string $title, string $classAtribute = null): string
{
    return <<<HTML
    <li class="nav-item">
    <a class="nav-link {$classAtribute}" href="/">$title<span class="sr-only">(current)</span></a>
    </li>
HTML;
}
function ConnectionForm():string
{
  $address= '';
  if(isset($_GET['address']))
  {
  $address = $_GET['address'];
  }
    return <<<HTML
    <div class="col-6">
    <div class="form-group">
      <form action = ""  method="POST">
        <label for="exampleInputEmail1">Adresse Email :</label>
        <input type="email" name="Cemail" class="form-control mt-1 mb-2" value ="{$address}" placeholder="Entez votre e-mail" required>
        <label for="exampleInputEmail1">Mot de passe :</label>
        <input type="password" name="Cpassword" class="form-control mt-1 mb-2" placeholder="Entez votre mot de passe" required>
        <button type="submit" class="btn btn-primary mt-5 ml-5"> Je me connecte </button>
      </form>
    </div>
  </div>
HTML;
}
function InscriptionForm():string
{
    return <<< HTML
    <div class="col-6">
        <div class="form-group">
          <form action = ""  method="POST">
            <label for="exampleInputEmail1">Adresse Email :</label>
            <input type="email" name="Iemail" class="form-control mt-1 mb-2" placeholder="Entez votre e-mail" required>
            <label for="exampleInputEmail1">Prenom :</label>
            <input type="text" name="Iprenom" class="form-control mt-1 mb-2" placeholder="Entez votre prenom" required>
            <label for="exampleInputEmail1">Nom :</label>
            <input type="text" name="Inom" class="form-control mt-1 mb-2" placeholder="Entez votre nom" required>
            <label for="exampleInputEmail1">Mot de passe :</label>
            <input type="password" name="Ipassword" class="form-control mt-1 mb-2" placeholder="Entez votre mot de passe" required>
            <button type="submit" class="btn btn-primary mt-5 ml-5"> Je m'inscris </button>
          </form>
        </div>
      </div>
HTML;
}