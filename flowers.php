
<?php
require 'db.php';

// CREATE: Add a flower
if (isset($_POST['add_flower'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    if ($name !== '' && $price >= 0 && $stock >= 0) {
        $stmt = $conn->prepare(
            "INSERT INTO flowers (name, description, price, stock)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdi", $name, $description, $price, $stock
        );

        if ($stmt->execute()) {
            echo "<script>alert('Flower added successfully!');</script>";
        } else {
            echo "Error adding flower.";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter valid details.');</script>";
    }
}

// READ: Display all flowers
$result = $conn->query("SELECT * FROM flowers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Flowers</title>
</head>
<body>

<h2>Add New Flower</h2>

<form method="POST">
    Flower Name:
    <input type="text" name="name" required><br><br>

    Description:
    <textarea name="description"></textarea><br><br>

    Price:
    <input type="number" name="price"
           min="0" step="0.01" required><br><br>

    Stock:
    <input type="number" name="stock"
           min="0" required><br><br>

    <button type="submit" name="add_flower">
        Add Flower
    </button>
</form>

<h2>Flower Records</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= (int)$row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td>KSh <?= number_format((float)$row['price'], 2) ?></td>
    <td><?= (int)$row['stock'] ?></td>
    <td>
        <a href="edit_flower.php?id=<?= (int)$row['id'] ?>">
            Edit
        </a>

        <form method="POST" action="delete_flower.php"
              onsubmit="return confirm('Delete this flower?');"
              style="display:inline">
            <input type="hidden" name="id"
                   value="<?= (int)$row['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>
</body>
</html>
