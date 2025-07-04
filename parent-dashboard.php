<?php
session_start();

if (!isset($_SESSION['parent_ID'])) {
    header("Location: parent-login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$parentID = $_SESSION['parent_ID'];

// Fetch student info linked to parent
$sql = "
SELECT s.student_ID, s.S_name, s.S_class, s.S_score, c.offenceDate, c.pointsDeducted, c.OffenceDescription
FROM parent_student ps
JOIN student s ON ps.student_ID = s.student_ID
LEFT JOIN conduct_record c ON s.student_ID = c.student_ID
WHERE ps.parent_ID = '$parentID'
ORDER BY c.offenceDate DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Parent Dashboard</title>
  <link rel="stylesheet" href="parent.css">
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['P_name']); ?>!</h1>
    <h2>📋 Child Conduct Summary</h2>

    <table>
      <thead>
        <tr>
          <th>Student ID</th>
          <th>Name</th>
          <th>Class</th>
          <th>Score</th>
          <th>Date</th>
          <th>Description</th>
          <th>Points Deducted</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row['student_ID']; ?></td>
              <td><?php echo $row['S_name']; ?></td>
              <td><?php echo $row['S_class']; ?></td>
              <td><?php echo $row['S_score']; ?></td>
              <td><?php echo $row['offenceDate']; ?></td>
              <td><?php echo $row['OffenceDescription']; ?></td>
              <td><?php echo $row['pointsDeducted']; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <br>
    <a href="parent-login.html" class="btn">← Logout</a>
    <a href="notification.html" class="btn">📬 Notifications</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
