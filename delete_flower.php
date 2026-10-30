
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: flowers.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare(
        "DELETE FROM flowers WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: flowers.php?deleted=1");
        exit;
    }

    $stmt->close();

    // A flower linked to an order cannot be deleted
    // because of the foreign key in the database.
    header("Location: flowers.php?error=linked");
    exit;
}

header("Location: flowers.php");
exit;
?>
