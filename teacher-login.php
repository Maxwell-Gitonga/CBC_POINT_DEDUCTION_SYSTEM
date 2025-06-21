<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get inputs from the login form
    $teacherID = trim($_POST['teacherID']);
    $password = trim($_POST['password']);

    // Query teacher table
    $query = "SELECT * FROM teacher WHERE teacher_ID = '$teacherID'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $teacher = mysqli_fetch_assoc($result);

        // Check password
        if ($teacher['password'] == $password) {
            // Save teacher info in session
            $_SESSION['teacher_ID'] = $teacher['teacher_ID'];
            $_SESSION['T_name'] = $teacher['T_name'];
            $_SESSION['t_department'] = $teacher['t_department'];
            $_SESSION['T_email'] = $teacher['T_email'];

            // Redirect to dashboard
            header("Location: teacher-dashboard.php");
            exit();
        } else {
            echo "<h3 style='color:red; text-align:center;'>❌ Incorrect password.</h3>";
        }
    } else {
        echo "<h3 style='color:red; text-align:center;'>❌ Teacher not found.</h3>";
    }

    mysqli_close($conn);
} else {
    echo "<h3 style='color:red; text-align:center;'>Invalid request method</h3>";
}
?>
