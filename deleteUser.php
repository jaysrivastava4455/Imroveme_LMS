<?php
require('dbcon.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
    $stmt->execute([':id'=>$id]);
}

header("Location: manageUser.php"); 
exit;
?>
