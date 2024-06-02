<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

require_once "getStudentDetailAction.php";

$userName = $_SESSION['userName'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="<?php require_once "getStudentDetailAction.php"; echo $profile; ?>">
    <title><?php require_once "getStudentDetailAction.php"; echo $name; ?> 同学好</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showSection(sectionId, linkId) {
            const sections = ['dashboardSection', 'shoppingCartSection', 'newsSection','ordersSection', 'profileSection'];
            sections.forEach(id => {
                const section = document.getElementById(id);
                section.style.display = (id === sectionId ? 'block' : 'none');
            });

            const links = ['dashboardLink', 'gouwucheLink', 'newsLink','ordersLink', 'profileLink'];
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
    <a href="#gouwuche" onclick="showSection('shoppingCartSection', 'gouwucheLink')" id="gouwucheLink">购物车</a>
    <a href="#tixing" onclick="showSection('newsSection', 'newsLink')" id="newsLink">公告</a>
    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="食物名称搜索...">
    <a href="#orders" onclick="showSection('ordersSection', 'ordersLink')" id="ordersLink">Orders</a>
    <a href="#zhanghao" onclick="showSection('profileSection', 'profileLink')" id="profileLink"><?php require_once "getStudentDetailAction.php"; echo $name; ?></a>
</div>
<div id="dashboardSection"  style="display: none;">
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
<div id="ordersSection"  style="display: none;">
    <?php require_once "getOrder.php"; ?>
</div>
<div id="profileSection" style="display: none;">
    <div class="profile-section">
        <div class="profile-image-container">
            <div class="profile-image <?php
            // Count the number of questions answered by the user
            $countQuery = "SELECT COUNT(*) AS answeredCount FROM account_security WHERE userName = ?";
            if ($countStmt = $conn->prepare($countQuery)) {
                $countStmt->bind_param("s", $userName);
                $countStmt->execute();
                $countStmt->bind_result($answeredCount);
                $countStmt->fetch();
                $countStmt->close();

                // Determine the class based on answeredCount
                if ($answeredCount == 0) {
                    echo "weak";
                } elseif ($answeredCount == 1 || $answeredCount == 2) {
                    echo "medium";
                } elseif ($answeredCount >= 3) {
                    echo "better";
                }
            } else {
                echo "error";
            }
            ?>">
                <?php if (!empty($profile)): ?>
                    <img  src="<?php echo $profile; ?>" alt="<?php echo $name; ?>"><br>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile-infos">
            <?php require_once "getStudentDetailAction.php"; ?>
            <div class="profile-info">
                <div class="profile-info-name-and-status">
                    <div class="profile-info-name"><?php echo $name; ?>同学</div>
                    <div class="profile-info-status">Level: <?php  echo $answeredCount;?></div>
                </div>
                <div onclick='redirectToAccountActionPage("edit_information")' class="profile-info-edit">🖊️编辑个人信息</div>
            </div>
            <div class="profile-info-action">
                <div onclick='redirectToAccountActionPage("set_security")' class="account-security">Account Security</div>
                <div onclick='redirectToAccountActionPage("reset_password")' class="reset-password">Reset Password</div>
            </div>

            <script>
                function redirectToAccountActionPage(action) {
                    window.location.href = 'account?action=' + action;
                }
            </script>

            <form method="post" action="logout.php">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>
</div>


</body>
</html>

