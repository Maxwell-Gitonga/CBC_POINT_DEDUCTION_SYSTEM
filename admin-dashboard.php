<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin-login.html");
    exit();
}

// Get admin name from session
$adminName = $_SESSION['A_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">
    <h1>Welcome, <?php echo htmlspecialchars($adminName); ?> 👨‍💼</h1>

    <div class="dashboard-menu">
      <a href="admin-register-users.html" class="nav-btn">Register Users</a>
      <a href="view-students.php" class="dashboard-link">📋 View All Students</a>
      <a href="view-parents.php" class="dashboard-link">👪 View Parents</a>
      <a href="link-parent-student.php" class="dashboard-link">🔗 Link Parent to Student</a>
      <a href="view-parent-links.php" class="dashboard-link">🔗 View Parent-Student Links</a>
      <a href="view-teachers.php" class="dashboard-link">👩‍🏫 View Teachers</a>
      <a href="view-conduct.php" class="dashboard-link">⚠️ View Conduct Logs</a>
      <a href="view-rules.php" class="dashboard-link">📜 View/Manage Rules</a>
      <a href="admin-login.html" class="dashboard-link logout">← Back to Admin</a>
    </div>
  </div>
</body>
</html>
