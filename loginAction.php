<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['userName']) && isset($_POST['password'])) {
        $userName = $_POST['userName'];
        $password = $_POST['password'];
        
        // Sanitize inputs to prevent SQL injection
        $userName = $conn->real_escape_string($userName);
        $password = $conn->real_escape_string($password);
        
        $sql = "SELECT * FROM students WHERE userName = '$userName' AND password = '$password'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            // Login successful
            session_start();
            $_SESSION['userName'] = $userName;
            header("Location: welcome"); // Redirect to welcome page
            exit();
        } else {
            // Login failed
            header("Location: login?error=1"); // Redirect to login page with error parameter
            exit();
        }
    } else {
        // echo "Please provide both username and password";
    }
}

$conn->close();
?>
