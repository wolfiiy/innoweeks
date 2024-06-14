<?php
  require_once '../scripts/session-check.php';
  require_once '../scripts/read.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <link rel="stylesheet" href="../styles/tasks.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="../js/main.js"></script>
  <title>Tâches</title>
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
      </ul>
    </nav>

    <div id="mobile-nav-container">
      <ul id="mobile-nav">
        <li>
          <a href="index.php">
            <span class="material-symbols-outlined">home</span>
          </a>
        </li>
        <li>
          <a href="tasks.php">
            <span class="material-symbols-outlined">task</span>
          </a>
        </li>
        <li>
          <a href="scores.php">
            <span class="material-symbols-outlined">leaderboard</span>
          </a>
        </li>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <li>
              <a href="my.php">
                <span class="material-symbols-outlined">account_box</span>
              </a>
            </li>
          <?php }
        ?>
      </ul>
    </div>

    <?php
          if(!isset($_SESSION['username']))
          { ?>
            <a href="signin.php" class="button">Connexion</a>
          <?php }
        ?>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <a href="../scripts/signout.php" class="button">Déconnexion</a>
          <?php }
        ?>
  </header>

  <main>
    <div class="content">
      <h1>Les tâches du jour</h1>
      <div class="task-container">
        <?php fillTaskContainer($conn);?>
      </div>
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