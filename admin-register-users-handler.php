<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Get the user type
if (isset($_POST['user_type'])) {
    $userType = $_POST['user_type'];

    if ($userType === "student") {
        $S_name = $_POST['S_name'];
        $S_class = $_POST['S_class'];
        $S_score = $_POST['S_score'];
        $password = $_POST['password'];

        $sql = "INSERT INTO student (S_name, S_class, S_score, password)
                VALUES ('$S_name', '$S_class', '$S_score', '$password')";
    
    } elseif ($userType === "teacher") {
        $T_name = $_POST['T_name'];
        $T_department = $_POST['T_department'];
        $T_email = $_POST['T_email'];
        $password = $_POST['password'];

        $sql = "INSERT INTO teacher (T_name, T_department, T_email, password)
                VALUES ('$T_name', '$T_department', '$T_email', '$password')";
    
    } elseif ($userType === "parent") {
        $P_name = $_POST['P_name'];
        $P_email = $_POST['P_email'];
        $password = $_POST['password'];

        $sql = "INSERT INTO parent (P_name, P_email, password)
                VALUES ('$P_name', '$P_email', '$password')";
    } else {
        echo "❌ Invalid user type.";
        exit();
    }

    // Execute SQL
    if (mysqli_query($conn, $sql)) {
        echo "<h2 style='color: green;'>✅ $userType registered successfully!</h2>";
    } else {
        echo "<h2 style='color: red;'>❌ Failed to register $userType: " . mysqli_error($conn) . "</h2>";
    }

    echo "<br><a href='admin-register-users.html'>← Back to Register Page</a>";
} else {
    echo "❌ No user type selected.";
}

mysqli_close($conn);
?>
