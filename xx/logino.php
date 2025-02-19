<?php
session_start();
/*<div class="google-login">
                <a href="login.php?action=google" class="btn-google">
                    <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                    Sign in with Google
                </a>
            </div>*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    
    <div class="login-wrapper">
        <div class="login-box">
            <h2 class="login-title">Login</h2>
            <form action="login.php" method="post">
            <?php
                if (isset($_SESSION['msg5'])) {
                    echo "<h6><div class='message-box'>".$_SESSION['msg5']."</div></h6>";
                    unset($_SESSION['msg5']); // Corrected: Now unsetting the correct variable
                }
                ?>
            <?php
                if (isset($_SESSION['msg4'])) {
                    echo "<h6><div class='message-box'>".$_SESSION['msg4']."</div></h6>";
                    unset($_SESSION['msg4']); // Corrected: Now unsetting the correct variable
                }
                ?>
                 <?php
                if (isset($_SESSION['msg2'])) {
                    echo "<h6><div class='message-box'>".$_SESSION['msg2']."</div></h6>";
                    unset($_SESSION['msg2']); // Corrected: Now unsetting the correct variable
                }
                ?>
                <div class="input-group">
                    <label for="user">Username:</label>
                    <input type="text" id="user" name="username" required>
                </div>
               
                <?php
                if (isset($_SESSION['msg1'])) {
                    echo "<h6><div class='message-box'>".$_SESSION['msg1']."</div></h6>";
                    unset($_SESSION['msg1']); // Corrected: Now unsetting the correct variable
                }
                ?>
                <div class="input-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="password" required>
                </div>
                <h6 id="chpas"><a href="change_passwordo.php">forgotpassword</a></h6>
                <button type="submit" class="btn-login">Login</button>
            </form>
           
            <p class="signup-text">Don't have an account? <a href="signupo.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

<style>
    #chpas{
        text-align: left;
        padding: 7px ;
    }
    .message-box {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        text-align: left;
        color: #721c24; 
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        font-weight: bold;
    }
    
    /* Reset and Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Page Background */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #4CAF50, #5135cc);
}

/* Wrapper for Centering */
.login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

/* Login Box Styling */
.login-box {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 350px;
    animation: fadeIn 0.8s ease-in-out;
}

/* Title Styling */
.login-title {
    color: #333;
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Input Group */
.input-group {
    text-align: left;
    margin-bottom: 15px;
}

.input-group label {
    font-weight: bold;
    color: #555;
}

.input-group input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: 0.3s ease-in-out;
}

/* Input Focus Effect */
.input-group input:focus {
    border-color: #5135cc;
    outline: none;
    box-shadow: 0 0 5px rgba(81, 53, 204, 0.5);
}

/* Login Button */
.btn-login {
    width: 100%;
    padding: 12px;
    background: #5135cc;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.btn-login:hover {
    background: #4128b6;
}

/* Google Login Styles */
.btn-google {
    width: 100%;
    padding: 12px;
    background: #4285F4;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 15px 0;
}

.btn-google:hover {
    background: #357ABD;
}

.google-icon {
    width: 20px;
    height: 20px;
}

/* Sign-up Text */
.signup-text {
    margin-top: 15px;
    font-size: 0.9rem;
    color: #333;
}

.signup-text a {
    color: #5135cc;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.signup-text a:hover {
    text-decoration: underline;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 400px) {
    .login-box {
        width: 90%;
        padding: 20px;
    }

    .btn-login {
        font-size: 0.9rem;
    }
}

</style>
