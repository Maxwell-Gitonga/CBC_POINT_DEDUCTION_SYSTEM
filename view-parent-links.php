<?php
session_start();

// Optional: Protect the page with admin session
if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin-login.html");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get parent-student links
$query = "
  SELECT ps.parent_ID, p.P_name, p.P_email, 
         ps.student_ID, s.S_name, s.S_class
  FROM parent_student ps
  JOIN parent p ON ps.parent_ID = p.parent_ID
  JOIN student s ON ps.student_ID = s.student_ID
  ORDER BY p.P_name, s.S_name
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Parent-Student Links</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #2e8b57;
      color: white;
    }

    h1 {
      color: #2e8b57;
    }

    .dashboard-link {
      display: inline-block;
      margin-top: 25px;
      background-color: #2e8b57;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }

    .dashboard-link:hover {
      background-color: #246c46;
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <h1>👪 Parent-Student Links</h1>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Parent ID</th>
            <th>Parent Name</th>
            <th>Email</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Class</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['parent_ID']; ?></td>
              <td><?php echo $row['P_name']; ?></td>
              <td><?php echo $row['P_email']; ?></td>
              <td><?php echo $row['student_ID']; ?></td>
              <td><?php echo $row['S_name']; ?></td>
              <td><?php echo $row['S_class']; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="color:red;">❌ No parent-student links found.</p>
    <?php endif; ?>

    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php $conn->close(); ?>
