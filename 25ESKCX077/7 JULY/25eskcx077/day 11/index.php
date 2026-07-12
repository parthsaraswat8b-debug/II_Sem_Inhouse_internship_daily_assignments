<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Students</h3>
  <a href="create.php" class="btn btn-primary">Add Student</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Admission No</th>
          <th>Class</th>
          <th>Contact</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $s): ?>
          <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['admission_no']) ?></td>
            <td><?= htmlspecialchars($s['class']) ?></td>
            <td><?= htmlspecialchars($s['parent_contact']) ?></td>
            <td>
              <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $s['id'] ?>">Edit</a>
              <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>