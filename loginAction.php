<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['userType'])) {
        $userType = $_POST['userType'];
        if ($userType === "student") {
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
        } elseif ($userType === "staff") {
            if(isset($_POST['staffUserName']) && isset($_POST['staffPassword'])) {
                $staffUserName = $_POST['staffUserName'];
                $staffPassword = $_POST['staffPassword'];

                // Sanitize inputs to prevent SQL injection
                $staffUserName = $conn->real_escape_string($staffUserName);
                $staffPassword = $conn->real_escape_string($staffPassword);

                $sql = "SELECT * FROM staff WHERE staffUserName = '$staffUserName' AND password = '$staffPassword'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Login successful
                    session_start();
                    $_SESSION['staffUserName'] = $staffUserName;
                    header("Location: welcomes"); // Redirect to staff welcome page
                    exit();
                } else {
                    // Login failed
                    header("Location: login?error=1"); // Redirect to login page with error parameter
                    exit();
                }
            } else {
                // echo "Please provide both username and password";
            }
        } else {
            // Invalid user type
            header("Location: login?error=1"); // Redirect to login page with error parameter
            exit();
        }
    } else {
        // Invalid request
        header("Location: login?error=1"); // Redirect to login page with error parameter
        exit();
    }
}

$conn->close();
?>
