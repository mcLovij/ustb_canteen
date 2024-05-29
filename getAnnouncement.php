<?php
global $conn;
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}
require_once "config.php";
$userName = $_SESSION['userName'];
$sql = "SELECT * from announcement;";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcement Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .announcement-main-container {
            margin: auto;
            overflow: hidden;
            border-radius: 8px;
        }
        .announcement-main-container .title {
            text-align: center;
            color: #333;
            font-weight: bold!important;
        }
        .announcement-container {
            width: 85%;
            margin: auto;
            overflow: hidden;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 10px;
        }
        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 5px;
        }
        .announcement-content {
            font-size: 16px;
            color: #333;
        }
        .no-announcement {
            text-align: center;
            font-size: 18px;
            color: #999;
            padding: 20px 0;
        }
    </style>
</head>
<body>
<div class="announcement-main-container">
    <div class="announcement-container-main-title">公告</div>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='announcement-container'>";
            echo "<div class='announcement-title'>" . $row["title"] . "</div>";
            echo "<div class='announcement-content'>" . $row["content"] . "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-announcement'>No announcements available</div>";
    }
    ?>
</div>
</body>
</html>
