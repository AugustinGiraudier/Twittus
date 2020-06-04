<head>
  <title> <?= $title; ?> </title>
</head>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <div class ="pt-1 sticky-top">
                <div class = "list-group-item active rounded mt-4">
                    <div class="ml-4">
                        <div>
                            <h4 class = "font-weight-bold ml-1"><?=htmlentities($_SESSION['prenom']) . ' ' . htmlentities($_SESSION['nom'])?></h4>
                            <li class = "badge badge-light"><?=htmlentities($_SESSION['email'])?></li>
                        </div>
                        <div>
                            <li class = "badge"><a href ="Profile?step=changeinfos" class = "font-weight-light text-white">modifier mes informations</a></li>
                        </div>
                        <li class = "badge"><a href ="Profile?step=deconnexion" class = "font-weight-light text-white">déconnexion</a></li>
                    </div>
                </div><!--  2eme rectangle (recherche) -->
                <div class = "list-group-item active rounded mt-4">
                    <h6>Rechercher un utilisateur :</h6>
                    <form action="" method="POST">
                        <input type="email" name="recherche" class = "mt-3 form-control" placeholder="EX : mickey@disney.com">
                        <button type='submit' class = "mt-3 btn btn-outline-light">Rechercher</button>
                    </form>
                    <?php if(isset($recherche)): ?>
                        <h6 class='mt-3'>Résultat de recherche :</h6>
                        <div class = 'list-group-item bg-light rounded mt-3'>
                            <h5><li class = "badge badge-primary"><?=htmlentities($recherche['prenom']) . ' ' . htmlentities($recherche['nom'])?></li></h5>
                            <h5><li class = "badge font-weight-light text-dark"><?=htmlentities($recherche['email'])?></li></h5>
                            <div class = "row mt-3">
                                <div  class = "ml-1 col-8" >
                                    <a target="_blank" href="PublicProfile?id=<?=htmlentities($recherche['id'])?>">Profile Public</a>
                                </div>
                                <?php if(isset($AlreadyFollowed)):?>
                                    <?php if($AlreadyFollowed):?>
                                        <a  href= "Profile?step=follow"><button class = 'col-12 btn btn-outline-success'>Follow</button></a>
                                    <?php else:?>
                                        <a  href= "Profile?step=unfollow"><button class = 'col-12 btn btn-outline-danger'>UnFollow</button></a>
                                    <?php endif?>
                                <?php endif?>
                                
                            </div>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col-6 list-group-item rounded mt-4">
            <!--  messages erreurs / succes -->
            <?php if(isset($erreurs)):?>
                <div class="alert alert-danger text-center"><?=$erreurs?></div>
            <?php endif ?>
            <?php if(isset($succes)):?>
                <div class="alert alert-success text-center"><?=$succes?></div>
            <?php endif ?>
            <?php if(isset($infos)):?>
                <div class="alert alert-info text-center"><?=$infos?></div>
            <?php endif ?>
            <!-- formulaire -->
            <div class = "shadow p-3 mb-5 bg-light rounded">
                <h7 class = "">Ecrire un nouveau tweet : (limité à 140 caractères)</h7>
                <form action="" method="POST">
                    <input type="text" name="NewTweet" maxlength="140" class="form-control mt-4 mb-2" placeholder="Entez votre pensée du jour...">
                    <div class= "text-right">
                    <button class = "mt-3 btn btn-primary ">Envoyer</button>
                    </div>
                </form>
            </div>
            <?php if(isset($infos2)):?>
                <div class="alert alert-light text-center"><?=$infos2?></div>
            <?php endif ?>
            <!-- Tweets -->
            <?php if(isset($tweets)):?>
                <?php foreach($tweets as $tweet):?>
                    <div class = "shadow p-3 mb-5 bg-light rounded">
                        <?php if(isset($tweet['retweeter_first_name'])):?>
                            <div class = "alert alert-warning">
                                <div>
                                    <li class = "badge">retweeté par <?=htmlentities($tweet['retweeter_first_name']) . " " . htmlentities($tweet['retweeter_last_name'])?></li>
                                </div>
                                <li class = "badge font-weight-light"><?=htmlentities($tweet['retweeter_e_mail'])?></li>
                            </div>
                        <?php endif?>
                        <div <?=isset($tweet['retweeter_first_name']) ? "class= \"ml-5\"" : ""?>>
                            <div>
                                <li class = "badge badge-primary"><?=htmlentities($tweet['first_name']) . " " . htmlentities($tweet['last_name'])?></li>
                                <li class = "badge badge_light"><?=htmlentities($tweet['e_mail'])?></li>
                                <li class = "badge font-weight-light"><?=htmlentities($tweet['publish_date'])?></li>
                            </div>
                            <p class = "mt-4"><?=htmlentities($tweet['content'])?></p>
                            <?php if($_SESSION['email'] === $tweet['e_mail']):?>
                                <a href = "Profile?delTweet=<?=$tweet['tweet_id']?>"><li class = "badge text-danger">supprimer</li></a>
                            <?php else: ?>    
                                <a href = "Profile?retweetid=<?=$tweet['tweet_id']?>"><li class = "badge">retweeter</li></a>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach?>
            <?php endif ?>
        </div>
    </div>
</div>