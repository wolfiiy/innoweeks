CREATE TABLE IF NOT EXISTS t_Account(
   idAccount INT AUTO_INCREMENT,
   accEmail VARCHAR(320) NOT NULL,
   accUsername VARCHAR(64) NOT NULL,
   accPassword VARCHAR(64) NOT NULL,
   accAge TINYINT,
   accScore INT,
   PRIMARY KEY(idAccount),
   UNIQUE(accEmail),
   UNIQUE(accUsername)
);

CREATE TABLE IF NOT EXISTS t_Task(
   idTask INT AUTO_INCREMENT,
   tasName VARCHAR(256) NOT NULL,
   tasDescription VARCHAR(1024) NOT NULL,
   tasScore SMALLINT NOT NULL,
   tasState BOOLEAN NOT NULL,
   PRIMARY KEY(idTask)
);

CREATE TABLE IF NOT EXISTS Complete(
   idAccount INT,
   idTask INT,
   comState BOOLEAN NOT NULL DEFAULT 0,
   PRIMARY KEY(idAccount, idTask),
   FOREIGN KEY(idAccount) REFERENCES t_Account(idAccount),
   FOREIGN KEY(idTask) REFERENCES t_Task(idTask),
   INDEX (idAccount),
   INDEX (idTask)
);

CREATE TABLE IF NOT EXISTS Befriend(
   idAccount INT,
   idAccount_1 INT,
   PRIMARY KEY(idAccount, idAccount_1),
   FOREIGN KEY(idAccount) REFERENCES t_Account(idAccount),
   FOREIGN KEY(idAccount_1) REFERENCES t_Account(idAccount),
   CHECK (idAccount <> idAccount_1)
);
