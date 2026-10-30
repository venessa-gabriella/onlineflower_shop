
<?php
require 'db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM flowers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$flower = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$flower) {
    die("Flower not found.");
}

if (isset($_POST['update_flower'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if ($name !== '' && $price >= 0 && $stock >= 0) {
        $stmt = $conn->prepare(
            "UPDATE flowers
             SET name=?, description=?, price=?, stock=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "ssdii", $name, $description, $price, $stock, $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: flowers.php?updated=1");
            exit;
        }

        echo "Error updating flower.";
        $stmt->close();
    } else {
        echo "Please enter valid details.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Flower</title>
</head>
<body>

<h2>Update Flower Details</h2>

<form method="POST">
    Flower Name:
    <input type="text" name="name"
           value="<?= htmlspecialchars($flower['name']) ?>"
           required><br><br>

    Description:
    <textarea name="description"><?= htmlspecialchars(
        $flower['description'] ?? ''
    ) ?></textarea><br><br>

    Price:
    <input type="number" name="price" min="0" step="0.01"
           value="<?= htmlspecialchars((string)$flower['price']) ?>"
           required><br><br>

    Stock:
    <input type="number" name="stock" min="0"
           value="<?= (int)$flower['stock'] ?>"
           required><br><br>

    <button type="submit" name="update_flower">
        Update Flower
    </button>
</form>

<a href="flowers.php">Back to Flowers</a>

</body>
</html>
