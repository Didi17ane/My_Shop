<?php
class Database {
    private $host = "localhost";
    private $dbname = "my_shop";
    private $username = "root";
    private $password = "";
    public $connexion;

    public function connect() {
        try {

            $this->connexion = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            error_log("[" . date("D, d M Y H:i:s") . "] " . $e->getMessage() . "\n", 3, "./error_log_file.log");
            echo("Erreur de connexion à la base de données.");
        }
        return $this->connexion;
    }
}

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function userExists($field, $value) {
        $query = "SELECT * FROM users WHERE $field = :value";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function register($username, $email, $password, $conf_pass) {
        if ($this->userExists("username", $username)) {
            return "Ce pseudo est déjà utilisé. Veuillez en choisir un autre !";
        }

        if ($this->userExists('email', $email)) {
            return "Cet email possède déjà un compte !";
        }

        if (strlen($password) < 6) {
            return "Le mot de passe doit contenir au moins 6 caractères.";
        }
        if ($password != $conf_pass){
            return "Le mot de passe ne correspond pas";
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $registerDate = date('Y-m-d H:i:s');

        $query = "INSERT INTO users (username, email, password, date_creat) VALUES (:username, :email, :pass, :register)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hashedPassword);
        $stmt->bindParam(':register', $registerDate);

        if ($stmt->execute()) {
            header('Location: ./Sign-in.php');
            exit();
        } else {
            return "Une erreur s'est produite lors de l'inscription.";
        }
    }
}

// Initialisation de la base de données et des classes
$database = new Database();
$db = $database->connect();
$user = new User($db);

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $conf_pass = trim($_POST['conf_password']);

    if (empty($username) && empty($email) && empty($password)) {
        $error = "Tous les champs sont obligatoires !";
    } else {
        $error = $user->register($username, $email, $password, $conf_pass);
    }

    if (isset($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" type="image/png" href="assets/images/Logo.png">
    <link rel="stylesheet" href="in1.css">
    <title>My_Shop</title>
</head>
<body>
    <div class="container"> 
        <div class="form-container sign-up-container">
            <form action="" method="POST">
                <h1>Créer un compte</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="social"><i class='bx bxl-google-plus'></i></a>
                    <a href="#" class="social"><i class='bx bxl-linkedin-square'></i></a>
                </div>
                <span>Ou utilisez votre email</span>
                <div class="infield">
                    <label for="username">Entrez votre pseudo</label>
                    <i class='bx bxs-user'></i>
                    <input type="text" name="username" placeholder="Pseudo" required/>
                </div>
                <div class="infield">
                    <label for="email">Entrez votre email</label>
                    <i class='bx bxs-envelope'></i>                
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="password">
                    <label for="password">Entrez votre mot de passe</label>
                    <i class='bx bxs-paste'></i>                
                    <input placeholder="Mot de passe" type="password" name="password" required><br>
                    <label for="password">Confirmer votre mot de passe</label>
                    <i class='bx bxs-paste'></i>                
                    <input placeholder="Mot de passe" type="password" name="conf_password" required>
                </div>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>
</body>