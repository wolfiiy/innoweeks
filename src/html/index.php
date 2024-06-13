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
      <h1>Bienvenue sur GreenHabits</h1>

      <div id="home-mascots-container">
        <img src="../../assets/eco-lojie.png" 
             alt="Eco et Lojie, les mascots de GreenHabits"
             id="home-mascots">
  
        <p id="home-mascots-text">
          Salut! Nous c'est Eco et Lojie! Es-tu prêt à améliorer tes habitudes écologiques avec nous?
        </p>
      </div>

      <h2>Présentation</h2>
      <p>
        GreenHabits est une plateforme dédiée à encourager des comportements écologiques au quotidien. Notre mission est 
        de fournir des outils et des ressources pour aider chacun à adopter des pratiques plus respectueuses de 
        l'environnement. 
      </p>

      <p>
        Sur GreenHabits, vous pouvez accomplir diverses tâches écologiques, suivre vos progrès et voir comment vous vous 
        classez par rapport aux autres utilisateurs. Que vous soyez un débutant en matière d'écologie ou un expert, 
        notre plateforme propose des défis adaptés à tous les niveaux.
      </p>

      <p>
        GreenHabits à été développé à l'ETML dans le contexte des Innoweeks, deux semaines de cours dédiées au 
        développement de projets innovants.
      </p>
    </div>
  </main>

  <footer>
    <p>
      This website is 
      <a href="https://github.com/wolfiiy/innoweeks">open source</a>.
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