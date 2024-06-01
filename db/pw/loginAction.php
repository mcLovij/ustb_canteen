<?php
global $conn;
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userType'])) {
        $userType = $_POST['userType'];

        if ($userType === "student") {
            if (isset($_POST['userName']) && isset($_POST['password'])) {
                $userName = $_POST['userName'];
                $password = $_POST['password'];

                // Sanitize inputs to prevent SQL injection
                $userName = $conn->real_escape_string($userName);

                // Get the hashed password from the database
                $sql = "SELECT password FROM students WHERE userName = '$userName'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $hashedPassword = $row['password'];

                    // Verify the input password against the hashed password
                    if (password_verify($password, $hashedPassword)) {
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
                    // Login failed
                    header("Location: login?error=1"); // Redirect to login page with error parameter
                    exit();
                }
            } else {
                // echo "Please provide both username and password";
            }
        } elseif ($userType === "staff") {
            if (isset($_POST['staffUserName']) && isset($_POST['staffPassword'])) {
                $staffUserName = $_POST['staffUserName'];
                $staffPassword = $_POST['staffPassword'];

                // Sanitize inputs to prevent SQL injection
                $staffUserName = $conn->real_escape_string($staffUserName);

                // Get the hashed password from the database
                $sql = "SELECT password FROM staff WHERE staffUserName = '$staffUserName'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $hashedPassword = $row['password'];

                    // Verify the input password against the hashed password
                    if (password_verify($staffPassword, $hashedPassword)) {
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
                    // Login failed
                    header("Location: login?error=1"); // Redirect to login page with error parameter
                    exit();
                }
            } else {
                // echo "Please provide both username and password";
            }
        }
        else {
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
