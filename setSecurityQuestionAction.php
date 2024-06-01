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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $answers = array_filter([
        $_POST['answer1'] ?? null,
        $_POST['answer2'] ?? null,
        $_POST['answer3'] ?? null,
    ]);

    // Fetch user's stored password
    $passwordQuery = "SELECT password FROM students WHERE userName = ?";
    $stmt = $conn->prepare($passwordQuery);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the password
    if (password_verify($password, $storedPassword)) {
        // Delete existing entries for unanswered questions
        $deleteQuery = "DELETE FROM account_security WHERE userName = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('s', $userName);
        $stmt->execute();
        $stmt->close();

        // Insert answered questions and answers into account_security
        $insertQuery = "INSERT INTO account_security (questionID, userName, answer) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        foreach ($answers as $index => $answer) {
            if (!empty($answer)) {
                $questionId = $_POST["questionId" . ($index + 1)];
                $stmt->bind_param('iss', $questionId, $userName, $answer);
                $stmt->execute();
            }
        }

        $stmt->close();
        header("Location: welcome?message=" . urlencode('Security questions and answers have been set successfully.'));
    } else {
        header("Location: account?action=set_security&error=" . urlencode('Incorrect password.'));
        exit();
    }
}
?>
