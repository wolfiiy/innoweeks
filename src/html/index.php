<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <link rel="stylesheet" href="../styles/index.css">
  <title>Accueil</title>
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
          <a href="TODO">Classement</a>
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
      <h1>Bienvenue sur GreenHabits</h1>

      <div id="home-mascots-container">
        <img src="../../assets/eco-lojie.png" 
             alt="Eco et Lojie, les mascots de GreenHabits"
             id="home-mascots">
  
        <p id="home-mascots-text">
          Salut! Nous c'est Eco et Lojie! Es-tu prêt à améliorer des habitudes écologiques avec nous?
        </p>
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