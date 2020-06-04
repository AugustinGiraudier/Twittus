<?php 
require "../Vue/HTMLFunctions.php";
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title> <?= $title; ?> </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="shortcut icon" href="Public/LogoTwittus.png"/>
  </head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
      <img style ="width: 55px; height: 55px;" class = "mr-3 " src="Public/LogoTwittus.png" alt="">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav mr-auto">
            <?= navlink("Twittus", "active");?>
        </ul>
      </div>
    </nav>
</body>