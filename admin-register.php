<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data

$name     = trim($_POST['A_name']);
$email    = trim($_POST['A_email']);
$password = trim($_POST['password']);
$role     = $_POST['role'];

// Hash the password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$query = "INSERT INTO admin ( A_name,A_email, password, role)
          VALUES ( '$name', '$email','$hashedPassword', '$role')";

if ($conn->query($query) === TRUE) {
    echo "<p style='color:green; text-align:center; font-weight:bold;'>✅ Admin account created successfully!</p>";
    echo "<p style='text-align:center;'><a href='admin-login.html'>Login Now</a></p>";
} else {
    echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
}

$conn->close();
?>
