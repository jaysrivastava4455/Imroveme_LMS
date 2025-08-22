<?php 
session_start(); 
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['userid'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $uid]);
$user = $stmt->fetch();
$name = $user['name'];

// Validate course ID
if (!isset($_GET['cid'])) {
    echo "No course selected.";
    exit;
}

$cid = $_GET['cid'];

// Fetch course details
$stmt = $pdo->prepare("SELECT * FROM course WHERE cid = :cid");
$stmt->execute([':cid' => $cid]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
}

// Fetch enrollment record if user is student
$enrolled = null;
if ($user['roleid'] == 3) {
    $stmt = $pdo->prepare("SELECT * FROM user_course_status WHERE uid = :uid AND cid = :cid");
    $stmt->execute([
        ':uid' => $uid,
        ':cid' => $cid
    ]);
    $enrolled = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Course Detail - ImproveMe LMS</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="mystyles.css">
  <style>
    body { font-family: "Times New Roman", Georgia, Serif;  }
    h1, h2, h3, h4, h5, h6 {
      font-family: "Playfair Display";
      letter-spacing: 5px;
    }
    .detail-row { margin: 30px; }
    .label { font-weight: bold; display: inline-block; width: 120px; }
  </style>
</head>
<body>

<!-- Navbar (synced with index.php) -->
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

<h2>Course Details</h2>

<div class="detail-row"><span class="label">Course Name:</span> <?= htmlspecialchars($course['cname']) ?></div>
<div class="detail-row"><span class="label">Description:</span> <?= htmlspecialchars($course['desc']) ?></div>
<div class="detail-row"><span class="label">Duration:</span> <?= htmlspecialchars($course['duration']) ?></div>
<div class="detail-row"><span class="label">Keywords:</span> <?= htmlspecialchars($course['keywords']) ?></div>

<?php if ($user['roleid'] == 3): ?>
  <div class="detail-row">
    <?php if (!$enrolled): ?>
      <a href="enrollme.php?cid=<?= $course['cid'] ?>" class="w3-button w3-green">Enroll Me</a>
    <?php else: ?>
      <strong>Status:</strong> Enrolled<br><br>
      <?php if ($enrolled['status'] == 0): ?>
        <a href="completeCourse.php?ucid=<?= $enrolled['ucid'] ?>" class="w3-button w3-orange">Complete Course</a>
        <div class="detail-row">Completion Status: Pending</div>
      <?php else: ?>
        <div class="detail-row">Completion Status: Completed</div>
        <a href="certificate.php?cid=<?= $course['cid'] ?>" class="w3-button w3-blue">Download Certificate</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<footer class="w3-center w3-light-grey w3-padding-32" style="margin-top: 50px;">
  <p>Powered by 
    <a href="https://www.cognizant.com/" target="_blank" class="w3-hover-text-green">Cognizant</a>
  </p>
</footer>

</body>
</html>
