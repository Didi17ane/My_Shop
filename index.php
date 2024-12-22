<?php
session_start();
include_once("database.php");

$database = new Database();
$db = $database->connect();

if($_SESSION['username']){
    $session_active = true;
}

// Récupération des produits dans la base de données
if ($db) {
    $req = $db->prepare("SELECT * FROM products ORDER BY cod_prod DESC");
    $req->execute();
    $products = $req->fetchAll();
} else {
    $products = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4p889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" type="image/png" href="assets/images/Logo.png">
    <title>My Shop</title>

</head>
<body class="bg-white text-black">

    <header class="bg-blue-700 shadow-md sticky top-0 z-50">
        <nav class="container mx-auto flex items-center justify-between py-4 px-6 text-white">
            <a href="#" class="text-xl font-bold"><img src="/assets/images/Logo.png" alt=""></a>
            <ul class="flex space-x-4">
                <li><a class="hover:text-blue-300" href="index.php">Home</a></li>
                <li><a class="hover:text-blue-300" href="admin.php">Shop</a></li>
                <li><a class="hover:text-blue-300" href="#">Magazine</a></li>
            </ul>
            <div class="flex items-center space-x-4" onclick="return confirm('Êtes-vous sûr ?')">
                <?php if ($session_active){ ?>
                    <a href="Logout.php" class="hover:text-red-300 space-x-2"><img src="/assets/images/CartButton.png" alt="image" class="w-5 h-5"><span>Logout</span></a>
                    <?php }else { ?>
                    <a href="Sign-in.php" class="hover:text-blue-300 space-x-2"><img src="/assets/images/CartButton.png" alt="image" class="w-5 h-5"><span>Login</span></a>
                    <?php    } ?>
            </div>
        </nav>
    </header>

    <section class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold text-center text-black mb-6">Produits disponible</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-lg p-4 border border-blue-200">
                        <img src="/uploads/<?= htmlspecialchars($product['photo']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded-md">
                        <h2 class="text-lg font-semibold text-blue-700 mt-4"><?= htmlspecialchars($product['name']) ?></h2>
                        <p class="text-gray-600 mt-2"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="text-blue-500 font-bold mt-4">$<?= htmlspecialchars($product['price']) ?></p>
                        <div class="mt-4">
                        <?php if ($session_active){ ?>
                            <a href="Ajouter_produit.php" class="block text-center bg-blue-700 text-white py-2 px-4 rounded hover:bg-blue-600">Add to Cart</a>
                            <?php }else { ?>
                                <a href="Sign-in.php" class="block text-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-400">See Details</a>
                                <?php    } ?>
                            </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500">Pas de produits disponible.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-blue-700 text-white py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 My Shop. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>