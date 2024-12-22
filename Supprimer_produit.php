<?php
session_start();

// Vérifier si un ID est fourni
if(!isset($_GET['cod_prod']) || empty($_GET['cod_prod'])) {
    $_SESSION['erreur'] = "ID non fourni";
    header('Location: admin.php');
    exit();
}


include_once("database.php");
$database = new Database();
$db = $database->connect();

try {
    
    $id = strip_tags($_GET['cod_prod']);

   
    $sqlSelect = "SELECT photo FROM products WHERE cod_prod = :id";
    $querySelect = $db->prepare($sqlSelect);
    $querySelect->bindValue(':id', $id, PDO::PARAM_INT);
    $querySelect->execute();
    $produit = $querySelect->fetch();

    if($produit) {
        
        if(!empty($produit['photo'])) {
            $cheminPhoto = 'uploads/' . $produit['photo'];
            if(file_exists($cheminPhoto)) {
                unlink($cheminPhoto);
            }
        }

        
        $sql = "DELETE FROM products WHERE cod_prod = :id";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $_SESSION['message'] = "Produit supprimé avec succès";
    } else {
        $_SESSION['erreur'] = "Ce produit n'existe pas";
    }

} catch(PDOException $e) {
    $_SESSION['erreur'] = "Erreur lors de la suppression : " . $e->getMessage();
}


header('Location: admin.php');
exit();
?>