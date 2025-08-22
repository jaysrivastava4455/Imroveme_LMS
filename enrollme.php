<?php
session_start();
require('dbcon.php');

// Ensure session and course ID exist
if (!isset($_SESSION['userid'], $_GET['cid'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['userid'];
$cid = $_GET['cid'];

// Get user details
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $uid]);
$user = $stmt->fetch();

if (!$user || $user['roleid'] != 3) {
    header('Location: login.php');
    exit;
}

// Check if already enrolled
$stmt = $pdo->prepare("SELECT * FROM user_course_status WHERE uid = :uid AND cid = :cid");
$stmt->execute([
    ':uid' => $uid,
    ':cid' => $cid
]);

if ($stmt->fetch()) {
    header("Location: mycourse.php");
    exit;
}

// Insert enrollment record
$stmt = $pdo->prepare("INSERT INTO user_course_status (uid, cid) VALUES (:uid, :cid)");
$stmt->execute([
    ':uid' => $uid,
    ':cid' => $cid
]);

header("Location: viewCourseDetail.php?cid=" . $_GET['cid']);

exit;
?>
