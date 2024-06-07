<?php
  include '../scripts/session-check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/main.css">
  <title>Admin dashboard</title>
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
        <li>
          <a href="signin.html">Compte</a>
        </li>

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
      <h1>Administration dashboard</h1>
      <h2>Database</h2>
      Database-related actions.
  
      <h3>Account creation</h3>
      <p>Fill in the following form to create a new account.</p>
      <div class="form-container">
        <form class="form-account-creation" action="../php/create-account.php" method="POST">
          <input type="text" name="email" placeholder="Adresse email" required>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <input type="password" name="password-confirm" placeholder="Mot de passe (confirmer)" required>
          <input type="number" name="age" placeholder="Age" required>
          <button type="submit">Create account</button>
        </form>
      </div>
      
      <h3>Database creation</h3>
      <p>
        To create the database itself, click
        <a href="../php/create-db.php">here</a>.
      </p>
    
      <h3>Table creation</h3>
      <p>
        To create required tables, click
        <a href="../php/create-tables.php">here</a>.
      </p>

      <h3>Log out</h3>
      <?php include '../scripts/session-check.php'; ?>
      <p>
        To log out from <?php echo htmlspecialchars($_SESSION['username']); ?>, click
        <a href="../php/signout.php">here</a>.
      </p>
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