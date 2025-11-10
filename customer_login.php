<?php
session_start();
include 'db.php';

// detect bg files
$body_classes = 'page-customer-login';
if (file_exists(__DIR__ . '/assets/images/bg-customer-login.jpg')) $body_classes .= ' has-bg';
if (file_exists(__DIR__ . '/assets/images/bg-forms.jpg')) $body_classes .= ' forms-has-bg';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM customers WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['customer_id'] = $row['id'];
        $_SESSION['customer_name'] = $row['name'];
        header("Location: menu.php");
        exit;
    } else {
        echo "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Customer Login</title>
<link rel="stylesheet" href="/restaurant_system/assets/styles.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="<?php echo $body_classes; ?>">
<div class="site-container">
<h2>Customer Login</h2>
<div class="effect-panel">
<form method="POST">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</div>
<p><a href="customer_register.php">Register here</a></p>
</div>
</body>
</html>