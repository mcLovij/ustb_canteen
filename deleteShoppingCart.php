<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

// Include config.php to establish database connection
require_once "config.php";

// Check if the request method is POST and chooseId is set in the POST data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chooseId'])) {
    // Get the username from the session
    $userName = $_SESSION['userName'];

    // Sanitize the chooseId input
    $chooseId = intval($_POST['chooseId']);

    // Prepare and execute the SQL statement to delete the item
    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE chooseId = ? AND userName = ?");
    $stmt->bind_param("is", $chooseId, $userName);
    $stmt->execute();

    // Check if any rows were affected (item deleted)
    if ($stmt->affected_rows > 0) {
        echo "Item deleted successfully.";
    } else {
        echo "Failed to delete item or item does not exist.";
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the shopping cart page if accessed without proper parameters
    header("Location: shopping_cart.php");
    exit();
}
?>
