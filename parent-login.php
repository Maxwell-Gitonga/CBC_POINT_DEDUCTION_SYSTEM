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

// Get values
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if ($email === '' || $password === '') {
    die("<h3 style='color:red; text-align:center;'>❌ Please enter both email and password.</h3>");
}

// Fetch parent record
$sql = "SELECT * FROM parent WHERE P_email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $parent = mysqli_fetch_assoc($result);

    if ($parent['password'] == $password) {
        $_SESSION['parent_ID'] = $parent['parent_ID'];
        $_SESSION['P_name'] = $parent['P_name'];
        header("Location: parent-dashboard.php");
        exit();
    } else {
        echo "<h3 style='color:red; text-align:center;'>❌ Incorrect password.</h3>";
    }
} else {
    echo "<h3 style='color:red; text-align:center;'>❌ Parent not found.</h3>";
}

mysqli_close($conn);
?>
