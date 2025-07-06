<?php
session_start();

// Check if parent is logged in
if (!isset($_SESSION['parent_ID'])) {
    header("Location: parent-login.html");
    exit();
}

$pid = $_SESSION['parent_ID'];
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get child info linked to this parent
$childQuery = "
    SELECT s.student_ID, s.S_name, s.S_score, s.S_class
    FROM student s
    JOIN parent_student ps ON s.student_ID = ps.student_ID
    WHERE ps.parent_ID = '$pid'
    LIMIT 1
";
$childResult = mysqli_query($conn, $childQuery);
$child = mysqli_fetch_assoc($childResult);

if (!$child) {
    echo "<h3 style='color:red;'>❌ No linked student found for this parent.</h3>";
    exit();
}

$sid = $child['student_ID'];
$sname = $child['S_name'];
$score = (int)$child['S_score'];
$class = $child['S_class'];

// Determine status
if ($score >= 80) $status = "✅ Active";
elseif ($score >= 60) $status = "⚠️ Warning";
elseif ($score >= 40) $status = "🚫 Suspended";
else $status = "❌ Barred from Exams";

// Fetch conduct logs
$logsQuery = "SELECT * FROM conduct_record WHERE student_ID = '$sid' ORDER BY offenceDate DESC";
$logsResult = mysqli_query($conn, $logsQuery);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Notifications - CBC System</title>
  <link rel="stylesheet" href="parent.css">
  <style>
    .parent-container {
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      box-shadow: 0 0 10px #ccc;
      border-radius: 8px;
    }

    h2, h3 {
      color: #2e8b57;
    }

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

    .unread {
      background-color: #f9e0e0;
      font-weight: bold;
    }

    .btn {
      background: #2e8b57;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }

    .btn:hover {
      background: #246c46;
    }
  </style>
</head>
<body>
  <div class="parent-container">
    <h2>📢 Notifications for <?php echo htmlspecialchars($sname); ?></h2>
    <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
    <p><strong>Score:</strong> <?php echo $score; ?></p>
    <p><strong>Status:</strong> <?php echo $status; ?></p>

    <h3>📜 Conduct Records</h3>
    <table>
      <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Points Deducted</th>
        <th>Status</th>
      </tr>
      <?php if (mysqli_num_rows($logsResult) > 0): ?>
        <?php while ($log = mysqli_fetch_assoc($logsResult)): ?>
          <tr class="<?php echo $log['seen_by_parent'] ? '' : 'unread'; ?>">
            <td><?php echo $log['offenceDate']; ?></td>
            <td><?php echo htmlspecialchars($log['OffenceDescription']); ?></td>
            <td><?php echo $log['pointsDeducted']; ?></td>
            <td><?php echo $log['seen_by_parent'] ? '👁 Seen' : '🆕 Unread'; ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4">No conduct records found.</td></tr>
      <?php endif; ?>
    </table>

    <?php
    // ✅ Mark all records as seen
    $conn->query("UPDATE conduct_record 
                  SET seen_by_parent = TRUE 
                  WHERE student_ID = '$sid' AND seen_by_parent = FALSE");
    ?>

    <br><a href="parent-dashboard.php" class="btn">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
