<?php
    session_start();
    require_once("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My_Shop</title>
</head>
<body>
    <p><?php 
        echo "HELLO ".$_SESSION['username'];
    ?></p>
</body>
</html>