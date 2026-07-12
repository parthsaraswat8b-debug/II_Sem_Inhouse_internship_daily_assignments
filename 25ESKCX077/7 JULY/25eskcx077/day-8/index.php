<?php include 'header.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h3 class="mb-0">Student Registration Form</h3>
        </div>
        <div class="card-body">
          <form action="result.php" method="post">
            <div class="mb-3">
              <label class="form-label">Student Name</label>
              <input type="text" name="student_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">CGPA</label>
              <input type="number" step="0.01" min="0" max="10" name="cgpa" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Branch</label>
              <input type="text" name="branch" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">College Name</label>
              <input type="text" name="college" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>