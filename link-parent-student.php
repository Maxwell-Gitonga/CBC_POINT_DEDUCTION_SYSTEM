<?php
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Link Parent to Student</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-container">
  <h2>👪 Link Parent to Student</h2>

  <form action="link-parent-student-handler.php" method="POST">
    <label for="parent_ID">Select Parent:</label><br>
    <select name="parent_ID" id="parent_ID" required>
      <?php
      $parents = $conn->query("SELECT parent_ID, P_name FROM parent");
      while ($row = $parents->fetch_assoc()) {
        echo "<option value='{$row['parent_ID']}'>{$row['P_name']} (ID: {$row['parent_ID']})</option>";
      }
      ?>
    </select><br><br>

    <label for="student_ID">Select Student:</label><br>
    <select name="student_ID" id="student_ID" required>
      <?php
      $students = $conn->query("SELECT student_ID, S_name FROM student");
      while ($row = $students->fetch_assoc()) {
        echo "<option value='{$row['student_ID']}'>{$row['S_name']} (ID: {$row['student_ID']})</option>";
      }
      $conn->close();
      ?>
    </select><br><br>

    <button type="submit" class="btn">Link Parent to Student</button>
  </form>

  <br><a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
</div>
</body>
</html>
