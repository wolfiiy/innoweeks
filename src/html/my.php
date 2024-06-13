<?php
require_once '../scripts/session-check.php';
require_once '../scripts/user-tools.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <script src="../js/main.js"></script>
  <title>Mon compte</title>
</head>
<body>
<header>
    <a href="index.php">
      <img src="../../assets/logo.png" alt="Logotype" id="logo">
    </a>

    <nav>
      <ul>
        <li>
          <a href="index.php">Accueil</a>
        </li>
        <li>
          <a href="tasks.php">Tâches</a>
        </li>
        <li>
          <a href="scores.php">Classement</a>
        </li>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <li>
              <a href="my.php">Mon compte</a>
            </li>
          <?php }
        ?>
        <?php
          if(!isset($_SESSION['username']))
          { ?>
            <li>
              <a href="signin.php" class="button">Connexion</a>
            </li>
          <?php }
        ?>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <li>
              <a href="../scripts/signout.php" class="button">Déconnexion</a>
            </li>
          <?php }
        ?>
      </ul>
    </nav>
  </header>

  <main>
    <div class="content">
      <h1>Bienvenue, 
        <?php
          echo $_SESSION['username'];
        ?>
      </h1>
      Ici, vous pouvez modifier les informations associées à votre compte GreenHabits.

      <h2>Nom d'utilisateur</h2>
      <p>
        <?php
          echo $_SESSION['username'];
        ?>
      </p>
      <form action="../scripts/user-tools.php?action=editUsername" 
            method="POST">
        <input type="text" name="username" required>
        <button type="submit">Valider</button>
      </form>

      <h2>Adresse e-mail</h2>
      <p>
        <?php
          echo getAccountEmail($conn);
        ?>
      </p>
      <form action="../scripts/user-tools.php?action=editEmail" 
            method="POST">
        <input type="text" name="email" required>
        <button type="submit">Valider</button>
      </form>

      <h2>Mot de passe</h2>
      <form action="../scripts/user-tools.php?action=editPassword" 
            method="POST">
        <input type="password" name="password" required>
        <input type="password" name="password" required>
        <button type="submit">Valider</button>
      </form>

      <h2>Age</h2>
      <p>
        <?php
          echo getAccountAge($conn) . " ans";
        ?>
      </p>
      <form action="../scripts/user-tools.php?action=editAge" 
            method="POST">
        <input type="number" name="age" required>
        <button type="submit">Valider</button>
      </form>
    </div>
  </main>

  <footer>
    <p>
      This website is 
      <a href="https://github.com/wolfiiy/innoweeks">open source</a>.
    </p>

    <p>
      <a id="dark-mode-toggle">Switch themes</a>
    </p>

    <?php
      if($_SESSION['username'] === "admin")
        { ?>
          <a href="admin.php">Administration dashboard</a>
        <?php }
      ?>
  </footer>
</body>
</html>