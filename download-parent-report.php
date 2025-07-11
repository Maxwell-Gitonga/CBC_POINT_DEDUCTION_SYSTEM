<?php
session_start();
require('fpdf/fpdf.php');

// Ensure parent is logged in
if (!isset($_SESSION['parent_ID'])) {
    die("Unauthorized access.");
}

$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parentID = $_SESSION['parent_ID'];

// Get students linked to the parent
$students = $conn->query("
    SELECT s.student_ID, s.S_name, s.S_score 
    FROM student s
    JOIN parent_student ps ON s.student_ID = ps.student_ID
    WHERE ps.parent_ID = '$parentID'
");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Child Conduct Report", 0, 1, 'C');
$pdf->Ln(5);

while ($student = $students->fetch_assoc()) {
    $sid = $student['student_ID'];
    $sname = $student['S_name'];
    $sscore = $student['S_score'];

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Student: $sname (Score: $sscore)",0,1);
    
    // Table headers
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(35,10,"Date",1);
    $pdf->Cell(115,10,"Description",1);
    $pdf->Cell(40,10,"Points Deducted",1);
    $pdf->Ln();

    // Conduct records for this student
    $records = $conn->query("
        SELECT offenceDate, OffenceDescription, pointsDeducted 
        FROM conduct_record 
        WHERE student_ID = '$sid' 
        ORDER BY offenceDate DESC
    ");

    if ($records->num_rows > 0) {
        $pdf->SetFont('Arial','',10);
        while ($row = $records->fetch_assoc()) {
            $pdf->Cell(35,10, $row['offenceDate'], 1);
            $pdf->Cell(115,10, $row['OffenceDescription'], 1);
            $pdf->Cell(40,10, $row['pointsDeducted'], 1);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(0,10,"No records found.",1,1);
    }

    $pdf->Ln(5);
}

$conn->close();
$pdf->Output("D", "Parent_Conduct_Report.pdf");
?>
