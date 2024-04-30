<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Form</h2>

<?php
if(isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<p style='color:red;'>Invalid username or password</p>";
}
?>

<form method="post" action="loginAction.php">
    <label for="userName">Username:</label><br>
    <input type="text" id="userName" name="userName"><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="hidden" name="userType" value="student"> <!-- Add a hidden input field for user type -->
    <input type="submit" value="Student Login">
</form>

<form method="post" action="loginAction.php">
    <label for="staffUserName">Staff Username:</label><br>
    <input type="text" id="staffUserName" name="staffUserName"><br>
    <label for="staffPassword">Password:</label><br>
    <input type="password" id="staffPassword" name="staffPassword"><br><br>
    <input type="hidden" name="userType" value="staff"> <!-- Add a hidden input field for user type -->
    <input type="submit" value="Staff Login">
</form>

</body>
</html>
