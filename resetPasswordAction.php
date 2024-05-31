<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login");
    exit();
}

require_once "config.php";

$userName = $_SESSION['userName'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $securityQuestionId = $_POST['securityQuestion'];
    $securityAnswer = $_POST['securityAnswer'];

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New password and confirmation do not match.'); window.location.href='welcome';</script>";
        exit();
    }

    // Verify security answer
    $verifyQuery = "SELECT answer FROM account_security WHERE userName = ? AND questionID = ?";
    if ($verifyStmt = $conn->prepare($verifyQuery)) {
        $verifyStmt->bind_param("si", $userName, $securityQuestionId);
        $verifyStmt->execute();
        $verifyStmt->bind_result($storedAnswer);
        $verifyStmt->fetch();
        $verifyStmt->close();

        // Check if answer matches
        if ($securityAnswer === $storedAnswer) {
            // Password reset logic
            $query = "SELECT password FROM students WHERE userName = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("s", $userName);
                $stmt->execute();
                $stmt->bind_result($storedPassword);
                $stmt->fetch();
                $stmt->close();

                // Check if the stored password is already hashed (bcrypt hashes start with $2y$)
                $isHashed = strpos($storedPassword, '$2y$') === 0;

                // If the stored password is not hashed, compare it directly
                $passwordMatch = $isHashed ? password_verify($oldPassword, $storedPassword) : ($oldPassword === $storedPassword);

                if ($passwordMatch) {
                    // If the stored password was in plain text, hash it now
                    if (!$isHashed) {
                        $hashedOldPassword = password_hash($oldPassword, PASSWORD_DEFAULT);
                        $updateOldPasswordQuery = "UPDATE students SET password = ? WHERE userName = ?";
                        if ($updateOldStmt = $conn->prepare($updateOldPasswordQuery)) {
                            $updateOldStmt->bind_param("ss", $hashedOldPassword, $userName);
                            $updateOldStmt->execute();
                            $updateOldStmt->close();
                        } else {
                            echo "<script>alert('Error updating old password to hashed value.'); window.location.href='resetpassword';</script>";
                            exit();
                        }
                    }

                    // Hash new password
                    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update password in database
                    $updateQuery = "UPDATE students SET password = ? WHERE userName = ?";
                    if ($updateStmt = $conn->prepare($updateQuery)) {
                        $updateStmt->bind_param("ss", $newHashedPassword, $userName);
                        if ($updateStmt->execute()) {
                            echo "<script>alert('Password has been successfully updated. You will be logged out.'); window.location.href='logout.php';</script>";
                        } else {
                            echo "<script>alert('Error updating password.'); window.location.href='resetpassword';</script>";
                        }
                        $updateStmt->close();
                    }
                } else {
                    echo "<script>alert('Old password verification failed.'); window.location.href='resetpassword';</script>";
                }
            } else {
                echo "<script>alert('Error preparing statement.'); window.location.href='welcome';</script>";
            }
        } else {
            echo "<script>alert('Security answer verification failed.'); window.location.href='welcome';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error preparing statement.'); window.location.href='welcome';</script>";
        exit();
    }

    $conn->close();
}
?>
