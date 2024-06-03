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
        <title><?php echo $name; ?> Set Security Questions</title>
        <link rel="stylesheet" href="style.css">
        <script>
            function updateQuestions() {
                const selects = document.querySelectorAll('select');
                const selectedValues = Array.from(selects).map(select => select.value);

                selects.forEach(select => {
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (selectedValues.includes(option.value) && option.value !== select.value) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = '';
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const selects = document.querySelectorAll('select');
                selects.forEach(select => {
                    select.addEventListener('change', updateQuestions);
                });
                updateQuestions();
            });
        </script>
    </head>
    <body>
    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <form method="post" action="setSecurityQuestionAction.php">
        <?php
        if ($questionsResult->num_rows > 0) {
            $questions = [];
            while ($row = $questionsResult->fetch_assoc()) {
                $questions[] = $row;
            }
            for ($i = 1; $i <= 3; $i++) {
                echo "<label for='question$i'>Choose a question:</label><br>";
                echo "<select id='question$i' name='questionId$i' required>";
                echo "<option value=''>Select a question</option>";
                foreach ($questions as $question) {
                    echo "<option value='" . htmlspecialchars($question['questionId']) . "'>" . htmlspecialchars($question['question']) . "</option>";
                }
                echo "</select><br>";
                echo "<label for='answer$i'>Your Answer:</label><br>";
                echo "<input type='text' id='answer$i' name='answer$i'><br><br>";
            }
        } else {
            echo "No security questions found.";
        }
        ?>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Set Security Questions">
    </form>
    </body>
    </html>
    <?php
    echo "set_security";
} elseif ($action == 'edit_information') {
    echo "// TODO this part lol, edit_information";

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
    <?php if (isset($_GET['error'])): ?>
    <div class="error-message">
        <span class="error-text"><?php echo htmlspecialchars($_GET['error']); ?></span>
    </div>
    <?php endif; ?>
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
