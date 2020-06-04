<?php
namespace Twittus;
use PDO;
use \Exception;

class Inscription
{
    /*--------------------------------------------Données Membres------------------------------------------------------*/ 
    private static $Pdo = null;
    private $email = null;
    private $prenom = null;
    private $nom = null;
    private $password = null;
    const MINIMAL_PASSWORD_COMPLEXITY = 75;

    /*-----------------------------------------------Fonctions---------------------------------------------------------*/

    //constructeur
    public function __construct(string $email, string $prenom, string $nom, string $password)
    {
        self::InitPdo();
        $this->email = $email;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->password = $password;
    }

    //retourne une instance static de PDO
    public static function GetPdo()
    {
        if(self::$Pdo == null)
        {
            self::InitPdo();
        }
        return self::$Pdo;
    }

    //vérifie le nom, prenom, email et mot de passe relativement à la base de donnée et renvoit true 
    public function VerfifyInfos():bool
    {
        if($this->VerifyEmail($this->email) && $this->VerifyName($this->prenom, $this->nom) && self::VerifyPassword($this->password))
        {
            return true;
        }
        throw new Exception("Une erreur est survenue durant l'inscription...");
    }

    //envoie les données utilisateur à la base de donnée
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

    //renvoie true si l'email est unique dans la base 
    public static function VerifyEmail(string $email):bool
    {
        $Pdo = self::GetPdo();
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

    //retourne true si le pass est assez sécurisé
    public static function VerifyPassword(string $password):bool
    {
        //système d'indice de sécurité du pass
        $longueur = strlen($password);
        $point = (int)0;
        $point_min = 0;
        $point_maj = 0;
        $point_chiffre = 0;
        $point_caracteres = 0;
        //des points sont ajoutés pour :
        for($i = 0; $i < $longueur; $i++) 	{
            $lettre = $password[$i];
            if ($lettre>='a' && $lettre<='z'){ //chaque minuscule (1)
                $point += 1;
                $point_min = 1;
            }
            else if ($lettre>='A' && $lettre <='Z'){ //chaque majuscule (2)
                $point += 2;
                $point_maj = 2;
            }
            else if ($lettre>='0' && $lettre<='9'){ //chaque chiffe (3)
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

    //initialise PDO grace aux variables d'environnement
    private static function InitPdo()
    {
        $Host = getenv('MYSQL_ADDON_HOST');
        $DB = getenv('MYSQL_ADDON_DB');
        $DBUser = getenv('MYSQL_ADDON_USER');
        $DBPass = getenv('MYSQL_ADDON_PASSWORD');
        $DBPort = getenv('MYSQL_ADDON_PORT');
        $DBInfos = "mysql:host=" . $Host . ":" . $DBPort . ";dbname=" . $DB;
        self::$Pdo = new PDO($DBInfos,$DBUser,$DBPass);
    }

    //renvoie true si le prenom et le nom sont trop longs ou trop courts
    private function VerifyName(string $prenom, string $nom):bool
    {
        if(strlen($prenom)<=30 && strlen($nom)<=30 && strlen($prenom)>=5 && strlen($nom)>=5)
        {
            return true;
        }
        throw new Exception("Nom ou prénom trop court ou trop long (non compris entre 5 et 30)...");
    }
}
