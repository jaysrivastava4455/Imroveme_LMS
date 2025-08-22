
<?php 
session_start(); 
require 'dbcon.php';

if (isset($_SESSION['userid'])) {
    $sql = "SELECT * FROM user WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute([':id' => $_SESSION['userid']]);
    $user = $statement->fetch();
    $name = $user['name'];
} else {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>ImproveMe LMS</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="mystyles.css">
  <style>
    body { font-family: "Times New Roman", Georgia, Serif; }
    h1, h2, h3, h4, h5, h6 {
      font-family: "Playfair Display";
      letter-spacing: 5px;
    }
    table, th, td {
      border: 1px solid #000;
      border-collapse: collapse;
      padding: 8px;
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

<!-- Page content -->
<div class="w3-content" style="max-width:1100px">
  <div class="w3-row w3-padding-64" id="about">     
    <p>Hi <?= htmlspecialchars($name) ?>, Welcome!</p>
  </div>

  <?php if ($user['roleid']  == 1 || $user['roleid']  == 2 || $user['roleid']==3) { ?>
    <div><strong>course display:</strong> here get all the course </div><br>

    <?php
    $sql = "SELECT * FROM course";
    $statement = $pdo->query($sql);
    $courses = $statement->fetchAll();

    echo "<table>
  <tr>
    <th>name</th>

    <th>duration</th>
    <th>keyword</th>";
if ($user['roleid'] != 3) {
    echo "<th>action</th>";
}
if ($user['roleid'] == 3) {
    echo "<th>enroll Action</th>";
}


echo "</tr>";

    foreach ($courses as $course) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($course['cname']) . "</td>";
      
      echo "<td>" . htmlspecialchars($course['duration']) . "</td>";
      echo "<td>" . htmlspecialchars($course['keywords']) . "</td>";
      if ($user['roleid'] != 3) { 
      echo "<td>
    <a href='editCourse.php?cid=" . $course['cid'] . "' title='edit'>edit</a>
    <a href='deleteCourse.php?cid=" . $course['cid'] . "' title='Delete' onclick=\"return confirm('Are you sure you want to delete this Course?');\">delete</a>
  </td>";
      }
      if ($user['roleid'] == 3) { 
        echo "<td>
          <a href='viewCourseDetail.php?cid=" . $course['cid']. "' title='view'>view</a>
        </td>";
    }
    
      echo "</tr>";
    }
    echo "</table>";
    ?>
  <?php } ?>
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by 
    <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant</a>
  </p>
</footer>

</body>
</html>
