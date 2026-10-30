
<?php
require 'db.php';

$sql = "SELECT orders.*, flowers.name AS flower_name
        FROM orders
        INNER JOIN flowers
        ON orders.flower_id = flowers.id
        ORDER BY orders.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Orders</title>
</head>
<body>

<h2>Customer Orders</h2>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Flower</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= (int)$row['id'] ?></td>
    <td><?= htmlspecialchars($row['customer_name']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= htmlspecialchars($row['flower_name']) ?></td>
    <td><?= (int)$row['quantity'] ?></td>
    <td>KSh <?= number_format((float)$row['total_price'], 2) ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
    <td>
        <a href="edit_order.php?id=<?= (int)$row['id'] ?>">
            Edit
        </a>

        <form action="delete_order.php" method="POST"
              style="display:inline"
              onsubmit="return confirm('Delete this order?')">
            <input type="hidden" name="id"
                   value="<?= (int)$row['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>

<a href="index.php">Back to Shop</a>

</body>
</html>
