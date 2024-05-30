<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['userName'];
$foodID = isset($_POST['foodID']) ? intval($_POST['foodID']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

if ($foodID <= 0 || empty($comment) || $rating <= 0 || $rating > 5) {
    echo "Invalid input.";
    exit();
}

$query = $conn->prepare("INSERT INTO comments (foodID, userName, comment, rating) VALUES (?, ?, ?, ?)");
$query->bind_param("issi", $foodID, $userName, $comment, $rating);

if ($query->execute()) {
    header("Location: food_detail?id=$foodID");
    exit();
} else {
    echo "Failed to add comment.";
}
?>
