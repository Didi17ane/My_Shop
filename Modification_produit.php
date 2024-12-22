<?php
session_start();
if(!isset($_GET['cod_prod']) || empty($_GET['cod_prod'])) {
    header('Location: admin.php');
    exit();
}


include_once("database.php");
$database = new Database();
$db = $database->connect();

$cod_prod = strip_tags($_GET['cod_prod']);
$sql = 'SELECT * FROM products WHERE cod_prod = :cod_prod';
$query = $db->prepare($sql);
$query->bindParam(':cod_prod', $cod_prod, PDO::PARAM_INT);
$query->execute();
$product = $query->fetch();

if(!$product) {
    $_SESSION['erreur'] = "Produit ou catégorie inexistant";
    header('Location: AllProducts.php');
    exit();
}

if($_POST){
    if(isset($_POST['name']) && !empty($_POST['name'])
    && isset($_POST['price']) && !empty($_POST['price'])
    && isset($_POST['description']) && !empty($_POST['description'])
    && isset($_POST['select']) && !empty($_POST['select'])){

        $name = strip_tags($_POST['name']);
        $price = strip_tags($_POST['price']);
        $description = strip_tags($_POST['description']);
        
      
        $photo = $product['photo']; 
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if(in_array(strtolower($filetype), $allowed)){
                $newname = uniqid() . '.' . $filetype;
                move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $newname);
                $photo = $newname;
            }
        }
            $sql = 'UPDATE products SET name=:name, photo=:photo, price=:prix, description=:description, category_id=:categ WHERE cod_prod=:id';
            $query = $db->prepare($sql);
            $query->bindParam(':id', $cod_prod, PDO::PARAM_INT);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':photo', $photo, PDO::PARAM_STR);
            $query->bindParam(':prix', $price, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':categ', $_POST['select'], PDO::PARAM_STR);
            
            try {
                if($query->execute()){
                    $_SESSION['message'] = "Produit modifié avec succès";
                    header('Location: AllProducts.php');
                    exit();
                }
            
            } catch(PDOException $e) {
                $_SESSION['erreur'] = "Erreur lors de la modification : " . $e->getMessage();
            }
        
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My_Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/png" href="assets/images/Logo.png">
</head>
<body class="bg-gray-100">
<div class="text-center pb-10">
   <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
   Show navigation
   </button>
</div>

<!-- drawer component -->
<div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-navigation-label">
    <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">My_Shop</h5>
    <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Close menu</span>
    </button>
  <div class="py-4 overflow-y-auto">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="admin.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="index.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" viewBox="0 0 20 18" aria-hidden="true" stroke-width="4" stroke="currentColor" fill="none">  
                    <path stroke="none" d="M0 0h24v24H0z"/>  <polyline points="5 12 3 12 12 3 21 12 19 12" />  <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /> 
                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Home</span>
            </a>
        </li>
         <?php if($_SESSION['admin']){ ?>
         <li>
            <a href="AllUsers.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
            </a>
         </li>
         <?php } ?>
         <li>
            <a href="Categories.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Categories</span>
               </a>
         </li>
         <li>
            <a href="AllProducts.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
            </a>
         </li>
         <li>
            <a href="Logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h11m0 0-4-4m4 4-4 4m-5 3H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3"/>
                </svg>
               <span class="flex-1 ms-3 whitespace-nowrap" onclick="return confirm('Êtes-vous sûr ?')">Log out</span>
            </a>
         </li>
      </ul>
   </div>
</div>
<div class="mx-auto sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700"> 
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-4 text-center">Modifier un produit</h1>

                <?php if(isset($_SESSION['erreur'])): ?>
                    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Nom du produit</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" class="w-full border rounded px-4 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="photo" class="block text-gray-700 font-bold mb-2">Photo du produit</label>
                        <input type="file" name="photo" id="photo" class="w-full border rounded px-4 py-2">
                        <p class="text-gray-500 mt-2">Image actuelle : <img src="uploads/<?= htmlspecialchars($product['photo'])?>" alt="Photo" class="h-16 mt-2"></p>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 font-bold mb-2">Prix</label>
                        <input type="text" name="price" id="price" value="<?= htmlspecialchars($product['price']) ?>" class="w-full border rounded px-4 py-2" required>
                        <label for="category_id" class="block text-gray-700 font-bold mb-2">Catégorie</label>
                        <?php  include_once("input_categ.php")?>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" id="description" class="w-full border rounded px-4 py-2" required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Modifier</button>
                </form>
            </div>
    </div>
        
    </div>
</body>
</html>

