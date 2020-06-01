<?php
namespace Twittus;
use PDO;
use \Exception;

class Inscription
{
    private static $Pdo;
    private $email = null;
    private $prenom = null;
    private $nom = null;
    private $password = null;
    const MINIMAL_PASSWORD_COMPLEXITY = 75;

    public function __construct(string $email, string $prenom, string $nom, string $password)
    {
        self::$Pdo = new PDO("mysql:host=127.0.0.1;dbname=twitter","root");
        $this->email = $email;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->password = $password;
    }
    public static function GetPdo()
    {
        if(self::$Pdo == null)
        {
            self::$Pdo = new PDO("mysql:host=127.0.0.1;dbname=twitter","root");
        }
        return self::$Pdo;
    }
    public function VerfifyInfos():bool
    {
        if($this->VerifyEmail($this->email) && $this->VerifyName($this->prenom, $this->nom) && self::VerifyPassword($this->password))
        {
            return true;
        }
        throw new Exception("Une erreur est survenue durant l'inscription...");
    }

    public Function SetNewUser()
    {
        $Hash = password_hash($this->password,PASSWORD_DEFAULT,['cost' => 12]);
        $Pdo = Inscription::GetPdo();
        $query = $Pdo->prepare("INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `pass`, `e_mail`) VALUES (NULL, :prenom, :nom, :pass, :email);");
        $query->execute([
        'prenom' => $this->prenom,
        'nom'    => $this->nom,
        'pass'   => $Hash,
        'email'  => $this->email
    ]);
    }

    private function VerifyEmail(string $email):bool
    {
        $Pdo = $this->GetPdo();
        $query = $Pdo->prepare("SELECT users.user_id FROM users WHERE users.e_mail = :email");
        $query->execute([
            'email'  => $email
        ]);
        if($query->fetch())
        {
            throw new Exception("Email déja éxistant...");
        }
        return true;
    }

    private function VerifyName(string $prenom, string $nom):bool
    {
        if(strlen($prenom)<=30 && strlen($nom)<=30)
        {
            return true;
        }
        throw new Exception("Nom ou prénom trop long (plus de 30 caractères)...");
    }

    public static function VerifyPassword(string $password):bool
    {
        $longueur = strlen($password);
        $point = (int)0;
        $point_min = 0;
        $point_maj = 0;
        $point_chiffre = 0;
        $point_caracteres = 0;
        for($i = 0; $i < $longueur; $i++) 	{
            $lettre = $password[$i];
            if ($lettre>='a' && $lettre<='z'){
                $point += 1;
                $point_min = 1;
            }
            else if ($lettre>='A' && $lettre <='Z'){
                $point += 2;
                $point_maj = 2;
            }
            else if ($lettre>='0' && $lettre<='9'){
                $point = $point + 3;
                $point_chiffre = 3;
            }
            else {
                $point = $point + 5;
                $point_caracteres = 5;
            }
        }
        $final = ($point / $longueur) * ($point_min + $point_maj + $point_chiffre + $point_caracteres) * $longueur;
        if($final>= self::MINIMAL_PASSWORD_COMPLEXITY)
        {
            return true;
        }
        throw new Exception("Mot de pass trop fragile (ajoutez des chiffres, des majuscules ou des signes particuliers...");
    }
}
