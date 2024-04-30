<?php
session_start();
if (!isset($_SESSION['staffUserName'])) {
    header("Location: login");
    exit();
}
$staffUserName = $_SESSION['staffUserName'];
?>


<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="<?php require_once "getStaffDetail.php";
    echo $profile; ?>">
    <title>你好<?php require_once "getStaffDetail.php";
        echo $name; ?></title>
</head>
<body>
<?php require_once "getStaffDetail.php"; ?>
<p>Your name: <?php echo $name; ?></p>
<?php if (!empty($profile)): ?>
    <img style="height: 50px;" src="<?php echo $profile; ?>" alt="<?php echo $name; ?>"><br>
<?php endif; ?>
<p>Your canteenIs: <?php echo $canteenId; ?></p>
<form action="logout.php" method="post">
    <input type="submit" value="Logout">
</form>



<?php require_once "staffFoodList.php"; ?>



</body>
</html>
