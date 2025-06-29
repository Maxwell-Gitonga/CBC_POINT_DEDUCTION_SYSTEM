<?php
session_start();

if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin-login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM parent";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Parents</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">
    <h1>👪 Registered Parents</h1>

    <table>
      <tr>
        <th>Parent ID</th>
        <th>Name</th>
        <th>Email</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?php echo $row['parent_ID']; ?></td>
          <td><?php echo $row['P_name']; ?></td>
          <td><?php echo $row['P_email']; ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
