<?php
require('dbcon.php');
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['userid'];

// Optional: Allow only instructors/admins to edit
$stmt = $pdo->prepare("SELECT roleid FROM user WHERE id = :id");
$stmt->execute([':id' => $uid]);
$user = $stmt->fetch();

if (!$user || ($user['roleid'] != 1 && $user['roleid'] != 2)) {
    header("Location: viewCourse.php");
    exit;
}

if (!isset($_GET['cid'])) {
    header("Location: viewCourse.php");
    exit;
}

$cid = $_GET['cid'];

$stmt = $pdo->prepare("SELECT * FROM course WHERE cid = :cid");
$stmt->execute([':cid' => $cid]);
$course = $stmt->fetch();

if (!$course) {
    header("Location: viewCourse.php?error=not_found");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cname    = $_POST['cname'];
    $desc     = $_POST['desc'];
    $duration = $_POST['duration'];
    $keywords = $_POST['keywords'];

    $stmt = $pdo->prepare("UPDATE course 
        SET cname = :cname, `desc` = :desc, duration = :duration, keywords = :keywords 
        WHERE cid = :cid");

    $stmt->execute([
        ':cname'    => $cname,
        ':desc'     => $desc,
        ':duration' => $duration,
        ':keywords' => $keywords,
        ':cid'      => $cid
    ]);

    header("Location: viewCourse.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Course - ImproveMe LMS</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="mystyles.css">
  <style>
    body { font-family: "Times New Roman", Georgia, Serif;}
    h2 { margin-top: 80px; }
    input, textarea { width: 100%; padding: 6px; margin-bottom: 12px; }
  </style>
</head>
<body>

<div class="w3-top">
  <div class="w3-bar w3-white w3-padding w3-card" style="letter-spacing:4px;">
    <a href="index.php" class="w3-bar-item w3-button">ImproveMe LMS</a>
    <div class="w3-right w3-hide-small">
      
      <?php if ($user['roleid'] == 1 || $user['roleid']  == 2) { ?>
        <a href="manageCourse.php" class="w3-bar-item w3-button">manage Course</a>
      <?php } ?>
      <?php if ($user['roleid'] == 1 ) { ?>
        <a href="manageUser.php" class="w3-bar-item w3-button">manage User</a>
      <?php } ?>
      <?php if ($user['roleid'] == 3) { ?>
      <a href="myCourse.php" class="w3-bar-item w3-button">Mycourse</a>
      <?php } ?>
      <?php if ($user['roleid'] == 3) { ?>
      <a href="viewCourse.php" class="w3-bar-item w3-button">view course</a>
      <?php } ?>
      <?php if ($user['roleid'] == 1 || $user['roleid']==2) { ?>
      <a href="report.php" class="w3-bar-item w3-button">report</a>
      <?php } ?>
       <a href="logout.php" class="w3-bar-item w3-button">Logout</a>
    </div>
  </div>
</div>
</div>

<div class="w3-content" style="max-width:1100px">
  <div class="w3-row w3-padding-64" id="about">
    <h2>Edit Course</h2>
    <form method="POST" action="editCourse.php?cid=<?= htmlspecialchars($course['cid']) ?>">
      <label>Course Name:</label><br>
      <input type="text" name="cname" value="<?= htmlspecialchars($course['cname']) ?>" required><br>

      <label>Description:</label><br>
      <textarea name="desc" rows="4" required><?= htmlspecialchars($course['desc']) ?></textarea><br>

      <label>Duration:</label><br>
      <input type="text" name="duration" value="<?= htmlspecialchars($course['duration']) ?>" required><br>

      <label>Keywords:</label><br>
      <input type="text" name="keywords" value="<?= htmlspecialchars($course['keywords']) ?>" required><br>

      <input type="submit" value="Update Course">
    </form>
  </div>
</div>

<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by 
    <a href="https://www.cognizant.com/" target="_blank" class="w3-hover-text-green">Cognizant</a>
  </p>
</footer>

</body>
</html>
