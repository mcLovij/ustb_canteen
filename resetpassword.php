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

<head>
    <link rel="icon" href="<?php require_once "getStudentDetailAction.php"; echo $profile; ?>">
    <title><?php require_once "getStudentDetailAction.php"; echo $name; ?> 同学好</title>
    <link rel="stylesheet" href="style.css">

</head>
<form method="post" action="resetPasswordAction.php">
    <label for="oldPassword">Old Password:</label>
    <input type="password" id="oldPassword" name="oldPassword" required>
    <br>
    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required>
    <br>
    <label for="confirmPassword">Confirm New Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required>
    <br>
    <input type="submit" value="Reset Password">
</form>