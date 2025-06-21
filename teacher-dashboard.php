<?php
session_start();

// Check if teacher is logged in
if (!isset($_SESSION['teacher_ID'])) {
    header("Location: teacher-login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$teacherID = $_SESSION['teacher_ID'];
$teacherName = $_SESSION['T_name'];

// Fetch rules for dropdown
$rulesQuery = "SELECT * FROM ruleset";
$rulesResult = mysqli_query($conn, $rulesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="teacher.css">
</head>
<body>
  <div class="dashboard-container">
    <h2>Welcome, <?php echo $teacherName; ?> 👨‍🏫</h2>

    <h3>Submit a Conduct Record</h3>
    <form action="submit-record.php" method="POST">
      <label for="student_ID">Student ID:</label>
      <input type="number" name="student_ID" required><br><br>

      <label for="rule_ID">Select Offense:</label>
      <select name="rule_ID" required>
        <option value="">-- Choose Offense --</option>
        <?php while ($row = mysqli_fetch_assoc($rulesResult)) { ?>
          <option value="<?php echo $row['rule_ID']; ?>">
            <?php echo $row['offenseType'] . " - " . $row['pointsToDeduct'] . " pts"; ?>
          </option>
        <?php } ?>
      </select><br><br>

      <label for="OffenceDescription">Optional Description:</label><br>
      <textarea name="OffenceDescription"rows="3" placeholder="Add context..."></textarea><br><br>

      <input type="submit" value="Submit Record" class="btn">
    </form>

    <br><a href="teacher-login.html" class="btn">← Logout</a>
  </div>
</body>
</html>
