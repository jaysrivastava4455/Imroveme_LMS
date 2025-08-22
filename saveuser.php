<?php
require('dbcon.php');

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$roleid = $_POST['role'];

//die("pop");

$sql = "INSERT INTO user (name, username, password, email, roleid) 
        VALUES (:name, :username, :password, :email, :roleid)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $name,
    ':username' => $username,
    ':password' => $password,
    ':email' => $email,
    ':roleid' => $roleid
]);



    header("Location: http://localhost/vanilla/index.php");
    exit;


?>
