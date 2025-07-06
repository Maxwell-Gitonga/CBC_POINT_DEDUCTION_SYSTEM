<?php
session_start();
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");

if (!$conn) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Check parent login
if (!isset($_SESSION['parent_ID'])) {
    echo "<p style='color:red;'>You must be logged in as a parent to view notifications.</p>";
    exit();
}

$parentID = $_SESSION['parent_ID'];

// Get students linked to this parent
$studentQuery = "
  SELECT s.student_ID, s.S_name, s.S_class, s.S_score
  FROM student s
  JOIN parent_student ps ON s.student_ID = ps.student_ID
  WHERE ps.parent_ID = '$parentID'
";

$students = $conn->query($studentQuery);

if ($students->num_rows === 0) {
    echo "<p>No students linked to your account.</p>";
    exit();
}

while ($student = $students->fetch_assoc()) {
    $sid = $student['student_ID'];
    $score = (int) $student['S_score'];
    $status = '';

    if ($score >= 80) {
        $status = "<span style='color:green;'>✅ Active</span>";
    } elseif ($score >= 60) {
        $status = "<span style='color:orange;'>⚠️ Warning</span>";
    } elseif ($score >= 40) {
        $status = "<span style='color:red;'>🚫 Suspended</span>";
    } else {
        $status = "<span style='color:darkred;'>❌ Barred from Exams</span>";
    }

    echo "<h3>👤 {$student['S_name']} (Class: {$student['S_class']}) - Status: $status</h3>";

    // Show conduct history
    $conductQuery = "
      SELECT offenceDate, OffenceDescription, pointsDeducted
      FROM conduct_record
      WHERE student_ID = '$sid'
      ORDER BY offenceDate DESC
    ";

    $conducts = $conn->query($conductQuery);

    if ($conducts->num_rows > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%; max-width: 800px;'>
                <tr style='background-color:#2e8b57; color:white;'>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Points Deducted</th>
                </tr>";

        while ($row = $conducts->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['offenceDate']}</td>
                    <td>{$row['OffenceDescription']}</td>
                    <td>{$row['pointsDeducted']}</td>
                  </tr>";
        }

        echo "</table><br>";
    } else {
        echo "<p style='color:gray;'>No conduct records found for this student.</p>";
    }
}

$conn->close();
?>
