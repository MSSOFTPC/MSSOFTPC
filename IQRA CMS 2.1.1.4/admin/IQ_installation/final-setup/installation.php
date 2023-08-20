<?php


if(isset($_POST['IQ_final_setup'])){  
    global $conn;
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sitename = $_POST['site-name'];

    IQ_rewrite_default_tables();

    $conn->query("INSERT INTO `user`(`full_name`,`email`, `username`, `password`, `role` ) VALUES ('admin','".$email."','".$username."','".$password."','admin')");
    $conn->query("UPDATE `site_options` SET `option_value`='".$sitename."' WHERE option_name='title'") ;   
    session_start();
    $_SESSION['success_notice'] = 'Thanks for Choosing IQRA CMS';
    back('admin');

 }
