<?php
require_once "config.php";

// Function to generate a random 6-digit number
function generateRandomId() {
    return mt_rand(10000000, 99999999);
}

// Check if all required parameters are set
if(isset($_POST['foodId'], $_POST['locationId'], $_POST['quantity'])) {
    // Sanitize inputs
    $foodId = mysqli_real_escape_string($conn, $_POST['foodId']);
    $locationId = mysqli_real_escape_string($conn, $_POST['locationId']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // Start the session if not started already
    session_start();

    // Assuming you have a session variable for the logged-in user
    // If not, you should handle the username securely
    $userName = $_SESSION['userName'];

    // Generate a random chooseId that is not already in the shopping_cart table
    do {
        $chooseId = generateRandomId();
        $check_query = "SELECT chooseId FROM shopping_cart WHERE chooseId = $chooseId";
        $check_result = $conn->query($check_query);
    } while ($check_result->num_rows > 0);

    // Insert order into the database
    $sql = "INSERT INTO `shopping_cart` (chooseId, userName, foodId, canteenId, quantity) VALUES ('$chooseId', '$userName', '$foodId', '$locationId', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        echo "Order added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: Required parameters are missing";
}
?>
