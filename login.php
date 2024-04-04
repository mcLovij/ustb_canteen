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
    <input type="submit" value="Login">
</form>

</body>
</html>
