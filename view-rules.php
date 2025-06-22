<?php
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin-login.html");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all rules from the ruleset table
$query = "SELECT * FROM ruleset ORDER BY rule_ID ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Rules</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    table {
      width: 100%;
      max-width: 1100px;
      border-collapse: collapse;
      margin: 30px auto;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
      font-size: 15px;
    }

    th {
      background-color: #2e8b57;
      color: white;
    }

    .admin-container {
      width: 100vw;
      min-height: 100vh;
      padding: 30px;
      background-color: #f9f9f9;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .dashboard-link {
      margin-top: 20px;
      display: inline-block;
      background-color: #2e8b57;
      padding: 12px 20px;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .dashboard-link:hover {
      background-color: #246c46;
    }

    h1 {
      color:rgb(7, 7, 7);
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <h1>📜 Rules & Deductions</h1>

    <table>
      <tr>
        <th>Rule ID</th>
        <th>Description</th>
        <th>Offense Type</th>
        <th>Points to Deduct</th>
        <th>Punishment</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['rule_ID']; ?></td>
          <td><?php echo $row['rule_description']; ?></td>
          <td><?php echo $row['offenseType']; ?></td>
          <td><?php echo $row['pointsToDeduct']; ?></td>
          <td><?php echo $row['punishment']; ?></td>
        </tr>
      <?php } ?>
    </table>

    <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php mysqli_close($conn); ?>
