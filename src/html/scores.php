<?php
  require '../scripts/session-check.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <link rel="stylesheet" href="../styles/scores.css">
  <title>Classement</title>
</head>
<body>
  <header>
    <img src="../../assets/logo.png" alt="Logotype" id="logo">

    <nav>
      <ul>
        <li>
          <a href="index.php">Accueil</a>
        </li>
        <li>
          <a href="admin.php">Admin</a>
        </li>
        <li>
          <a href="tasks.php">Tâches</a>
        </li>
        <li>
          <a href="scores.php">Classement</a>
        </li>        
        <?php
          if(!isset($_SESSION['username']))
          { ?>
            <li>
              <a href="signin.html" id="login-button">Connexion</a>
            </li>
          <?php }
        ?>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <li>
              <a href="../scripts/signout.php" id="login-button">Déconnexion</a>
            </li>
          <?php }
        ?>
      </ul>
    </nav>
  </header>

  <main>
    <div class="content">
      <h1>Classement</h1>
      
      <div class="leaderboard">
        <h2>Placeholder</h2>
        <div class="leaderboard-item">
          <div class="rank rank-1">1</div>
          <div class="leaderboard-username">Moi</div>
          <div class="points">50 Pts</div>
        </div>
        <div class="leaderboard-item">
          <div class="rank rank-2">2</div>
          <div class="leaderboard-username">Annabelle</div>
          <div class="points">40 Pts</div>
        </div>
        <div class="leaderboard-item">
          <div class="rank rank-3">3</div>
          <div class="leaderboard-username">Nolan</div>
          <div class="points">35 Pts</div>
        </div>
        <div class="leaderboard-item">
          <div class="rank rank-4">4</div>
          <div class="leaderboard-username">Sophie</div>
          <div class="points">15 Pts</div>
        </div>
        <div class="leaderboard-item">
          <div class="rank rank-5">5</div>
          <div class="leaderboard-username">Laurent</div>
          <div class="points">5 Pts</div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p>
      This website is 
      <a href="https://github.com/wolfiiy/innoweeks">open source</a>.
    </p>
  </footer>
</body>
</html>