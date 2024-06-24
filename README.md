# GreenHabits

## Setup
First, create a new MariaDB user and a database to use with *GreenHabits*:
```SQL
CREATE USER 'ghadmin'@'localhost' IDENTIFIED BY 'password';
CREATE DATABASE IF NOT EXISTS db_greenhabits;
```

Then, give the newly created user necessary permissions:
```SQL
GRANT SELECT, INSERT, UPDATE, CREATE, ALTER ON db_greenhabits.* TO 'ghadmin'@'localhost';
```

Once the database has been correctly set up, open up the `admin.php` file and
create the tables.

Finally, from that same page, create an account with the 'admin' username.