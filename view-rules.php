<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "CBC_POINT_DEDUCTION_SYSTEM");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// ADD rule
if (isset($_POST['add_rule'])) {
  $desc = trim($_POST['rule_description']);
  $type = trim($_POST['offenseType']);
  $points = (int) $_POST['pointsToDeduct'];
  $punishment = trim($_POST['punishment']);

  $sql = "INSERT INTO ruleset (rule_description, offenseType, pointsToDeduct, punishment)
          VALUES ('$desc', '$type', '$points', '$punishment')";
  mysqli_query($conn, $sql);
  header("Location: view-rules.php"); exit();
}

// DELETE rule
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  mysqli_query($conn, "DELETE FROM ruleset WHERE rule_ID = $id");
  header("Location: view-rules.php"); exit();
}

// UPDATE rule
if (isset($_POST['edit_rule'])) {
  $id = (int) $_POST['rule_ID'];
  $desc = trim($_POST['rule_description']);
  $type = trim($_POST['offenseType']);
  $points = (int) $_POST['pointsToDeduct'];
  $punishment = trim($_POST['punishment']);

  $sql = "UPDATE ruleset SET rule_description='$desc', offenseType='$type',
          pointsToDeduct='$points', punishment='$punishment' WHERE rule_ID = $id";
  mysqli_query($conn, $sql);
  header("Location: view-rules.php"); exit();
}

$rules = mysqli_query($conn, "SELECT * FROM ruleset ORDER BY rule_ID ASC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Rules</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    form, table { width: 100%; max-width: 1000px; margin: auto; }
    input, select { width: 100%; padding: 8px; margin: 5px 0; }
    .btn { padding: 8px 16px; background: #2e8b57; color: white; border: none; cursor: pointer; }
    .btn:hover { background: #246c46; }
    table { border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #2e8b57; color: white; }
    .actions a { margin: 0 5px; text-decoration: none; color: #e74c3c; }
    .edit-form { background: #f4f4f4; padding: 15px; margin-top: 20px; border: 1px solid #ccc; }
  </style>
</head>
<body>
<div class="admin-container">
  <h1>📜 Manage Rules</h1>

  <!-- ADD Rule Form -->
  <form method="POST">
    <h3>➕ Add New Rule</h3>
    <input type="text" name="rule_description" placeholder="Rule Description" required>
    <input type="text" name="offenseType" placeholder="Offense Type" required>
    <input type="number" name="pointsToDeduct" placeholder="Points to Deduct" required>
    <input type="text" name="punishment" placeholder="Punishment (e.g., Warning)" required>
    <button type="submit" name="add_rule" class="btn">Add Rule</button>
  </form>

  <!-- Existing Rules -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Description</th>
        <th>Offense Type</th>
        <th>Points</th>
        <th>Punishment</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($rules)): ?>
        <tr>
          <td><?= $row['rule_ID'] ?></td>
          <td><?= htmlspecialchars($row['rule_description']) ?></td>
          <td><?= htmlspecialchars($row['offenseType']) ?></td>
          <td><?= $row['pointsToDeduct'] ?></td>
          <td><?= htmlspecialchars($row['punishment']) ?></td>
          <td class="actions">
            <a href="view-rules.php?edit=<?= $row['rule_ID'] ?>">✏️</a>
            <a href="view-rules.php?delete=<?= $row['rule_ID'] ?>" onclick="return confirm('Delete this rule?')">🗑️</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Edit Form -->
  <?php
    if (isset($_GET['edit'])):
      $editID = (int) $_GET['edit'];
      $editRule = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM ruleset WHERE rule_ID = $editID"));
  ?>
  <div class="edit-form">
    <h3>✏️ Edit Rule ID <?= $editID ?></h3>
    <form method="POST">
      <input type="hidden" name="rule_ID" value="<?= $editRule['rule_ID'] ?>">
      <input type="text" name="rule_description" value="<?= htmlspecialchars($editRule['rule_description']) ?>" required>
      <input type="text" name="offenseType" value="<?= htmlspecialchars($editRule['offenseType']) ?>" required>
      <input type="number" name="pointsToDeduct" value="<?= $editRule['pointsToDeduct'] ?>" required>
      <input type="text" name="punishment" value="<?= htmlspecialchars($editRule['punishment']) ?>" required>
      <button type="submit" name="edit_rule" class="btn">Save Changes</button>
    </form>
  </div>
  <?php endif; ?>

  <br>
  <a href="admin-dashboard.php" class="dashboard-link">← Back to Dashboard</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
