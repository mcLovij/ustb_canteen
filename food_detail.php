<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}
$userName = $_SESSION['userName'];
$foodId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($foodId <= 0) {
    echo "Invalid food ID.";
    exit();
}

// Fetch the food item details
$query = $conn->prepare("SELECT * FROM food_list WHERE foodId = ?");
$query->bind_param("i", $foodId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Food item not found.";
    exit();
}

$food = $result->fetch_assoc();

// Fetch comments and calculate average rating
$commentsQuery = $conn->prepare("SELECT c.comment, c.rating, sd.name, sd.profile
FROM comments c
INNER JOIN student_detail sd ON c.userName = sd.userName
WHERE c.foodID = ?");
$commentsQuery->bind_param("i", $foodId);
$commentsQuery->execute();
$commentsResult = $commentsQuery->get_result();

$totalRating = 0;
$ratingCount = 0;
while ($comment = $commentsResult->fetch_assoc()) {
    $totalRating += $comment['rating'];
    $ratingCount++;
    $comments[] = $comment;
}

$averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : "No ratings yet";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo htmlspecialchars($food['foodImage']); ?>">
    <title><?php echo htmlspecialchars($food['foodName']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <img src="img/banner/logo.png" alt="贝壳食堂">
    <a href="welcome#" onclick="showDashboard()" id="dashboardLink">首页</a>
    <a href="welcome#gouwuche" onclick="showSection('shoppingCartSection')" id="orderLink">购物车</a>
    <a href="welcome#tixing" onclick="showSection('newsSection')" id="newsLink">公告</a>
    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">
    <a href="welcome#zhanghao" onclick="showSection('profileSection')" id="profileLink"><?php require_once "getStudentDetailAction.php"; echo $name; ?></a>
</div>

<h1><?php echo htmlspecialchars($food['foodName']); ?></h1>
<img src="<?php echo htmlspecialchars($food['foodImage']); ?>" alt="<?php echo htmlspecialchars($food['foodName']); ?>">
<p><?php echo htmlspecialchars($food['foodDetail']); ?></p>
<p>Price: <?php echo htmlspecialchars($food['foodPrice']); ?></p>
<p>Rating: <?php echo htmlspecialchars($averageRating); ?></p>

<div class="comments">
    <div class="comment">
        <div class="comment-title">评论</div>
        <div class="comment-sections">
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="comment-section">
                        <div class="profile-pic">
                            <img src="<?php echo htmlspecialchars($comment['profile']); ?>" alt="<?php echo htmlspecialchars($comment['name']); ?>">
                        </div>
                        <div class="details">
                            <div class="name"> <?php echo htmlspecialchars($comment['name']); ?></div>
                            <div class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                            <div class="rating"><?php echo htmlspecialchars($comment['rating']); ?>分</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="add-comment">
        <div class="add-comment-title">添加评论</div>
        <form action="add_comment.php" method="post" class="feedback-form">
            <input type="hidden" name="foodID" value="<?php echo htmlspecialchars($foodId); ?>">
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5">
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>
            </div>
            <div class="comment">
                <label for="comment" class="form-label">Comment:</label>
                <textarea id="comment" name="comment" class="form-control" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</div>
</body>
</html>
