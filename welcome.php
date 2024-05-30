<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

require_once "getStudentDetailAction.php"; // Include once

$userName = $_SESSION['userName'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="<?php require_once "getStudentDetailAction.php"; echo $profile; ?>">
    <title>你好<?php require_once "getStudentDetailAction.php"; echo $name; ?>同学</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showSection(sectionId, linkId) {
            const sections = ['dashboardSection', 'shoppingCartSection', 'newsSection', 'profileSection'];
            sections.forEach(id => {
                const section = document.getElementById(id);
                section.style.display = (id === sectionId ? 'block' : 'none');
            });

            const links = ['dashboardLink', 'orderLink', 'newsLink', 'profileLink'];
            links.forEach(id => {
                const link = document.getElementById(id);
                if (id === linkId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        window.onload = () => showSection('dashboardSection', 'dashboardLink');
    </script>
</head>
<body>
<div class="navbar">
    <img src="img/banner/logo.png" alt="贝壳食堂">
    <a href="#" onclick="showSection('dashboardSection', 'dashboardLink')" id="dashboardLink">首页</a>
    <a href="#gouwuche" onclick="showSection('shoppingCartSection', 'orderLink')" id="orderLink">购物车</a>
    <a href="#tixing" onclick="showSection('newsSection', 'newsLink')" id="newsLink">公告</a>
    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">
    <a href="#zhanghao" onclick="showSection('profileSection', 'profileLink')" id="profileLink"><?php require_once "getStudentDetailAction.php"; echo $name; ?></a>
</div>
<div id="dashboardSection"  style="display: none;">
    <?php require_once "getOrder.php"; ?>

        <?php require_once "getRecommendation.php"; ?>
        <?php require_once "getStudentFavorite.php"; ?>
    <?php require_once "getFoodList.php"; ?>
</div>

<div id="shoppingCartSection" style="display: none;">
    <?php require_once "getShoppingCart.php"; ?>
</div>

<div id="newsSection" style="display: none;">
    <?php require_once "getAnnouncement.php"; ?>
</div>

<div id="profileSection" style="display: none;">
    <?php require_once "getStudentDetailAction.php"; ?>
    <p>Your name: <?php echo $name; ?></p>
    <?php if (!empty($profile)): ?>
        <img style="height: 50px;" src="<?php echo $profile; ?>" alt="<?php echo $name; ?>"><br>
    <?php endif; ?>
    <form method="post" action="logout.php"><input type="submit" value="Logout"></form>
    <p>This is the welcome page. You are logged in as <?php echo $userName; ?>.</p>
</div>

</body>
</html>

