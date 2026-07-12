<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <title>Student Management System</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/students/index.php">SMS</a>
    <div class="ms-auto d-flex gap-2">
      <a class="btn btn-outline-light btn-sm" href="/students/index.php">Students</a>
      <a class="btn btn-outline-light btn-sm" href="/grades/index.php">Grades</a>
      <a class="btn btn-outline-light btn-sm" href="/attendance/index.php">Attendance</a>
      <a class="btn btn-warning btn-sm" href="/auth/logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-4">