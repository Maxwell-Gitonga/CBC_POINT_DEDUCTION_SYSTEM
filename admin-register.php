<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$admin_ID = $_POST['admin_ID'];
$name     = trim($_POST['A_name']);
$password = trim($_POST['password']);
$role     = $_POST['role'];

// Hash the password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$query = "INSERT INTO admin (admin_ID, A_name, password, role)
          VALUES ('$admin_ID', '$name', '$hashedPassword', '$role')";

if ($conn->query($query) === TRUE) {
    echo "<p style='color:green; text-align:center; font-weight:bold;'>✅ Admin account created successfully!</p>";
    echo "<p style='text-align:center;'><a href='admin-login.html'>Login Now</a></p>";
} else {
    echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
}

$conn->close();
?>
