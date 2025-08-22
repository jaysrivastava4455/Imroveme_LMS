
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
<title>Darwin Dawkins Library</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="mystyles.css">
<style>
body {font-family: "Times New Roman", Georgia, Serif;}
h1, h2, h3, h4, h5, h6 {
  font-family: "Playfair Display";
  letter-spacing: 5px;
}
</style>
</head>
<body>

<!-- Navbar (sit on top) -->
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

<!-- Header -->
<header class="w3-display-container w3-content w3-wide" style="max-width:1600px;min-width:500px" id="home">

</header>

<!-- Page content -->
<div class="w3-content" style="max-width:1100px">
  <!-- About Section  -->
  <div class="w3-row w3-padding-64" id="about">
	</br>
	<form action="saveuser.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>
    <label for="name">User_Name:</label>
    <input type="text" id="name" name="username" required><br>

    <label for="pwd">Password:</label>
    <input type="text" id="pwd" name="password" required><br><br>
    <label for="email">eamil:</label>
    <input type="text" id="pwd" name="email" required><br><br>

    <label>Role:</label><br>
    <select name="role">
    
    <?php if ($user['roleid'] == 1): ?>
  <option value="1" selected>Admin</option>
<?php endif; ?>

      <option value="2">Instructor</option>
      <option value="3">student</option>
</select>

    <input type="submit" name="submit" value="Submit">
  </form>


  </div>
<!-- End page content -->
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by <a href="https://www.cognizant.com/" title="Cognizant" target="_blank" class="w3-hover-text-green">Cognizant	</a></p>
</footer>

</body>
</html>
