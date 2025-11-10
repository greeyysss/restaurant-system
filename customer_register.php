<?php
include 'db.php';

// detect bg files
$body_classes = 'page-customer-register';
if (file_exists(__DIR__ . '/assets/images/bg-customer-register.jpg')) $body_classes .= ' has-bg';
if (file_exists(__DIR__ . '/assets/images/bg-forms.jpg')) $body_classes .= ' forms-has-bg';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "INSERT INTO customers (name, email, password)
            VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful! <a href='customer_login.php'>Login now</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Customer Registration</title>
<link rel="stylesheet" href="/restaurant_system/assets/styles.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="<?php echo $body_classes; ?>">
<div class="site-container">
<h2>Customer Registration</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
</form>
</div>
</body>
</html>
