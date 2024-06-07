<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <title>Document</title>
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
              <a href="signin.html">Connexion</a>
            </li>
          <?php }
        ?>
        <?php
          if(isset($_SESSION['username']))
          { ?>
            <li>
              <a href="../scripts/signout.php">Déconnexion</a>
            </li>
          <?php }
        ?>
      </ul>
    </nav>
  </header>

  <main>
    <div class="content">
      <h1>Bienvenue</h1>
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