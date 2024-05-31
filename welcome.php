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
    <style>
        .profile-section{
            width: 85%;
            display: flex;
            flex-direction: row;
            margin: auto;
            padding-top: 20px;
        }
        .profile-image-container{
            /*width: 200px;*/

        }
        .profile-image{
            width: 100px;
            height: 100px;
            border-radius: 100px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px;
        }
        .profile-image img{
            width: 99%;
        }
        .profile-infos{
            width: 100%;
            padding-left: 20px;
            display: flex;
            flex-direction: column;
            /*border: red solid 1px;*/
        }
        .profile-info{
            border-bottom: gray 1px solid;
            padding-bottom: 15px;
            width: 80%;
            display: flex;
            flex-direction: row;
            margin-right: 20%;
        }
        .profile-info-edit{
            margin-left: auto;
            padding: 8px 20px;
            border: gray 1px solid;
            border-radius: 50px;
            align-items: center;
            justify-content: center;
        }



        .account-security, .reset-password {
            padding: 20px;
            margin: 10px 0;
            border: 2px solid #14b114;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .account-security:hover, .reset-password:hover {
            background-color: #14b114;
            color: white;
        }

        .account-security a, .reset-password a {
            text-decoration: none;
            color: inherit;
            font-weight: bold;
        }

        .account-security a:hover, .reset-password a:hover {
            text-decoration: underline;
        }
    </style>



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
                    <div class="profile-info-status">Level<?php  echo $answeredCount;?></div>
                </div>
                <div class="profile-info-edit">🖊️编辑个人信息</div>
            </div>
            <div class="profile-info-action">
                <a href="setsecurity"><div class="account-security">Account Security</div></a>
                <a href="resetpassword"><div class="reset-password">ResetPassword</div></a>
            </div>






            <form method="post" action="logout.php">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>





<!--    --><?php //require_once "getRecommendation.php"; ?>
<!--    --><?php //require_once "getStudentFavorite.php"; ?>
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
</div>


</body>
</html>

