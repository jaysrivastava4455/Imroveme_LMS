<?php
require('dbcon.php');

if (isset($_GET['cid'])) {
    $id = $_GET['cid'];

    $stmt = $pdo->prepare("DELETE FROM course WHERE cid = :cid");
    $stmt->execute([':cid' => $id]);
}

header("Location: manageCourse.php"); 
exit;
?>
