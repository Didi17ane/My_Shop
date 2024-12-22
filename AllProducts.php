<?php
session_start();
        
include_once("database.php");
$database = new Database();
$db = $database->connect();

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $pageAc = (int) strip_tags($_GET['page']); 
} else{
    $pageAc = 1;
}

// $limit = 2;
$query = "SELECT count(*) as nb_prod FROM products";
$s = $db->prepare($query);
$s->execute();
$resul=$s->fetch();
$nb_prod = (int) $resul['nb_prod'];

$limit=5;
$pages= ceil($nb_prod/$limit);
$page1= ($pageAc * $limit) - $limit;


if($_GET['recherche'])
{
    $cod_prod=strip_tags($_GET['recherche']);
    $sql = "SELECT * FROM products where cod_prod = :cod_prod";
    $query = $db->prepare($sql);
    $query->bindParam(':cod_prod', $cod_prod, PDO::PARAM_INT);
    $query->execute();
    $rech = $query->fetchAll(PDO::FETCH_ASSOC);
    
   if($rech)
   {
      $products=$rech;
   }else{
      $_SESSION['erreur'] = "Ce produit n'existe pas";
   }
}else{
    $sql = "SELECT * FROM products ORDER BY cod_prod ASC LIMIT :page1, :lim";
    $q = $db->prepare($sql);
    $q->bindParam(':page1', $page1, PDO::PARAM_INT);
    $q->bindParam(':lim', $limit, PDO::PARAM_INT);
    $q->execute();
    $products = $q->fetchAll(PDO::FETCH_ASSOC);
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

<body>
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
    <div class="border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 sm:auto mx-100%">
                    <h1 class="text-3xl font-bold mb-3 text-center">Liste des produits</h1>

                    <?php if(isset($_SESSION['message'])): ?>
                        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                            <?= $_SESSION['message'] ?>
                            <?php unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['erreur'])): ?>
                        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                            <?= $_SESSION['erreur'] ?>
                            <?php unset($_SESSION['erreur']); ?>
                        </div>
                    <?php endif; ?>

                    <form class="m-2 shadow-md h-10 flex space-x-4">
                        <input name="recherche" class="p-2 w-full" type="text" placeholder="Rechercher...">
                        <div><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded"><a href="Ajouter_produit.php">NewProduct</a></button></div>
                    </form>

                    <table class="w-full bg-white text-center">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Nom</th>
                                <th class="px-4 py-2 hidden sm:flex">Photo</th>
                                <th class="px-4 py-2">Prix</th>
                                <th class="px-4 py-2">Catégorie</th>
                                <th class="px-4 py-2 hidden sm:flex">Date d'Ajout</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr class="border-b">
                                    <td class="px-4 py-2"><?= htmlspecialchars($product['cod_prod']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($product['name']) ?></td>
                                    <td class="px-4 py-2 hidden sm:flex">
                                        <?php if($product['photo']): ?>
                                            <img src="uploads/<?= htmlspecialchars($product['photo']) ?>" alt="Photo" class="h-16">
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($product['price']) ?> €</td>
                                    <?php 
                                         $sql = "SELECT name FROM categories where id = :id_cat";
                                         $quer = $db->prepare($sql);
                                         $quer->bindParam(':id_cat', $product['category_id'], PDO::PARAM_INT);
                                         $quer->execute();
                                         $cat = $quer->fetch();
                                    ?>
                                    <td class="px-4 py-2"><?= htmlspecialchars($cat['name']) ?></td>
                                   <td class="px-4 py-2 hidden sm:flex"><?= htmlspecialchars($product['date_ajout']) ?></td>
                                    <td class="px-4 py-2">
                                        <a href="Modification_produit.php?cod_prod=<?= $product['cod_prod'] ?>" class="text-blue-500 hover:underline">Modifier</a> |
                                        <a href="Supprimer_produit.php?cod_prod=<?= $product['cod_prod'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="flex">
                       
                        <div class="mt-8 flex space-x-5">
                           <?php for($page = 1; $page <= $pages; $page++) { ?>
                                 <div class="<?= ($pageAc == $page) ? "min-w-9 rounded-full bg-slate-800 py-2 px-3.5 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" : "min-w-9 rounded-full border border-slate-300 py-2 px-3.5 text-center text-sm transition-all shadow-sm hover:shadow-lg text-slate-600 hover:text-white hover:bg-slate-800 hover:border-slate-800 focus:text-white focus:bg-slate-800 focus:border-slate-800 active:border-slate-800 active:text-white active:bg-slate-800 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" ?>">
                                    <a href="?page=<?= $page ?>"><?= $page ?></a>
                                 </div>
                           <?php }?>
                        </div>
                     </div>
                
     </div>
</div>
</body>
</html>