<?php
session_start();

// DB connection info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CBC_POINT_DEDUCTION_SYSTEM";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentID = trim($_POST['studentID']);
    $enteredPassword = trim($_POST['password']);  // renamed for clarity

    // Query the student table
    $query = "SELECT * FROM student WHERE student_ID = '$studentID'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $student = mysqli_fetch_assoc($result);

        if ($student['password'] == $enteredPassword) {
            // Save data to session
            $_SESSION['student_ID'] = $student['student_ID'];
            $_SESSION['S_name'] = $student['S_name'];

            header("Location: student-dashboard.php");
            exit();
        } else {
            echo "<h3 style='color:red; text-align:center;'>❌ Incorrect password</h3>";
        }
    } else {
        echo "<h3 style='color:red; text-align:center;'>❌ Student ID not found</h3>";
    }

    mysqli_close($conn);
} else {
    echo "<h3 style='color:red; text-align:center;'>Invalid request</h3>";
}
?>
