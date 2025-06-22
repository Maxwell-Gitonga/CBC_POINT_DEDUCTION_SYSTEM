<?php
// Start session and check admin login if needed
session_start();

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all students
$query = "SELECT * FROM student";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Students</title>
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
    <h1>📋 All Registered Students</h1>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Class</th>
        <th>Score</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['student_ID']; ?></td>
          <td><?php echo $row['S_name']; ?></td>
          <td><?php echo $row['S_class']; ?></td>
          <td><?php echo $row['S_score']; ?></td>
        </tr>
      <?php } ?>
    </table>

    <br>
    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
