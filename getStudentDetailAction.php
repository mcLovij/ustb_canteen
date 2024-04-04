<?php
require_once "config.php"; // Include your database connection file

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

// Get the username from the session
$userName = $_SESSION['userName'];

// Fetch student details based on username
$sql = "SELECT * FROM student_detail WHERE userName = '$userName'";
$result = $conn->query($sql);

// Check if there is a result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $profile = $row['profile'];
} else {
    // Handle case where student details are not found
    $name = "Unknown";
    $profile = ""; // You can set a default profile image here or handle as needed
}

// Close database connection
?>