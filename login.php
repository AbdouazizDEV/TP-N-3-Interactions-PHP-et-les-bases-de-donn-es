<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/styleLogin.css">
</head>
<body>
    <div class="container">
        <!-- Left Side: Login Form -->
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="controllers/traitement_login.php">
                <div class="input-field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="options">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>

        <!-- Right Side: Illustration -->
        <div class="welcome-box">
            <h1>Check Your Project Progress</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <img src="image/images.jpeg" alt="Illustration">
        </div>
    </div>
</body>
</html>


