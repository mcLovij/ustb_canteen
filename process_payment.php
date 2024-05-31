<?php
session_start(); // Ensure session is started
if (!isset($_SESSION['userName'])) {
    header("Location: login.phpppp");
    exit();
}
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['checkedItems']) && is_array($_POST['checkedItems'])) {
        $userName = $_SESSION['userName'];
        $itemChooseId = rand(10000000, 99999999);
        $totalCheckedItemsPrice = $_POST['totalCheckedItemsPrice'];

        // Calculate total number of items
        $totalItem = 0;
        foreach ($_POST['checkedItems'] as $foodId) {
            $stmt = $conn->prepare("SELECT quantity FROM shopping_cart WHERE chooseId = ?");
            $stmt->bind_param("i", $foodId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalItem += $row['quantity'];
            }
            $stmt->close();

            // Insert order details
            $stmt = $conn->prepare("INSERT INTO orders (orderId, userName, foodId, canteenId, status, quantity) 
                                    SELECT ?, ?, foodId, canteenId, 0, quantity FROM shopping_cart WHERE chooseId = ?");
            $stmt->bind_param("isi", $itemChooseId, $userName, $foodId);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
                exit();
            }
            $stmt->close();

            // Remove item from shopping cart
            $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE chooseId = ? AND userName = ?");
            $stmt->bind_param("is", $foodId, $userName);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
                exit();
            }
            $stmt->close();
        }

        // Insert payment history with total item count
        $stmt = $conn->prepare("INSERT INTO order_history (orderId, price, totalItem) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $itemChooseId, $totalCheckedItemsPrice, $totalItem);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
            exit();
        }
        $stmt->close();

        header("Location: welcome");
        exit();
    } else {
        header("Location: welcome");
        exit();
    }
} else {
    header("Location: welcome");
    exit();
}
?>
