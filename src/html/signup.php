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

        <h1>Créer un compte</h1>
        <p>Veuillez renseigner vos informations.</p>

        <form class="form-account-creation" action="../scripts/public-tools.php?action=createAccount" method="POST">
          <input type="text" name="email" placeholder="Adresse email" required>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <input type="password" name="password-confirm" placeholder="Mot de passe (confirmer)" required>
          <input type="number" name="age" placeholder="Age" required>
          <button type="submit" class="button">Confirmer</button>
          <p class="form-bottom-text">
            <a href="signin.php">Se connecter</a>
            |
            <a href="index.php">Accueil</a>
          </p>
        </form>
      </div>
  </main>
</body>
</html>