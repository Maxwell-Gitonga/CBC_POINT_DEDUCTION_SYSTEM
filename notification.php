<?php
// DB connection
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) die("❌ Connection failed: " . mysqli_connect_error());

// Get parent ID from form
$parentID = isset($_POST['parent_ID']) ? (int)$_POST['parent_ID'] : 0;

echo "<h2>📚 Notifications for Parent ID: $parentID</h2>";

if ($parentID === 0) {
  echo "<p style='color:red;'>Invalid parent ID</p>";
  exit();
}

// Get linked student(s)
$query = "
SELECT s.student_ID, s.S_name, s.S_score, s.S_class, cr.OffenceDescription, cr.offenceDate, cr.pointsDeducted
FROM parent_student ps
JOIN student s ON ps.student_ID = s.student_ID
LEFT JOIN conduct_record cr ON s.student_ID = cr.student_ID
WHERE ps.parent_ID = $parentID
ORDER BY cr.offenceDate DESC
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
  echo "<p>No records or student linked to this parent.</p>";
} else {
  echo "<table border='1' cellpadding='10'>
    <tr>
      <th>Student</th>
      <th>Class</th>
      <th>Score</th>
      <th>Status</th>
      <th>Offense</th>
      <th>Date</th>
      <th>Points Deducted</th>
    </tr>";

  while ($row = mysqli_fetch_assoc($result)) {
    $score = (int)$row['S_score'];

    // Determine status
    if ($score >= 80) $status = "✅ Active";
    elseif ($score >= 60) $status = "⚠️ Warning";
    elseif ($score >= 40) $status = "🚫 Suspended";
    else $status = "❌ Barred";

    echo "<tr>
      <td>{$row['S_name']} (ID: {$row['student_ID']})</td>
      <td>{$row['S_class']}</td>
      <td>$score</td>
      <td>$status</td>
      <td>{$row['OffenceDescription']}</td>
      <td>{$row['offenceDate']}</td>
      <td>{$row['pointsDeducted']}</td>
    </tr>";
  }

  echo "</table>";
}

echo "<br><a href='notification.html'>← Back</a>";
mysqli_close($conn);
?>
