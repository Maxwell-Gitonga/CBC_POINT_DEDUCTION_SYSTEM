<?php
// Start session
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get submitted form values
$parentID = isset($_POST['parent_ID']) ? (int)$_POST['parent_ID'] : 0;
$studentID = isset($_POST['student_ID']) ? (int)$_POST['student_ID'] : 0;

if ($parentID === 0 || $studentID === 0) {
    echo "<h3 style='color:red;'>❌ Invalid parent or student selected.</h3>";
    exit();
}

// Check if already linked
$check = $conn->prepare("SELECT * FROM parent_student WHERE parent_ID = ? AND student_ID = ?");
$check->bind_param("ii", $parentID, $studentID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<h3 style='color:orange;'>⚠️ Already linked!</h3>";
} else {
    $insert = $conn->prepare("INSERT INTO parent_student (parent_ID, student_ID) VALUES (?, ?)");
    $insert->bind_param("ii", $parentID, $studentID);
    
    if ($insert->execute()) {
        echo "<h3 style='color:green;'>✅ Link successful!</h3>";
    } else {
        echo "<h3 style='color:red;'>❌ Error: " . $conn->error . "</h3>";
    }
}

echo "<br><a href='link-parent-student.html'>← Back</a>";
echo "<br><a href='admin-dashboard.php'>🏠 Dashboard</a>";

$conn->close();
?>
