<?php
session_start();

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<h3 style='color:red; text-align:center;'>Invalid request method.</h3>");
}

// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Grab POST values (names must match your form)
$studentID = isset($_POST['studentID']) ? trim($_POST['studentID']) : '';
$password  = isset($_POST['password'])  ? trim($_POST['password'])  : '';

// Validate input
if ($studentID === '' || $password === '') {
    die("<h3 style='color:red; text-align:center;'>❌ Please enter both Student ID and Password.</h3>");
}

// Query for that student
$sql    = "SELECT * FROM student WHERE student_ID = '$studentID'";
$result = mysqli_query($conn, $sql) or die("Query failed: " . mysqli_error($conn));

if (mysqli_num_rows($result) === 1) {
    $student = mysqli_fetch_assoc($result);

    // Check password
    if ($student['password'] == $password) {
        // Success: store session and redirect
        $_SESSION['student_ID'] = $student['student_ID'];
        $_SESSION['S_name']     = $student['S_name'];
        header("Location: student-dashboard.php");
        exit();
    } else {
        echo "<h3 style='color:red; text-align:center;'>❌ Incorrect password.</h3>";
    }
} else {
    echo "<h3 style='color:red; text-align:center;'>❌ Student ID not found.</h3>";
}

mysqli_close($conn);
?>
