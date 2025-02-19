<?php
session_start();
require_once 'db_connect.php';
/*require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/Google/Service/Oauth2.php';


// Google OAuth configuration - Replace with your credentials
$client = new Google_Client();
$client->setClientId('INSERT_GOOGLE_CLIENT_ID_HERE');
$client->setClientSecret('INSERT_GOOGLE_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/login.php');
$client->addScope("email");
$client->addScope("profile");*/

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
                header("Location: home.html");
                exit();
            } else {
                $_SESSION['msg1'] = "Invalid password";
                header("Location: logino.php");
                
            }
        } else {
            $_SESSION['msg2'] = "Username not found";
            header("Location: logino.php");
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>