<?php
namespace Twittus;
use \Exception;
class Connexion
{
    /*--------------------------------------------Données Membres------------------------------------------------------*/ 

    private $email = null;
    private $password = null;

    /*-----------------------------------------------Fonctions---------------------------------------------------------*/

    //constructeur
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    //renvoie true si les infos de connexion sont correctes
    public function VerifyConnexionInformations():bool
    {
        try{
            if($this->DoEmailExist()
            && password_verify($this->password, $this->GetBddPassword()))
            {
                $Pdo = Inscription::GetPdo();
                $query = $Pdo->prepare("SELECT users.user_id as id, users.first_name as prenom, users.last_name as nom FROM users WHERE users.e_mail = :email");
                $query->execute([
                    'email' => $this->email
                ]);
                $fetch = $query->fetch();
                //les infos utilisateur sont mises en session
                $_SESSION = [
                    'email' => $this->email,
                    'nom'   => $fetch['nom'],
                    'prenom' => $fetch['prenom'],
                    'id'    => $fetch['id']
                ];
                header("location: Profile");
                exit();
                return true;
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        throw new Exception("Mot de passe incorrect...");
    }

    //verifie aupres de la bdd si l'email est associe à un compte 
    private function DoEmailExist():bool
    {
        $Pdo = Inscription::GetPdo();
        $query = $Pdo->prepare("SELECT users.user_id FROM users WHERE users.e_mail = :email");
        $query->execute([
            'email'  => $this->email
        ]);
        if($fetch = $query->fetch())
        {
            return true;
        }
        throw new Exception("Email inéxistant dans la base de donnée...");
    }

    //recupere le mot de passe crypté associé au compte
    private function GetBddPassword()
    {
        $Pdo = Inscription::GetPdo();
        $query = $Pdo->prepare("SELECT users.pass FROM users WHERE users.e_mail = :email");
        $query->execute([
            'email'  => $this->email
        ]);
        if($fetch = $query->fetch())
        {
            return $fetch['pass'];
        }
        throw new Exception("Erreur de mot de passe dans la base de donnée...");
    }

}