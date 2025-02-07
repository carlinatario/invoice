<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="password-wrapper">
        <div class="password-box">
            <h2 class="password-title">Change Password</h2>
            
            <div class="message-box" id="chpas">
                Please enter your current and new password carefully. 
               
            </div>
             <?php
                if (isset($_SESSION['msg6'])) {
                    echo "<h6><div class='message-box'>".$_SESSION['msg6']."</div></h6>";
                    unset($_SESSION['msg6']); // Corrected: Now unsetting the correct variable
                }
                ?>
           
            <form action="change_password.php" method="post">
                <div class="input-group">
                    <label>Enter email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Current Password:</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="input-group">
                    <label>New Password:</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="input-group">
                    <label>Confirm New Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-submit">Change Password</button>
            </form>
            
            <p class="back-home"><a href="logino.php">Back to Home</a></p>
        </div>
    </div>
</body>
</html>
<style>
/* Global Reset */
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
.password-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

/* Password Box Styling */
.password-box {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 350px;
    animation: fadeIn 0.8s ease-in-out;
}

/* Title Styling */
.password-title {
    color: #333;
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 15px;
}

/* Message Box */
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

/* Submit Button */
.btn-submit {
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

/* Button Hover Effect */
.btn-submit:hover {
    background: #4128b6;
    transform: scale(1.05);
}

/* Back to Home Link */
.back-home {
    margin-top: 15px;
    font-size: 0.9rem;
    color: #333;
}

.back-home a {
    color: #5135cc;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.back-home a:hover {
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
    .password-box {
        width: 90%;
        padding: 20px;
    }

    .btn-submit {
        font-size: 0.9rem;
    }
}
</style>