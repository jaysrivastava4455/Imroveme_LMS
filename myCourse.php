<?php
session_start();
require('dbcon.php');

// Ensure the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['userid'];

// Fetch user details (including roleid)
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $uid]);
$user = $stmt->fetch();

// Fetch user's enrolled courses with completion status and ucid
$stmt = $pdo->prepare("
    SELECT 
        course.cname, 
        course.`desc`, 
        course.duration, 
        course.keywords, 
        user_course_status.status, 
        user_course_status.cid,
        user_course_status.ucid
    FROM course
    INNER JOIN user_course_status 
        ON course.cid = user_course_status.cid
    WHERE user_course_status.uid = :uid
");
$stmt->execute([':uid' => $uid]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>ImproveMe LMS</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="mystyles.css">
  <style>
    body { font-family: "Times New Roman", Georgia, Serif; margin: 20px; }
    h2 { margin-top: 80px; }
    table, th, td {
      border: 1px solid #000;
      border-collapse: collapse;
      padding: 8px;
    }
    th {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
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

<h2>My Courses</h2>

<?php if (count($courses) > 0): ?>
  <table>
    <tr>
      <th>Course Name</th>
      <th>Description</th>
      <th>Duration</th>
      <th>Keywords</th>
      <th>Status</th>
      <th>certificate</th>
    </tr>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><?= htmlspecialchars($course['cname']) ?></td>
        <td><?= htmlspecialchars($course['desc']) ?></td>
        <td><?= htmlspecialchars($course['duration']) ?></td>
        <td><?= htmlspecialchars($course['keywords']) ?></td>
        <td>
          <?php if ($course['status'] == 0): ?>
            <a href="completeCourse.php?ucid=<?= $course['ucid'] ?>" class="w3-bar-item">Complete Course</a>
          <?php else: ?>
            Completed
          <?php endif; ?>
        </td>
        <td>
          <?php if ($course['status'] == 0): ?>
            completion pending
          <?php else: ?>
            <a href="certificate.php" class="w3-bar-item">certificate</a>
            
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>You have not enrolled in any courses yet.</p>
<?php endif; ?>

<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by 
    <a href="https://www.cognizant.com/" target="_blank" class="w3-hover-text-green">Cognizant</a>
  </p>
</footer>

</body>
</html>
