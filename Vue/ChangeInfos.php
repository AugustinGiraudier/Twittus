<head>
  <title> <?= $title; ?> </title>
</head>
<div class="container-fluid">
    <div class="row">
        <!-- partie gauche -->
        <div class="col-3">
            <div class ="pt-1 sticky-top">
                <div class = "list-group-item active rounded mt-4">
                    <div class="ml-4">
                        <!-- infos du compte -->
                        <div>
                            <h4 class = "font-weight-bold ml-1"><?=htmlentities($_SESSION['prenom']) . ' ' . htmlentities($_SESSION['nom'])?></h4>
                            <li class = "badge badge-light"><?=htmlentities($_SESSION['email'])?></li>
                        </div>
                        <div>
                            <li class = "badge"><a href ="Profile" class = "font-weight-light text-white">Retour au profile</a></li>
                        </div>
                        <li class = "badge"><a href ="Profile?step=deconnexion" class = "font-weight-light text-white">déconnexion</a></li>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
        <!-- partie droite -->
        <div class="col-6 list-group-item rounded mt-4">
            <?php if(isset($erreurs)):?>
                <div class="alert alert-danger text-center"><?=$erreurs?></div>
            <?php endif ?>
            <?php if(isset($succes)):?>
                <div class="alert alert-success text-center"><?=$succes?></div>
            <?php endif ?>
            <!-- formulaire de changement d'infos -->

            <div class = "shadow p-3 mb-5 bg-light rounded col">
                <p>
                    <!-- partie prenom -->
                    <ui>Prenom : <label class = 'badge badge-primary' for=""><?=htmlentities($_SESSION['prenom'])?></label></ui>
                    <?php if($changeStep === 'p'): ?>
                        <form action="" class = "form" method="POST">
                            <input name = 'NewPrenom' type="text" maxlength="30" placeholder = 'Nouveau prénom'>
                            <button class = 'btn-sm btn-primary'>Modifier</button>
                        </form>
                    <?php else: ?>
                        <a class= "ml-3 badge" href="ChangeInfos?step=changePrenom">modifier</a>
                    <?php endif ?>
                </p>
                <p>
                    <!-- partie nom -->
                    <ui>Nom : <label class = 'badge badge-primary' for=""><?=htmlentities($_SESSION['nom'])?></label></ui>
                    <?php if($changeStep === 'n'): ?>
                        <form action="" class = "form" method="POST">
                            <input name = 'NewNom' type="text" maxlength="30" placeholder = 'Nouveau nom'>
                            <button class = 'btn-sm btn-primary'>Modifier</button>
                        </form>
                    <?php else: ?>
                        <a class= "ml-3 badge" href="ChangeInfos?step=changeNom">modifier</a>
                    <?php endif ?>
                </p>
                <p>
                    <!-- partie email -->
                    <ui>Adresse E-mail : <label class = 'badge badge-primary' for=""><?=htmlentities($_SESSION['email'])?></label></ui>
                    <?php if($changeStep === 'e'): ?>
                        <form action="" class = "form" method="POST">
                            <input name = 'NewEmail' type="text" maxlength="30" placeholder = 'Nouvel email'>
                            <button class = 'btn-sm btn-primary'>Modifier</button>
                        </form>
                    <?php else: ?>
                        <a class= "ml-3 badge" href="ChangeInfos?step=changeEmail">modifier</a>
                    <?php endif ?>
                </p>
                <p>
                    <!-- partie mot de passe -->
                    <?php if($changeStep === 'm'): ?>
                        <form action="" class = "form" method="POST">
                            <input name = 'AncienMdp' type="password" placeholder = 'Ancien pass' required>
                            <input name = 'NewMdp' type="password" placeholder = 'Nouveau pass' required>
                            <button class = 'btn-sm btn-primary'>Modifier</button>
                        </form>
                    <?php else: ?>
                        <a class= "ml-3 badge" href="ChangeInfos?step=changeMdp">modifier le mot de passe</a>
                    <?php endif ?>
                </p>
            </div>

        </div>
    </div>
</div>