<?php
session_start();

// Ensure teacher is logged in
if (!isset($_SESSION['teacher_ID'])) {
    die("Unauthorized access. Please log in.");
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate POST input
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_ID = trim($_POST['student_ID']);
    $ruleID = trim($_POST['rule_ID']);
    $teacher_ID = $_SESSION['teacher_ID'];
    $OffenceDescription = trim($_POST['OffenceDescription']);
    $offenceDate = date("Y-m-d");

    // Get rule details
    $ruleQuery = "SELECT * FROM ruleset WHERE rule_ID = '$ruleID'";
    $ruleResult = mysqli_query($conn, $ruleQuery);

    if ($ruleResult && mysqli_num_rows($ruleResult) === 1) {
        $rule = mysqli_fetch_assoc($ruleResult);
        $points = $rule['pointsToDeduct'];

        // Insert into conduct_record
        $insertQuery = "
        INSERT INTO conduct_record (
            rule_ID, student_ID, teacher_ID, offenceDate, OffenceDescription, pointsDeducted
        ) VALUES (
            '$ruleID', '$student_ID', '$teacher_ID', '$offenceDate', '$OffenceDescription', '$points'
        )";

        if (mysqli_query($conn, $insertQuery)) {
            // Update student's score
            $updateQuery = "UPDATE student SET S_score = S_score - $points WHERE student_ID = '$student_ID'";
            mysqli_query($conn, $updateQuery);

            echo "<p style='color:green; font-weight:bold;'>✅ Conduct record submitted and student score updated.</p>";

            // Fetch student name
            $studentQuery = "SELECT S_name FROM student WHERE student_ID = '$student_ID'";
            $studentResult = mysqli_query($conn, $studentQuery);
            $student = mysqli_fetch_assoc($studentResult);
            $studentName = $student['S_name'];

            // Get all linked parents
            $parentQuery = "
                SELECT parent.parent_ID, P_name, P_email 
                FROM parent 
                JOIN parent_student ON parent.parent_ID = parent_student.parent_ID 
                WHERE parent_student.student_ID = '$student_ID'
            ";
            $parentResult = mysqli_query($conn, $parentQuery);

            while ($parent = mysqli_fetch_assoc($parentResult)) {
                $to = $parent['P_email'];
                $subject = "CBC Alert: New Conduct Entry for $studentName";
                $message = "Dear {$parent['P_name']},\n\n" .
                           "An offense has been recorded for your child, $studentName.\n" .
                           "Description: $OffenceDescription\n" .
                           "Points Deducted: $points\n" .
                           "Date: $offenceDate\n\n" .
                           "Please log in to the CBC portal to view full records.\n\n" .
                           "- CBC Point Deduction System";
                $headers = "From: no-reply@cbc-system.test";

                // Send email
                mail($to, $subject, $message, $headers);

                // Insert into notification table
                $insertNotif = "
                  INSERT INTO notification (student_ID, parent_ID, message, date_sent)
                  VALUES ('$student_ID', '{$parent['parent_ID']}', 
                    'Offense for $studentName: $OffenceDescription. Points: $points.', 
                    CURDATE()
                  )
                ";
                mysqli_query($conn, $insertNotif);
            }

            echo "<a href='teacher-dashboard.php'>← Back to Dashboard</a>";
        } else {
            echo "<p style='color:red;'>❌ Failed to insert conduct record.</p>";
            echo mysqli_error($conn);
        }
    } else {
        echo "<p style='color:red;'>❌ Invalid rule ID selected.</p>";
    }
} else {
    echo "<p style='color:red;'>Invalid request method.</p>";
}

mysqli_close($conn);
?>
