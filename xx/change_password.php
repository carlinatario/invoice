<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch email from the form
    $email = $_POST['email']; 
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if ($new_password !== $confirm_password) {
        die("New passwords do not match.");
    }

    try {
        // Retrieve user ID based on email
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_id = $user['id']; // Get user ID

            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                $_SESSION ['msg6'] = "Current password is incorrect.";
                header("Location: change_passwordo.php");
            }

            // Update password
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_hash, $user_id);
            $update_stmt->execute();

             $_SESSION ['msg5'] = "Password updated successfully!";
             header("Location: logino.php");
            exit();
        } else {
           $_SESSION ['msg6'] = "User not found with this email.";
            die();
        }
    } catch (mysqli_sql_exception $e) {
        die("Error changing password: " . $e->getMessage());
    }
}
?>

