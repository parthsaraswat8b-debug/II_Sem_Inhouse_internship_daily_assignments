<?php
require_once '../config/db.php';
session_start();

if (isset($_SESSION['user'])) {
    header('Location: ../students/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header('Location: ../students/index.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm p-4">
      <h3 class="mb-3">Login</h3>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>