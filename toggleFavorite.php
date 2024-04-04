<?php
session_start();

if (!isset($_SESSION['userName'])) {
    exit('Unauthorized access');
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['foodId']) && isset($_POST['status'])) {
        $userName = $_SESSION['userName'];
        $foodId = $_POST['foodId'];
        $status = $_POST['status'];

        // Check if the food item is already in the favorites list
        $sql_check = "SELECT * FROM student_favorite WHERE userName = '$userName' AND foodId = '$foodId'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0 && $status == 0) {
            // Remove food item from favorites list
            $sql_remove = "DELETE FROM student_favorite WHERE userName = '$userName' AND foodId = '$foodId'";
            if ($conn->query($sql_remove) === TRUE) {
                echo '0'; // Successfully removed from favorites
            } else {
                echo 'Error: ' . $conn->error;
            }
        } elseif ($result_check->num_rows == 0 && $status == 1) {
            // Add food item to favorites list
            $sql_add = "INSERT INTO student_favorite (userName, foodId) VALUES ('$userName', '$foodId')";
            if ($conn->query($sql_add) === TRUE) {
                echo '1'; // Successfully added to favorites
            } else {
                echo 'Error: ' . $conn->error;
            }
        } else {
            // No action needed
            echo $status;
        }
    }
} else {
    exit('Invalid request');
}

$conn->close();
?>
