<?php
namespace Twittus;
use \Exception;
class user{

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function GetUserInfos()
    {
        $Pdo = Inscription::GetPdo();
        $query = $Pdo->prepare("SELECT users.user_id as id, users.first_name as prenom, users.last_name as nom, users.e_mail as email FROM users WHERE users.user_id = :id");
        $query->execute([
            'id'  => $this->id
        ]);
        if($fetch = $query->fetch())
        {
            return $fetch;
        }
        throw new Exception("Utilisateur inconnu...");
    }
}