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
      <p>
        Ici, vous pouvez modifier les informations associées à votre compte GreenHabits. Les champs laissés vides 
        restent tels quels.
      </p>

      <form action="../scripts/user-tools.php?action=edit" 
            method="post"
            class = wide-form>
        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" class="medium"
               placeholder="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>"
        >
        
        <label for="email">Adresse mail</label>
        <input type="text" name="email" id="email" class="large"
                placeholder="<?php echo htmlspecialchars(getAccountEmail($conn) ?? ''); ?>"
        >
        
        <label for="age">Age</label>
        <input type="number" name="age" id="age" class="small"
                placeholder="<?php echo htmlspecialchars(getAccountAge($conn) ?? ''); ?>"
        >
        
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" class="medium">
        <input type="password" name="password-confirm" id="password-confirm" class="medium">
        
        <button type="submit" class="button">Valider</button>
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