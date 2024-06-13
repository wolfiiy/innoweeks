<?php
  require_once '../scripts/session-check.php';
  if ($_SESSION['username'] != "admin") {
    header("Location: ../html/index.php");
    alert("Use the admin account!"); // TODO
    exit();
  }
  
  require_once '../scripts/admin-tools.php';
  include "../scripts/read.php";
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
          <a href="tasks.php">Tâches</a>
        </li>
        <li>
          <a href="scores.php">Classement</a>
        </li>        

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
      <h1>Administration dashboard</h1>
      <h2>Database information</h2>
      <h3>Registered accounts</h3>
      <p>A list of all accounts currently registered in the database can be found below.</p>
      <?php displayAllAccounts($conn);?>

      <h3>Tasks</h3>
      <p>A list of all tasks currently available to users is shown below.</p>
      <?php displayAllTasks($conn);?>

      <h2>Database management</h2>
      Database-related actions.
  
      <h3>Account creation</h3>
      <p>Fill in the following form to create a new account.</p>
      <div class="form-container">
        <form class="form-account-creation" action="../scripts/admin-tools.php?action=createAccount" method="POST">
          <input type="text" name="email" placeholder="Adresse email" required>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <input type="password" name="password-confirm" placeholder="Mot de passe (confirmer)" required>
          <input type="number" name="age" placeholder="Age" required>
          <button type="submit">Create account</button>
        </form>
      </div>

      <h3>Task / habit creation</h3>
      <p>Fill in the following form to create a new task.</p>
      <div class="form-container">
        <form action="../scripts/admin-tools.php?action=createTask" method="POST">
          <input type="text" name="task-name" placeholder="tasName" required>
          <input type="text" name="task-description" placeholder="tasDescription" required>
          <input type="number" name="task-score" placeholder="tasScore" required>
          <button type="submit">Add task</button>
        </form>
      </div>
      
      <h3>Database creation</h3>
      <p>
        To create the database itself, click
        <a href="../scripts/setup.php?action=createDatabase">here</a>.
      </p>
    
      <h3>Table creation</h3>
      <p>
        To create required tables, click
        <a href="../scripts/setup.php?action=createTables">here</a>.
      </p>

      <h3>Log out</h3>
      <p>
        To log out from user "<?php echo htmlspecialchars($_SESSION['username']); ?>", click
        <a href="../scripts/signout.php">here</a>.
      </p>
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