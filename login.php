<?php
session_start(); // Start session for login tracking
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css"> <!-- Linking CSS -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="login_process.php" method="POST">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?php echo md5(uniqid(mt_rand(), true)); ?>">

            <button type="submit" class="login-btn">Login</button>
        </form>

        <!-- Register Link -->
        <div class="register-section">
            <a href="register.php" class="register-link">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>