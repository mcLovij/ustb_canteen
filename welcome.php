<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

// Get the username from the session
$userName = $_SESSION['userName'];
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="<?php require_once "getStudentDetailAction.php"; echo $profile; ?>">
    <title>你好<?php require_once "getStudentDetailAction.php"; echo $name; ?>同学</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            flex; align-items: center; justify-content: center
        }






        button {
            appearance: none;
            background-color: #2ea44f;
            border: 1px solid rgba(27, 31, 35, .15);
            border-radius: 6px;
            box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
            padding: 6px 16px;
            position: relative;
            text-align: center;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            white-space: nowrap;
        }

        button:focus:not(:focus-visible):not(.focus-visible) {
            box-shadow: none;
            outline: none;
        }

        button:hover {
            background-color: #2c974b;
        }

        button:focus {
            box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
            outline: none;
        }

        button:disabled {
            background-color: #94d3a2;
            border-color: rgba(27, 31, 35, .1);
            color: rgba(255, 255, 255, .8);
            cursor: default;
        }

        button:active {
            background-color: #298e46;
            box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
        }

        button.delete{
            background-color: #CB2027;
        }

        button.delete:hover {
            background-color: #D54D52;
        }












        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
            background-color: rgba(72, 71, 71, 0.65);
            color: white;
        }

        .active, .filter_active {
            background-color: #383838;
            color: white !important;
        }

        #searchInput {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        #profileLink {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #ff6f61;
            color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        #profileLink:hover {
            background-color: #dd544c;
        }


    </style>
    <script>
        function showDashboard() {
            document.getElementById('dashboardSection').style.display = 'block';
            document.getElementById('shoppingCartSection').style.display = 'none';
            document.getElementById('newsSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'none';

            // Apply active class to the dashboard link
            document.getElementById('dashboardLink').classList.add('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.remove('active');
            document.getElementById('newsLink').classList.remove('active');
            document.getElementById('profileLink').classList.remove('active');
        }

        function showOrder() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('shoppingCartSection').style.display = 'block';
            document.getElementById('newsSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'none';

            // Apply active class to the order link
            document.getElementById('dashboardLink').classList.remove('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.add('active');
            document.getElementById('newsLink').classList.remove('active');
            document.getElementById('profileLink').classList.remove('active');
        }

        function showNews() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('shoppingCartSection').style.display = 'none';
            document.getElementById('newsSection').style.display = 'block';
            document.getElementById('profileSection').style.display = 'none';

            // Apply active class to the order link
            document.getElementById('dashboardLink').classList.remove('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.remove('active');
            document.getElementById('newsLink').classList.add('active');
            document.getElementById('profileLink').classList.remove('active');
        }

        function showProfile() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('shoppingCartSection').style.display = 'none';
            document.getElementById('newsSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'block';

            // Apply active class to the profile link
            document.getElementById('dashboardLink').classList.remove('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.remove('active');
            document.getElementById('newsLink').classList.remove('active');
            document.getElementById('profileLink').classList.add('active');
        }

        window.onload = showDashboard;
    </script>
</head>
<body>
<div class="navbar">
    <a href="#" onclick="showDashboard()" id="dashboardLink">首页</a>
    <a href="#" onclick="showOrder()" id="orderLink">购物车</a>
    <a href="#" onclick="showNews()" id="newsLink">公告</a>
    <input type="text" id="searchInput" onkeyup="filterByName()" placeholder="Search by Food Name...">
    <a href="#" onclick="showProfile()" id="profileLink"><?php require_once "getStudentDetailAction.php"; echo $name; ?></a>

</div>
<div id="dashboardSection" style="display: none;">
     <?php require_once "getOrder.php"; ?>
<!--     <h3>Recommendation</h3>-->
<!--    --><?php //require_once "getRecommendation.php"; ?>
<!--    <h3>Student Favorite</h3>-->
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

