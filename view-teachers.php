<?php
session_start();

// Optional: Check if admin is logged in
if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin-login.html");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all teachers
$query = "SELECT * FROM teacher";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Teachers</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    th {
      background-color: #2e8b57;
      color: white;
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <h1>👩‍🏫 All Registered Teachers</h1>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['teacher_ID']; ?></td>
          <td><?php echo $row['T_name']; ?></td>
        </tr>
      <?php } ?>
    </table>

    <br>
    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>

