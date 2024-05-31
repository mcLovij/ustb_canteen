<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

require_once "getStudentDetailAction.php";
require_once "config.php";

$userName = $_SESSION['userName'];

// Fetch security questions from the database
$questionQuery = "SELECT questionId, question FROM questions_list";
$questionsResult = $conn->query($questionQuery);

// Check if questions are fetched successfully
if (!$questionsResult) {
    echo "Error fetching security questions: " . $conn->error;
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'set_security') {
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo $profile; ?>">
    <title><?php echo $name; ?> 同学</title>
    <link rel="stylesheet" href="style.css">
    hahahahah
</head>
<body>
    <?php
    echo "set_security";
} elseif ($action == 'edit_information') {
    echo "edit_information";
    } elseif ($action == 'reset_password') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="icon" href="<?php echo $profile; ?>">
        <title><?php echo $name; ?> 真的要重置密码？</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
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
        <!-- Display security questions as options -->
        <?php
        if ($questionsResult->num_rows > 0) {
            echo "<label for='securityQuestion'>Security Question:</label>";
            echo "<select id='securityQuestion' name='securityQuestion' required>";
            while ($row = $questionsResult->fetch_assoc()) {
                echo "<option value='" . $row['questionId'] . "'>" . $row['question'] . "</option>";
            }
            echo "</select><br>";
        } else {
            echo "No security questions found.";
        }
        ?>
        <label for="securityAnswer">Security Answer:</label>
        <input type="text" id="securityAnswer" name="securityAnswer" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>
    </body>
    </html>
    <?php
} else {
    echo "Invalid action.";
}
?>
