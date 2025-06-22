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

// Get all conduct logs with JOINs
$query = "
SELECT cr.conduct_ID, cr.offenceDate, cr.OffenceDescription, cr.pointsDeducted,
       s.student_ID AS student_ID, s.S_class,
       t.T_name AS teacher_name,
       r.offenseType
FROM conduct_record cr
JOIN student s ON cr.student_ID = s.student_ID
JOIN teacher t ON cr.teacher_ID = t.teacher_ID
JOIN ruleset r ON cr.rule_ID = r.rule_ID
ORDER BY cr.offenceDate DESC
";


$result = mysqli_query($conn, $query);
if (!$result) {
    die("❌ Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>View Conduct Records</title>
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
      text-align: center;
    }

    th {
      background-color: #2e8b57;
      color: white;
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <h1>⚠️ Conduct Records</h1>

    <table>
      <tr>
        <th>Date</th>
        <th>Student_ID</th>
        <th>Class</th>
        <th>Teacher</th>
        <th>Offense</th>
        <th>Description</th>
        <th>Points Deducted</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['offenceDate']; ?></td>
          <td><?php echo $row['student_ID']; ?></td>
          <td><?php echo $row['S_class']; ?></td>
          <td><?php echo $row['teacher_name']; ?></td>
          <td><?php echo $row['offenseType']; ?></td>
          <td><?php echo $row['OffenceDescription']; ?></td>
          <td><?php echo $row['pointsDeducted']; ?></td>
        </tr>
      <?php } ?>
    </table>

    <br>
    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
