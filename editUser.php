<?php
require('dbcon.php');
session_start();

if (!isset($_GET['id'])) {
    // No user ID provided
    header("Location: viewUser.php");
    exit;
}
if (isset($_SESSION['userid'])) {
  $sql = "SELECT * FROM user WHERE id = :id";
  $statement = $pdo->prepare($sql);
  $statement->execute([':id' => $_SESSION['userid']]);
  $user1 = $statement->fetch();
  $name1 = $user['name'];
} else {
  header('Location: login.php');
  exit;
}

$id = $_GET['id'];

// Fetch user from database
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    // User not found
    header("Location: viewUser.php?error=user_not_found");
    exit;
}

// Redirect if user is admin (cannot edit)
if ($user['roleid'] == 1) {
    header("Location: viewUser.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $user['password']; // Preserving existing password
    $roleid   = $_POST['roleid'];

    $stmt = $pdo->prepare("UPDATE user 
        SET name = :name, username = :username, password = :password, email = :email, roleid = :roleid 
        WHERE id = :id");

    $stmt->execute([
        ':name'     => $name,
        ':username' => $username,
        ':password' => $password,
        ':email'    => $email,
        ':roleid'   => $roleid,
        ':id'       => $id
    ]);

    header("Location: viewUser.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>ImproveMe LMS</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="mystyles.css">
  <style>
    body { font-family: "Times New Roman", Georgia, Serif;  }
    h2 { margin-top: 80px; }
    input, textarea { width: 100%; padding: 6px; margin-bottom: 12px; }
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

<!-- Main Content -->
<div class="w3-content" style="max-width:1100px">
  <div class="w3-row w3-padding-64" id="about">
    <h2>Edit User</h2>
    <form method="POST" action="editUser.php?id=<?= htmlspecialchars($user['id']) ?>">
      <label for="name">Name:</label><br>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" ><br><br>

      <label for="username">Username:</label><br>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

      <label for="email">Email:</label><br>
      <input type="text" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

      <label for="roleid">Role:</label><br>
      <select name="roleid" required>
        <option value="">-- Select Role --</option>
        <option value="2" <?= $user['roleid'] == 2 ? 'selected' : '' ?>>Instructor</option>
        <option value="3" <?= $user['roleid'] == 3 ? 'selected' : '' ?>>Student</option>
      </select><br><br>

      <input type="submit" name="submit" value="Submit">
    </form>
  </div>
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by 
    <a href="https://www.cognizant.com/" target="_blank" class="w3-hover-text-green">Cognizant</a>
  </p>
</footer>

</body>
</html>
