

<?php
require('dbcon.php');

$name     = $_POST['name'];
$desc     = $_POST['desc'];
$duration = $_POST['duration'];
$keywords = $_POST['keywords'];


$sql = "INSERT INTO course (cname, `desc`, duration, keywords) 
        VALUES (:cname, :desc, :duration, :keywords)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':cname'    => $name,
    ':desc'     => $desc,
    ':duration' => $duration,
    ':keywords' => $keywords
]);

header("Location: http://localhost/vanilla/viewCourse.php");
exit;
?>
