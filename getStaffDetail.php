<?php
global $staffUserName;
require_once "config.php";
if (!isset($_SESSION['staffUserName'])) {
    header("Location: login");
    exit();
}
$userName = $_SESSION['staffUserName'];
$sql = "SELECT sd.*, l.canteenName
        FROM staff_detail sd
        JOIN `location` l ON l.canteenId = sd.canteenId
        WHERE sd.staffUserName = '$staffUserName';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffName = $row['staffName']; // Update this line to fetch the user's name
    $profile = $row['profile'];
    $canteenName = $row['canteenName'];
} else {
    $name = "Unknown";
    $profile = "";
}
?>
