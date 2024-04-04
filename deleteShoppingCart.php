<?php
// Include config.php to establish database connection
require_once "config.php";

if(isset($_POST['chooseId'])) {
    $chooseId = $_POST['chooseId'];

    // Delete order from the database
    $sql = "DELETE FROM `shopping_cart` WHERE `chooseId`='$chooseId'";
    if ($conn->query($sql) === TRUE) {
        echo "Order deleted successfully";
        header("Location: welcome");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: No chooseId provided";
}
?>
