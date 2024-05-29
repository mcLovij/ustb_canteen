<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

// Get the username from the session
$userName = $_SESSION['userName'];

// Get the foodId from the URL
$foodId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($foodId <= 0) {
    echo "Invalid food ID.";
    exit();
}

// Sample data for illustration
$foods = [
    1 => ['foodName' => 'Pizza', 'foodImage' => 'images/pizza.jpg', 'description' => 'Delicious cheese pizza.'],
    2 => ['foodName' => 'Burger', 'foodImage' => 'images/burger.jpg', 'description' => 'Juicy beef burger.']
];

if (!isset($foods[$foodId])) {
    echo "Food item not found.";
    exit();
}

$food = $foods[$foodId];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($food['foodName']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <img src="img/banner/logo.png" alt="贝壳食堂">
    <a href="welcome#" onclick="showDashboard()" id="dashboardLink">首页</a>
    <a href="welcome#gouwuche" onclick="showSection('shoppingCartSection')" id="orderLink">购物车</a>
    <a href="welcome#tixing"" onclick="showSection('newsSection')" id="newsLink">公告</a>
    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">
    <a href="welcome#zhanghao" onclick="showSection('profileSection')" id="profileLink"><?php require_once "getStudentDetailAction.php"; echo $name; ?></a>
</div>

<h1><?php echo htmlspecialchars($food['foodName']); ?></h1>
<img src="<?php echo htmlspecialchars($food['foodImage']); ?>" alt="<?php echo htmlspecialchars($food['foodName']); ?>">
<p><?php echo htmlspecialchars($food['description']); ?></p>
<p>Welcome, <?php echo htmlspecialchars($userName); ?>!</p>
</body>
</html>
