<?php
session_start();
include 'db.php';

// detect bg files
$body_classes = 'page-admin-login';
if (file_exists(__DIR__ . '/assets/images/bg-admin-login.jpg')) $body_classes .= ' has-bg';
if (file_exists(__DIR__ . '/assets/images/bg-forms.jpg')) $body_classes .= ' forms-has-bg';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
    
        echo "Invalid admin credentials!";
    }
}
?>

    <!DOCTYPE html>
<html>
<head><title>Admin Login</title>
<link rel="stylesheet" href="/restaurant_system/assets/styles.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="<?php echo $body_classes; ?>">
<div class="site-container">
<h2>Admin Login</h2>
<div class="effect-panel">
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</div>
</div>
</body>
</html>

?>