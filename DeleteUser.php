<?php
session_start();
include_once("authentificat.php");
$delt = new User();

if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['erreur'] = "ID non fourni";
    header('Location: admin.php');
    exit();
}

if(isset($_GET['id']) && isset($_GET['req']))
{
    if($_GET['req']=="delete"){
        $delt->setId(strip_tags($_GET['id']));
        $res=$delt->Search();
        echo 'Bon';
        if($res){
            echo 'Bon';
            $delt->DeleteUser();
        }
    }
header('Location: admin.php');
exit();
}