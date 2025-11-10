<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Expecting an array: order[FoodName] => quantity
if (isset($_POST['order']) && is_array($_POST['order'])) {
    $order_input = $_POST['order'];

    // Prices list must match menu.php
  $prices = [
    "Milktea" => 39,
    "Fries" => 20,
    "Burger" => 90,
    "Coke" => 10,
    "Shawarma" => 60,
    "Takoyaki" => 60,
    "Frappe" => 99,
    "Siomai" => 10
];


    $items = [];
    $total = 0;

    foreach ($order_input as $food => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) continue;
        if (!isset($prices[$food])) continue; // ignore unknown items

        $line_total = $prices[$food] * $qty;
        $items[] = "$food x$qty";
        $total += $line_total;
    }

    if (empty($items)) {
        echo "⚠ No food or quantity selected. <a href='menu.php'>Go back</a>";
        exit;
    }

    $items_text = implode(', ', $items);

    $sql = "INSERT INTO orders (customer_id, items, total_amount, status)
            VALUES ('$customer_id', '" . mysqli_real_escape_string($conn, $items_text) . "', '$total', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        echo "<h3>✅ Order placed successfully!</h3>";
        echo "<p>Items: " . htmlspecialchars($items_text) . "</p>";
        echo "<p>Total: ₱" . number_format($total,2) . "</p>";
        echo "<p>Status: Pending (waiting for admin confirmation)</p>";
        echo "<a href='menu.php'>Back to Menu</a> | ";
        echo "<a href='my_orders.php'>View My Orders</a>";
    } else {
        echo "❌ Error placing order: " . mysqli_error($conn);
    }

} else {
    echo "⚠ No food or quantity selected. <a href='menu.php'>Go back</a>";
}
?>
