<?php
require_once "config.php";
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}
$userName = $_SESSION['userName'];
$sql = "SELECT * FROM student_detail WHERE userName = '$userName'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name']; // Update this line to fetch the user's name
    $profile = $row['profile'];
} else {
    $name = "Unknown";
    $profile = "";
}
?>
