<?php
session_start();
if(isset($_SESSION['login'])){
session_destroy();
if($_SESSION['login']['role'] == 'visitor'){ header('Location: ../'); die(); }
if($_SESSION['login']['role'] == 'admin'){ header('Location: login.php'); die(); }

}else{
    header('Location: login.php');
}

?>