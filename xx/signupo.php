<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-wrapper">
        <div class="signup-box">
            <h2 class="signup-title">Create Account</h2>
            <?php
            if (isset($_SESSION['msg3'])) {
                echo "<h6><div class='message-box'>".$_SESSION['msg3']."</div></h6>";
                unset($_SESSION['msg3']); // Corrected: Now unsetting the correct variable
            }
            ?>
            <form action="signup.php" method="post">
                <div class="input-group">
                    <label for="user">Username:</label>
                    <input type="text" id="user" name="username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="password" required>
                </div>
                <div class="input-group">
                    <label for="admin-pass">Admin Password:</label>
                    <input type="password" id="admin-pass" name="admin_pass" required>
                </div>
                <button type="submit" class="btn-signup">Sign Up</button>
            </form>
            <p class="login-text">Already have an account? <a href="logino.php">Login</a></p>
        </div>
    </div>
</body>
</html>
<style>/* signup styling*/
    /* Reset and Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
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
      
      /* Page Background */
      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: linear-gradient(135deg, #4CAF50, #5135cc);
      }
      
      /* Wrapper for Centering */
      .signup-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
      }
      
      /* Signup Box Styling */
      .signup-box {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        text-align: center;
        width: 350px;
        animation: fadeIn 0.8s ease-in-out;
      }
      
      /* Title Styling */
      .signup-title {
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
      
      /* Signup Button */
      .btn-signup {
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
      
      .btn-signup:hover {
        background: #4128b6;
      }
      
      /* Login Text */
      .login-text {
        margin-top: 15px;
        font-size: 0.9rem;
        color: #333;
      }
      
      .login-text a {
        color: #5135cc;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s;
      }
      
      .login-text a:hover {
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
        .signup-box {
            width: 90%;
            padding: 20px;
        }
      
        .btn-signup {
            font-size: 0.9rem;
        }
      }</style>
