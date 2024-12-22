<?php
require_once("database.php");
$database = new Database();
$conn = $database->connect();
// define("ERROR_LOG_FILE", "errors.log");

class User{
    private $id;
    public $username;
    public $email;
    public $password;
    public $admin;
    

    public function setId($id)
    {
        $this->id=$id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username=$username;
    }
    public function getUsername()
    {
        return $this->username;
    }


    public function setEmail($email)
    {
        $this->email=$email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password=$password;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setAdmin($admin)
    {
        $this->admin=$admin;
    }
    public function getAdmin()
    {
        return $this->admin;
    }


    // public function error( $error ) 
    // {
    //     file_put_contents(ERROR_LOG_FILE, $error, FILE_APPEND);
    // }
    public function AllUsers($pag1, $limit)
    {
        global $conn;
        try{
            
            $rq=$conn->prepare("SELECT * from users");
            $rq->execute();
            $result=$rq->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch(Exception $e)
        {
            // error($e->getMessage()."\n");
            $_SESSION['erreur'] = "Erreur lors de la recherche";
            return $e->getMessage();
        }

    }
    public function CountU()
    {
        $query = "SELECT count(*) as nb_user FROM users";
        $s = $conn->prepare($query);
        $s->execute();
        $resul=$s->fetch();
        $nb_user = (int) $resul['nb_user'];
        return $nb_user;
    }
    public function ShowUser()
    {
        global $conn;
        try{
            
            $rq=$conn->prepare("SELECT * from users where id= :id");
            $rq->bindParam(':id', $this->getId());
            $rq->execute();
            $result=$rq->fetchAll();
            return $result;
        }catch(Exception $e)
        {
            // error($e->getMessage()."\n");
            $_SESSION['erreur'] = "Erreur lors de la recherche";
            return $e->getMessage();
        }

    }
    public function Search()
    {
        global $conn;
        try
        {
            $rq=$conn->prepare("SELECT * from users where id = :id");
            $rq->bindParam(':id', $this->getId());
            $rq->execute();
            $result=$rq->fetch();
            return $result;
        }catch(Exception $e)
        {
            // error($e->getMessage()."\n");
            $_SESSION['erreur'] = "Erreur lors de la recherche";
            return $e->getMessage();
        }
        
    }
    public function EditUser()
    {
        global $conn;
        try{
            $rq=$conn->prepare("UPDATE users set `username`=:usename, `email`=:mail, `password`=:pass, `admin`=:statut where `id`=:id");
            $rq->bindParam(':id', $this->getId());
            $rq->bindParam(':usename', $this->getUsername());
            $rq->bindParam(':mail', $this->getEmail());
            $rq->bindParam(':pass', $this->getPassword());
            $rq->bindParam(':statut', $this->getAdmin());
        
            if($rq->execute()){
                $_SESSION['message'] = "Utilisateur modifié avec succès";
                header('Location: AllUsers.php');
                exit();
            }
        }catch(Exception $e)
        {
            // error($e->getMessage()."\n");
            $_SESSION['erreur'] = "Erreur lors de la modification";      
            return $e->getMessage();
        }
    }
    public function DeleteUser()
    {
        global $conn;
        try{ 
            $rq=$conn->prepare("DELETE from users where id=:id");
            $rq->bindParam(':id', $this->getId());
            if($rq->execute()){
                $_SESSION['message'] = "Utilisateur supprimé avec succès";
            } else {
                $_SESSION['erreur'] = "Cet utilisateur n'existe pas";
            }

        }catch(Exception $e)
        {
            // error($e->getMessage()."\n");
            $_SESSION['erreur'] = "Erreur lors de la suppression";
            return $e->getMessage();
            
        }
    }









}