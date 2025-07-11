<?php
require('fpdf/fpdf.php');

// Connect to DB
$conn = new mysqli("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define thresholds and titles
$thresholds = [
    ["min" => 80, "max" => 1000, "label" => "Active (Score 80 and above)"],
    ["min" => 60, "max" => 79, "label" => "Warning (Score 60 - 79)"],
    ["min" => 40, "max" => 59, "label" => "Suspended (Score 40 - 59)"],
    ["min" => 0,  "max" => 39, "label" => "Barred (Below 40)"]
];

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'CBC Conduct Report (Grouped by Score Threshold)', 0, 1, 'C');
$pdf->Ln(5);

foreach ($thresholds as $group) {
    $min = $group['min'];
    $max = $group['max'];
    $label = $group['label'];

    $query = "
        SELECT s.S_name, s.S_score, c.offenceDate, c.OffenceDescription, c.pointsDeducted
        FROM student s
        JOIN conduct_record c ON s.student_ID = c.student_ID
        WHERE s.S_score BETWEEN $min AND $max
        ORDER BY s.S_name, c.offenceDate
    ";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Section title
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(0, 10, $label, 1, 1, 'L', true);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(45, 10, "Student Name", 1);
        $pdf->Cell(20, 10, "Score", 1);
        $pdf->Cell(30, 10, "Date", 1);
        $pdf->Cell(65, 10, "Offense", 1);
        $pdf->Cell(30, 10, "Points", 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(45, 10, $row['S_name'], 1);
            $pdf->Cell(20, 10, $row['S_score'], 1);
            $pdf->Cell(30, 10, $row['offenceDate'], 1);
            $pdf->Cell(65, 10, $row['OffenceDescription'], 1);
            $pdf->Cell(30, 10, $row['pointsDeducted'], 1);
            $pdf->Ln();
        }
        $pdf->Ln(5);
    }
}

$pdf->Output("D", "Grouped_Conduct_Report.pdf");
?>
