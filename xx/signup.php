<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_password = '1234'; // Set admin password

    if ($_POST['admin_pass'] !== $admin_password) {
        die("Invalid admin password");
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch existing records
        $existingUser = $result->fetch_assoc();
        
        if ($existingUser['username'] === $username && $existingUser['email'] === $email) {
            $_SESSION ['msg4'] = "Error: Email already registered. Please log in.";
            header("Location: logino.php");
        } elseif ($existingUser['email'] === $email) {
            $_SESSION ['msg4'] = "Error: Email already registered. Please log in.";
            header("Location: logino.php");
        }
        else if($existingUser['username'] === $username)
        {
            $_SESSION['msg3']= "Error: Username already taken. Please choose another one.";
            header("Location: signupo.php");
        }
        
        // Redirect to login page after 3 seconds
        
        exit();
    } else {
        // Insert new user if username and email are unique
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();
            
            header("location: logino.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
