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
    <title>Welcome</title>
    <style>
        .navbar {
            overflow: hidden;
            background-color: #ffffff;
        }

        .navbar a {
            float: left;
            display: block;
            color: #b0b0b0;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            color: black;
            border-radius: 10px;
        }

        .navbar a:hover {
            background-color: rgba(72, 71, 71, 0.65);
            color: white;
        }

        .active {
            background-color: #383838;
            color: white!important;
        }

    </style>
    <script>
        function showDashboard() {
            document.getElementById('dashboardSection').style.display = 'block';
            document.getElementById('shoppingCartSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'none';

            // Apply active class to the dashboard link
            document.getElementById('dashboardLink').classList.add('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.remove('active');
            document.getElementById('profileLink').classList.remove('active');
        }

        function showOrder() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('shoppingCartSection').style.display = 'block';
            document.getElementById('profileSection').style.display = 'none';

            // Apply active class to the order link
            document.getElementById('dashboardLink').classList.remove('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.add('active');
            document.getElementById('profileLink').classList.remove('active');
        }

        function showProfile() {
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('shoppingCartSection').style.display = 'none';
            document.getElementById('profileSection').style.display = 'block';

            // Apply active class to the profile link
            document.getElementById('dashboardLink').classList.remove('active');
            // Remove active class from other links
            document.getElementById('orderLink').classList.remove('active');
            document.getElementById('profileLink').classList.add('active');
        }
        window.onload = showDashboard;
    </script>
</head>
<body>
<div class="navbar">
    <a href="#" onclick="showDashboard()" id="dashboardLink" >Dashboard</a>
    <a href="#" onclick="showOrder()" id="orderLink">Shopping Cart</a>
    <a href="#" onclick="showProfile()" id="profileLink"><?php echo $userName; ?></a>
</div>


<div id="dashboardSection" style="display: none;">
    <!-- Display food list -->
    <h3>Student Favorite</h3>
    <?php require_once "getStudentFavorite.php"; ?>
    <h3>Food List</h3>
    <?php require_once "getFoodList.php"; ?>
</div>

<div id="shoppingCartSection" style="display: none;">
    <?php require_once "getShoppingCart.php"; ?>
</div>

<div id="profileSection" style="display: none;">
    <?php require_once "getStudentDetailAction.php"; ?>
    <p>Your name: <?php echo $name; ?></p>
    <?php if (!empty($profile)): ?>
        <img style="height: 50px;" src="<?php echo $profile; ?>" alt="Profile Image">
    <?php endif; ?>
    <form method="post" action="logout.php"><input type="submit" value="Logout"></form>
    <p>This is the welcome page. You are logged in as <?php echo $userName; ?>.</p>
</div>


</body>
</html>

