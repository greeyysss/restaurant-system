<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Handle payment
if (isset($_POST['pay_order'])) {
    $order_id = $_POST['order_id'];
    mysqli_query($conn, "UPDATE orders SET status='Paid' WHERE id=$order_id AND customer_id=$customer_id");
}

$orders = mysqli_query($conn, "SELECT * FROM orders WHERE customer_id=$customer_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>My Orders</title>
<link rel="stylesheet" href="/restaurant_system/assets/styles.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<div class="site-container">
<h2>My Orders</h2>
<table border="1" cellpadding="5">
<tr><th>Order ID</th><th>Items</th><th>Total</th><th>Status</th><th>Action</th></tr>
<?php
if (mysqli_num_rows($orders) > 0) {
    while ($row = mysqli_fetch_assoc($orders)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['items']}</td>";
        echo "<td>₱{$row['total_amount']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>";
        if ($row['status'] == 'Checked') {
            echo "
            <form method='POST'>
                <input type='hidden' name='order_id' value='{$row['id']}'>
                <button type='submit' name='pay_order'>Pay Now</button>
            </form>";
        } else {
            echo "-";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No orders found.</td></tr>";
}
?>
</table>

<p><a href="menu.php">Back to Menu</a> | <a href="logout.php">Logout</a></p>
</body>
</html>
