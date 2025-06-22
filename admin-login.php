<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $adminID = trim($_POST['admin_ID']);
    $enteredPassword = trim($_POST['password']);

    if (empty($adminID) || empty($enteredPassword)) {
        echo "<p style='color:red; text-align:center;'>❌ Please fill in both fields.</p>";
        exit();
    }

    // Query the admin table
    $query = "SELECT * FROM admin WHERE admin_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Use password_verify to compare entered password with hashed password
        if (password_verify($enteredPassword, $admin['password'])) {
            // Success: store data in session
            $_SESSION['admin_ID'] = $admin['admin_ID'];
            $_SESSION['A_name'] = $admin['A_name'];

            header("Location: admin-dashboard.php");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Incorrect password.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Admin ID not found.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='color:red; text-align:center;'>Invalid request.</p>";
}
?>
