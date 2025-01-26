<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/styleRegistration.css">
</head>
<body>
    <div class="container">
        <div class="registration-box">
            <h2>Register</h2>
            <form method="POST" action="controllers/traitement_register.php" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="nom">nom</label>
                    <input type="text" id="username" name="nom" required>
                </div>
                <div class="input-field">
                    <label for="prenom">prenom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-field">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <div class="input-field">
                    <label for="tel">téléphone</label>
                    <input type="tel" id="phone" name="tel" required>
                </div>
                <div class="input-field">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required>
                </div> 
                <!-- un select pour choisir le role  en important les roles de la base de données dans la table role-->
                <div class="input-field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="user">client</option>
                        <option value="agent">commercial</option>
                    </select>
                </div>
                <button type="submit" class="register-btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>