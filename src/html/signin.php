<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <link rel="stylesheet" href="../styles/form.css">
  <title>Admin dashboard</title>
</head>
<body>
  <main>
    <div class="content">
      <div class="form-container">
        <img class="form-logo" src="../../assets/logo.png" alt="Logotype">

        <h1>Connexion</h1>
        <p>Veuillez renseigner vos identifiants.</p>
        
        <form class="form-account-signin" action="../scripts/signin.php" method="POST">
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <button type="submit" class="button">Se connecter</button>
          <p class="form-bottom-text">
            <a href="signup.php">Créer un compte</a>
            |
            <a href="index.php">Accueil</a>
          </p>
        </form>
      </div>
    </div>
  </main>
</body>
</html>