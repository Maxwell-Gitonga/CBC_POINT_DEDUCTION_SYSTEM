<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_ID'])) {
    header("Location: student-login.html");
    exit();
}

// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$studentID = $_SESSION['student_ID'];

// Fetch student info
$sql     = "SELECT * FROM student WHERE student_ID = '$studentID'";
$result  = mysqli_query($conn, $sql) or die("Student query failed: " . mysqli_error($conn));
$student = mysqli_fetch_assoc($result);

// Fetch conduct records
$conductSQL    = "SELECT * FROM conduct_record WHERE student_ID = '$studentID' ORDER BY offenceDate DESC";
$conductResult = mysqli_query($conn, $conductSQL) or die("Conduct query failed: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="student.css">
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($student['S_name']); ?>!</h1>

    <div class="status-box">
      <p><strong>Class:</strong> <?php echo htmlspecialchars($student['S_class']); ?></p>
      <p><strong>Score:</strong> <?php echo $student['S_score']; ?></p>
    <p><strong>Status:</strong>
  <?php
    $score = $student['S_score'];
    if ($score >= 80) {
        echo "<span class='status-active'>Active</span>";
    } elseif ($score >= 60) {
        echo "<span class='status-warning'>Warning</span>";
    } elseif ($score >= 40) {
        echo "<span class='status-suspended'>Suspension</span>";
    } else {
        echo "<span class='status-barred'>Barred from Exams</span>";
    }
  ?>
</p>

    </div>

    <h2>Conduct Log</h2>
    <table class="log-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Points Deducted</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($conductResult) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($conductResult)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['offenceDate']); ?></td>
              <td><?php echo (int)$row['pointsDeducted']; ?></td>
              <td><?php echo htmlspecialchars($row['OffenceDescription']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="3" style="text-align:center;">No records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <br>
    <a href="student-login.html" class="btn">← Logout</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
