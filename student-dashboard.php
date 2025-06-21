<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_ID'])) {
    header("Location: student-login.html");
    exit();
}

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get student info
$studentID = $_SESSION['student_ID'];
$query = "SELECT * FROM student WHERE student_ID = '$studentID'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Fetch conduct records
$recordsQuery = "SELECT * FROM conduct_record WHERE student_ID = '$studentID' ORDER BY offense_date DESC";
$recordsResult = mysqli_query($conn, $recordsQuery);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="student.css">
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome, <?php echo $student['S_name']; ?>!</h1>

    <div class="status-box">
      <p><strong>Class:</strong> <?php echo $student['S_class']; ?></p>
      <p><strong>Current Score:</strong> <?php echo $student['S_score']; ?></p>

      <p><strong>Status:</strong>
        <?php
          $score = $student['S_score'];
          if ($score >= 80) echo "<span class='status-active'>Active</span>";
          elseif ($score >= 60) echo "<span class='status-warning'>Warning</span>";
          elseif ($score >= 40) echo "<span class='status-suspended'>Suspended</span>";
          else echo "<span class='status-barred'>Barred from Exams</span>";
        ?>
      </p>
    </div>

    <h2>Conduct Log</h2>
    <table class="log-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Offense</th>
          <th>Points Deducted</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($recordsResult)) { ?>
          <tr>
            <td><?php echo $row['offense_date']; ?></td>
            <td><?php echo $row['offense']; ?></td>
            <td><?php echo $row['points_deducted']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <br>
    <a href="student-login.html" class="btn">← Logout</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
