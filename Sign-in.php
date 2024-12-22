<?php
include_once("database.php");
$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
       
            try {
                // Recherche de l'utilisateur avec l'email
                $query = "SELECT * FROM users WHERE email = :email";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch();
                
                if ($user){
                    // Vérification du mot de passe
                    if (password_verify($password, $user['password'])) {
                       
                        // Connexion réussie
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['admin'] = $user['admin'];
                        echo "<script>alert('Connexion réussie !');</script>";
                        header('Location: admin.php');
                        exit();
                    } else {
                        echo "<script>alert('Mot de passe incorrect.');</script>";
                    }
                } else {
                    echo "<script>alert('Aucun compte trouvé avec cet email.');</script>";
                }
            } catch (PDOException $e) {
                error_log("[" . date("D, d M Y H:i:s") . "] " . $e->getMessage() . "\n", 3, "./error_log_file.log");
                echo("Erreur de connexion à la base de données.");
            }
    } else {
        echo "<script>alert('Tous les champs sont obligatoires.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="in1.css">
    <link rel="shortcut icon" type="image/png" href="assets/images/Logo.png">
    <title>My_Shop</title>
</head>
<body>
    <div class="container"> 
        <div class="form-container sign-in-container">
            <form action="" method="POST">
                <h1>Connexion</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="social"><i class='bx bxl-google-plus'></i></a>
                    <a href="#" class="social"><i class='bx bxl-linkedin-square'></i></a>
                </div>
                <span>Ou utilisez votre email</span>
                <div class="infield">
                    <label for="email">Email</label>
                    <i class='bx bxs-envelope'></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="password">
                    <label for="password">Mot de passe</label>
                    <i class='bx bxs-lock'></i>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <button type="submit">Se connecter</button>
                <a href="Sign-up.php">Créer un compte</a>
            </form>
        </div>
    </div>
</body>
</html>