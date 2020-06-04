<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <div class ="pt-1 sticky-top">
                <div class = "list-group-item bg-info rounded mt-4">
                    <div class="ml-4">
                        <div>
                            <h3 class = "font-weight-bold text-light ml-1"><?='Profile public :'?></h3>
                            <h4 class = "font-weight-bold ml-1"><?= htmlentities($user['prenom']) . " " . htmlentities($user['nom'])?></h4>
                            <li class = "badge badge-light"><?= htmlentities($user['email'])?></li>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col-6 list-group-item rounded mt-4">
            <div class="alert alert-info text-center"><?='Ses Derniers Tweets :'?></div>
            <?php if(isset($infos)):?>
                <div class="alert alert-light text-center"><?=$infos?></div>
            <?php endif?>
            <?php foreach($Tweets as $tweet):?>
                <div class = "shadow p-3 mb-5 bg-light rounded">
                    <li class = "badge font-weight-light"><?=htmlentities($tweet['publish_date'])?></li>
                    <p class = "mt-4"><?=htmlentities($tweet['content'])?></p>
                </div>
            <?php endforeach?>
        </div>
    </div>
</div>






<?php 
require "../Vue/Footer.php";
?>