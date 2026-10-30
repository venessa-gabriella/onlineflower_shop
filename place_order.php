
<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$name = trim($_POST['customer_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['delivery_address'] ?? '');
$flower_id = (int)($_POST['flower_id'] ?? 0);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if ($name === '' || $phone === '' || $address === ''
    || $flower_id < 1 || !$quantity || $quantity < 1) {
    die("Please fill in all fields correctly.");
}

$conn->begin_transaction();

try {
    // Check the flower and lock its stock record.
    $stmt = $conn->prepare(
        "SELECT price, stock FROM flowers
         WHERE id = ? FOR UPDATE"
    );
    $stmt->bind_param("i", $flower_id);
    $stmt->execute();
    $flower = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$flower) {
        throw new Exception("Flower not found.");
    }

    if ($quantity > (int)$flower['stock']) {
        throw new Exception("Not enough flowers in stock.");
    }

    $total = (float)$flower['price'] * $quantity;

    // Save the order.
    $stmt = $conn->prepare(
        "INSERT INTO orders
        (customer_name, phone, delivery_address,
         flower_id, quantity, total_price)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssiid", $name, $phone, $address,
        $flower_id, $quantity, $total
    );
    $stmt->execute();
    $stmt->close();

    // Reduce available stock.
    $stmt = $conn->prepare(
        "UPDATE flowers SET stock = stock - ?
         WHERE id = ?"
    );
    $stmt->bind_param("ii", $quantity, $flower_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo "<script>
        alert('Thank you! Your flower order was placed successfully.');
        window.location.href = 'index.php';
    </script>";
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    echo "<script>alert(" .
         json_encode($e->getMessage()) .
         "); window.location.href='index.php';</script>";
    exit;
}
?>
