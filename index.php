
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php";
?>
<?php
require_once "db.php";

// Get all flowers from the database
$sql = "SELECT id, name, description, price, stock FROM flowers ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Unable to load flowers: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Flower Shop</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff5f8;
            margin: 0;
            color: #333;
        }

        header {
            background: #d63384;
            color: white;
            padding: 25px;
            text-align: center;
        }

        nav {
            background: #fff;
            padding: 15px;
            text-align: center;
        }

        nav a {
            margin: 0 12px;
            color: #c2185b;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 25px auto;
        }

        .flowers {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .flower-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px #ddd;
        }

        .flower-card h2 {
            color: #c2185b;
        }

        .price {
            color: #198754;
            font-size: 20px;
            font-weight: bold;
        }

        input, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin: 7px 0 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background: #d63384;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #ad2167;
        }

        button:disabled {
            background: #999;
            cursor: not-allowed;
        }

        .empty {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #d63384;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>

<body>

<header>
    <h1>🌸 Online Flower Shop 🌸</h1>
    <p>Beautiful flowers for every special moment</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="flowers.php">Manage Flowers</a>
    <a href="orders.php">View Orders</a>
</nav>

<div class="container">
    <h2>Available Flowers</h2>
    <p>Choose your favourite flowers and place an order below.</p>

    <?php if ($result->num_rows > 0): ?>
        <div class="flowers">

            <?php while ($flower = $result->fetch_assoc()): ?>
                <div class="flower-card">

                    <h2>
                        <?php echo htmlspecialchars($flower['name']); ?>
                    </h2>

                    <p>
                        <?php
                        echo nl2br(htmlspecialchars(
                            $flower['description'] ?? ''
                        ));
                        ?>
                    </p>

                    <p class="price">
                        KSh <?php echo number_format((float)$flower['price'], 2); ?>
                    </p>

                    <p>
                        Available stock:
                        <?php echo (int)$flower['stock']; ?>
                    </p>

                    <?php if ((int)$flower['stock'] > 0): ?>

                        <h3>Place Your Order</h3>

                        <form action="place_order.php" method="POST">

                            <input
                                type="hidden"
                                name="flower_id"
                                value="<?php echo (int)$flower['id']; ?>"
                            >

                            <label>Your Name</label>
                            <input
                                type="text"
                                name="customer_name"
                                maxlength="100"
                                required
                            >

                            <label>Phone Number</label>
                            <input
                                type="text"
                                name="phone"
                                maxlength="30"
                                required
                            >

                            <label>Delivery Address</label>
                            <textarea
                                name="delivery_address"
                                rows="3"
                                required
                            ></textarea>

                            <label>Quantity</label>
                            <input
                                type="number"
                                name="quantity"
                                min="1"
                                max="<?php echo (int)$flower['stock']; ?>"
                                value="1"
                                required
                            >

                            <button type="submit">Place Order</button>
                        </form>

                    <?php else: ?>
                        <p><strong>Out of stock</strong></p>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>

        </div>

    <?php else: ?>
        <p class="empty">
            No flowers are available yet. Add flowers through the flower
            management page first.
        </p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Online Flower Shop. All rights reserved.</p>
</footer>

</body>
</html>
