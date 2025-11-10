<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle status update first
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$order_id");
}

// Fetch latest orders AFTER update
$orders = mysqli_query($conn, "
    SELECT orders.*, customers.name 
    FROM orders 
    JOIN customers ON orders.customer_id = customers.id 
    ORDER BY orders.id DESC
");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title>
<link rel="stylesheet" href="/restaurant_system/assets/styles.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<div class="site-container">
<h2>Welcome, Admin!</h2>
<table border="1" cellpadding="5">
<tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Items</th>
    <th>Total</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php while($row = mysqli_fetch_assoc($orders)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['items']; ?></td>
    <td>₱<?php echo $row['total_amount']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
            <select name="status">
                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                <option value="Checked" <?php if($row['status']=='Checked') echo 'selected'; ?>>Checked</option>
                <option value="Paid" <?php if($row['status']=='Paid') echo 'selected'; ?>>Paid</option>
            </select>
            <button type="submit" name="update_status">Update</button>
        </form>
    </td>
</tr>
<?php } ?>
</table>

<p><a href="logout.php">Logout</a></p>
</div>
</body>
</html>
