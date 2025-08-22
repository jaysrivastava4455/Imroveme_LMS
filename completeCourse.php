<?php
session_start();
require('dbcon.php');

if (!isset($_SESSION['userid']) || !isset($_GET['ucid'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['userid'];
$ucid = $_GET['ucid'];

$checkStmt = $pdo->prepare("SELECT * FROM user_course_status WHERE ucid = :ucid AND uid = :uid");
$checkStmt->execute([
    ':ucid' => $ucid,
    ':uid'  => $id
]);

$record = $checkStmt->fetch();

if (!$record) {
    
    header("Location: mycourse.php?error=invalid");
    exit;
}

// Update status to 1 (completed)
$updateStmt = $pdo->prepare("UPDATE user_course_status SET status = 1 WHERE ucid = :ucid");
$updateStmt->execute([':ucid' => $ucid]);


header("Location: viewCourseDetail.php?cid=" . $record['cid']);


exit;
?>
