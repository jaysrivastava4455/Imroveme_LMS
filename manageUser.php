<?php 
session_start(); 
require 'dbcon.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$sql = "SELECT * FROM user WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute([':id' => $_SESSION['userid']]);
$user = $statement->fetch();
$name = $user['name'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users - ImproveMe LMS</title>
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
    button {
      background-color: green;
      margin-left: 5px;
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
  <div class="w3-row w3-padding-64">
    <p>Hi <?= htmlspecialchars($name) ?>, Welcome to User Management!</p>
    <div><strong>User List:</strong> View and manage system users</div><br>

    <?php
    $stmt = $pdo->query("SELECT * FROM user");
    $users = $stmt->fetchAll();

    echo "<table>
            <tr>
              <th>Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>";
    if ($user['roleid'] == 1) {
        echo "<th>Action</th>";
    }
    echo "</tr>";

    foreach ($users as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        
        $roleLabel = $row['roleid'] == 1 ? 'Admin' : ($row['roleid'] == 2 ? 'Instructor' : 'Student');
        echo "<td>$roleLabel</td>";

        if ($user['roleid'] == 1) {
            echo "<td>
                    <a href='editUser.php?id={$row['id']}' title='Edit'>Edit</a>
                    <a href='deleteUser.php?id={$row['id']}' title='Delete' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                  </td>";
        }

        echo "</tr>";
    }

    echo "</table>";
    ?>
    <button><a href="adduser.php">add course</a></button>
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
