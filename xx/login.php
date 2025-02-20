<?php
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                session_regenerate_id(true);
                header("Location: home.html");
                exit();
            } else {
                $_SESSION['msg1'] = "Invalid password";
                header("Location: login.php");
                exit();
                
            }
        } else {
            $_SESSION['msg2'] = "Username not found";
            header("Location: login.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Login error: " . $e->getMessage());
        header("Location: login.php?error=1");
        exit();
    }
}
?>