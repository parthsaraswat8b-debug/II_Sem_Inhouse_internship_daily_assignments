<?php
$name = "";
$email = "";
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"] ?? "");
    $email = htmlspecialchars($_POST["email"] ?? "");
    $submitted = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .box {
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }
        button {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            border-radius: 6px;
        }
        .message {
            background: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="box">
        <?php if ($submitted): ?>
            <div class="message">
                <h2>Welcome, <?php echo $name; ?>!</h2>
                <p>Thank you for registering.</p>
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
            </div>
        <?php endif; ?>

        <h2>Registration Form</h2>
        <form method="post" action="">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>