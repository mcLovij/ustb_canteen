<?php
session_start();

// 如果用户未登录，则重定向到登录页面
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

    // 检查新密码和确认密码是否匹配
    if ($newPassword !== $confirmPassword) {
        header("Location: account?action=reset_password&error=" . urlencode('新密码和确认密码不匹配。'));
        exit();
    }

    // 验证安全答案
    $verifyQuery = "SELECT answer FROM account_security WHERE userName = ? AND questionID = ?";
    if ($verifyStmt = $conn->prepare($verifyQuery)) {
        $verifyStmt->bind_param("si", $userName, $securityQuestionId);
        $verifyStmt->execute();
        $verifyStmt->bind_result($storedAnswer);
        $verifyStmt->fetch();
        $verifyStmt->close();

        // 检查答案是否匹配
        if ($securityAnswer === $storedAnswer) {
            // 重置密码逻辑
            $query = "SELECT password FROM students WHERE userName = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("s", $userName);
                $stmt->execute();
                $stmt->bind_result($storedPassword);
                $stmt->fetch();
                $stmt->close();

                // 检查存储的密码是否已经哈希（bcrypt哈希以$2y$开头）
                $isHashed = strpos($storedPassword, '$2y$') === 0;

                // 如果存储的密码未哈希，直接比较
                $passwordMatch = $isHashed ? password_verify($oldPassword, $storedPassword) : ($oldPassword === $storedPassword);

                if ($passwordMatch) {
                    // 如果存储的密码是明文，现在对其进行哈希
                    if (!$isHashed) {
                        $hashedOldPassword = password_hash($oldPassword, PASSWORD_DEFAULT);
                        $updateOldPasswordQuery = "UPDATE students SET password = ? WHERE userName = ?";
                        if ($updateOldStmt = $conn->prepare($updateOldPasswordQuery)) {
                            $updateOldStmt->bind_param("ss", $hashedOldPassword, $userName);
                            $updateOldStmt->execute();
                            $updateOldStmt->close();
                        } else {
                            header("Location: account?action=reset_password&error=" . urlencode('更新旧密码为哈希值时出错。'));
                            exit();
                        }
                    }

                    // 哈希新密码
                    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // 更新数据库中的密码
                    $updateQuery = "UPDATE students SET password = ? WHERE userName = ?";
                    if ($updateStmt = $conn->prepare($updateQuery)) {
                        $updateStmt->bind_param("ss", $newHashedPassword, $userName);
                        if ($updateStmt->execute()) {
                            header("Location: logout?message=" . urlencode('密码已成功更新。您将被注销。'));
                        } else {
                            header("Location: account?action=reset_password&error=" . urlencode('更新密码时出错。'));
                        }
                        $updateStmt->close();
                    }
                } else {
                    header("Location: account?action=reset_password&error=" . urlencode('旧密码验证失败。'));
                }
            } else {
                header("Location: account?action=reset_password&error=" . urlencode('准备语句时出错。'));
            }
        } else {
            header("Location: account?action=reset_password&error=" . urlencode('安全答案验证失败。'));
            exit();
        }
    } else {
        header("Location: account?action=reset_password&error=" . urlencode('准备语句时出错。'));
        exit();
    }

    $conn->close();
}
?>
