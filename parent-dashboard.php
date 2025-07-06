<?php
session_start();
if (!isset($_SESSION['parent_ID'])) {
    header("Location: parent-login.html");
    exit();
}

$pid = $_SESSION['parent_ID'];
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get linked student
$studentQuery = "
    SELECT s.student_ID, s.S_name, s.S_score, s.S_class
    FROM student s
    JOIN parent_student ps ON s.student_ID = ps.student_ID
    WHERE ps.parent_ID = '$pid'
    LIMIT 1
";
$studentResult = mysqli_query($conn, $studentQuery);
$student = mysqli_fetch_assoc($studentResult);

if (!$student) {
    echo "<h3 style='color:red;'>❌ No child linked to this parent.</h3>";
    exit();
}

$studentID = $student['student_ID'];
$name = $student['S_name'];
$score = (int)$student['S_score'];
$class = $student['S_class'];

// Status
if ($score >= 80) $status = "✅ Active";
elseif ($score >= 60) $status = "⚠️ Warning";
elseif ($score >= 40) $status = "🚫 Suspended";
else $status = "❌ Barred";

// Count unread notifications
$unseenQuery = "
  SELECT COUNT(*) AS unseen_count
  FROM conduct_record
  WHERE student_ID = '$studentID' AND seen_by_parent = FALSE
";
$unseenResult = mysqli_query($conn, $unseenQuery);
$unseenRow = mysqli_fetch_assoc($unseenResult);
$unreadCount = (int)$unseenRow['unseen_count'];

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Parent Dashboard</title>
  <link rel="stylesheet" href="parent.css">
  <style>
    .dashboard-container {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px #ccc;
      border-radius: 8px;
    }

    h1 {
      color: #2e8b57;
    }

    .status-box {
      margin-top: 20px;
      padding: 15px;
      background-color: #f3f3f3;
      border-left: 6px solid #2e8b57;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 20px;
      background: #2e8b57;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .btn:hover {
      background-color: #246c46;
    }

    .badge {
      background: red;
      color: white;
      border-radius: 50%;
      padding: 3px 8px;
      font-size: 12px;
      vertical-align: top;
      margin-left: 5px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome, Parent 👪</h1>
    <div class="status-box">
      <p><strong>Child Name:</strong> <?= htmlspecialchars($name) ?></p>
      <p><strong>Class:</strong> <?= htmlspecialchars($class) ?></p>
      <p><strong>Score:</strong> <?= $score ?></p>
      <p><strong>Status:</strong> <?= $status ?></p>
    </div>

    <a href="view-notifications.php" class="btn">
      📩 View Notifications
      <?php if ($unreadCount > 0): ?>
        <span class="badge"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>

    <br><br>
    <a href="parent-login.html" class="btn">← Logout</a>
  </div>
</body>
</html>
