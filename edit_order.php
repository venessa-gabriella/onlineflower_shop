
<?php
require 'db.php';

$id = (int)($_GET['id'] ?? 0);

if (isset($_POST['update_order'])) {
    $name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['delivery_address']);
    $status = $_POST['status'];

    $allowed = ['Pending', 'Confirmed', 'Delivered', 'Cancelled'];

    if ($name !== '' && $phone !== '' && $address !== ''
        && in_array($status, $allowed, true)) {

        $stmt = $conn->prepare(
            "UPDATE orders
             SET customer_name=?, phone=?,
                 delivery_address=?, status=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "ssssi", $name, $phone, $address, $status, $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: orders.php");
            exit;
        }

        echo "Error updating order.";
        $stmt->close();
    } else {
        echo "Please enter valid order details.";
    }
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
</head>
<body>

<h2>Update Customer Order</h2>

<form method="POST">
    Customer Name:
    <input name="customer_name"
           value="<?= htmlspecialchars($order['customer_name']) ?>"
           required><br><br>

    Phone:
    <input name="phone"
           value="<?= htmlspecialchars($order['phone']) ?>"
           required><br><br>

    Delivery Address:
    <textarea name="delivery_address" required><?= htmlspecialchars(
        $order['delivery_address']
    ) ?></textarea><br><br>

    Order Status:
    <select name="status">
        <?php foreach (
            ['Pending', 'Confirmed', 'Delivered', 'Cancelled']
            as $status
        ) { ?>
            <option value="<?= $status ?>"
                <?= $order['status'] === $status ? 'selected' : '' ?>>
                <?= $status ?>
            </option>
        <?php } ?>
    </select><br><br>

    <button type="submit" name="update_order">
        Update Order
    </button>
</form>

</body>
</html>
