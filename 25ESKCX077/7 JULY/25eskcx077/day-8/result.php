<?php
function getGradeMessage($cgpa) {
    if ($cgpa >= 9) {
        return ["Excellent", "success"];
    } elseif ($cgpa >= 8) {
        return ["Very Good", "primary"];
    } elseif ($cgpa >= 6.5) {
        return ["Good", "warning"];
    } else {
        return ["Keep Improving", "danger"];
    }
}

$student_name = $_POST['student_name'] ?? '';
$email = $_POST['email'] ?? '';
$cgpa = $_POST['cgpa'] ?? '';
$branch = $_POST['branch'] ?? '';
$college = $_POST['college'] ?? '';

list($grade, $color) = getGradeMessage((float)$cgpa);
?>

<?php include 'header.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-dark text-white">
          <h3 class="mb-0">Student Confirmation</h3>
        </div>
        <div class="card-body">
          <div class="alert alert-<?php echo $color; ?>">
            <h4 class="mb-0">Hello, <?php echo htmlspecialchars($student_name); ?>!</h4>
            <p class="mb-0">Your performance is: <strong><?php echo $grade; ?></strong></p>
          </div>

          <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
          <p><strong>CGPA:</strong> <?php echo htmlspecialchars($cgpa); ?></p>
          <p><strong>Branch:</strong> <?php echo htmlspecialchars($branch); ?></p>
          <p><strong>College:</strong> <?php echo htmlspecialchars($college); ?></p>
          <p><strong>Date:</strong> <?php echo date("l, F j, Y"); ?></p>

          <a href="index.php" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>