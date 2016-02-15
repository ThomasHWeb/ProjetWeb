#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: Utilisateur
#------------------------------------------------------------

CREATE TABLE Utilisateur(
        Id_Utilisateur INT NOT NULL AUTO_INCREMENT,
        Nom            Varchar (25) ,
        Prenom         Varchar (25) ,
        Password       Varchar (100),
        DateNaissance  Date ,
        Adresse        Varchar (25) ,
        Mail           Varchar (25) NOT NULL,
        Telephone      Varchar (25) ,
        Id_Sexe        INT NOT NULL,
        PRIMARY KEY (Id_Utilisateur ),
        UNIQUE (Mail )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Adhérent
#------------------------------------------------------------

CREATE TABLE Adherent(
        Id_Adherent     INT NOT NULL AUTO_INCREMENT,
        NumeroLicence   INT NOT NULL,
        DateInscription Date NOT NULL,
        Id_Utilisateur  INT NOT NULL ,
        PRIMARY KEY (Id_Adherent ) ,
        UNIQUE (NumeroLicence )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Competiteur
#------------------------------------------------------------

CREATE TABLE Competiteur(
        Id_Competiteur INT NOT NULL AUTO_INCREMENT,
        Id_Adherent    INT NOT NULL ,
        Id_Specialite  INT ,
        Id_Objectif    INT ,
        Id_Categorie   INT ,
        PRIMARY KEY (Id_Competiteur )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Sexe
#------------------------------------------------------------

CREATE TABLE Sexe(
        Id_Sexe INT NOT NULL ,
        Type    Varchar (1) ,
        PRIMARY KEY (Id_Sexe ) ,
        UNIQUE (Type )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Specialite
#------------------------------------------------------------

CREATE TABLE Specialite(
        Id_Specialite INT NOT NULL ,
        Nom           Varchar (25) ,
        PRIMARY KEY (Id_Specialite ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Objectif
#------------------------------------------------------------

CREATE TABLE Objectif(
        Id_Objectif INT NOT NULL AUTO_INCREMENT,
        Type        Varchar (25) ,
        PRIMARY KEY (Id_Objectif )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Competition
#------------------------------------------------------------

CREATE TABLE Competition(
        Id_Competition      INT NOT NULL AUTO_INCREMENT,
        Adresse             Varchar (25) ,
        DateCompetition     Date ,
        Id_Sexe             INT ,
        Id_Type_Competition INT NOT NULL,
        Id_Categorie        INT NOT NULL,
        Id_Specialite       INT NOT NULL,
        Id_Club_Organisateur INT NOT NULL,
        PRIMARY KEY (Id_Competition )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Club_Organisateur
#------------------------------------------------------------

CREATE TABLE Club_Organisateur(
        ID_Club_Organisateur INT NOT NULL AUTO_INCREMENT,
        Nom                  Varchar (25) ,
        President            Varchar (25) ,
        PRIMARY KEY (ID_Club_Organisateur ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: Categorie
#------------------------------------------------------------

CREATE TABLE Categorie(
        Id_Categorie INT NOT NULL ,
        Nom          Varchar (25) ,
        PRIMARY KEY (Id_Categorie ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Equipe
#------------------------------------------------------------

CREATE TABLE Equipe(
        Id_Equipe INT NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (Id_Equipe )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Role
#------------------------------------------------------------

CREATE TABLE Role(
        Id_Role INT NOT NULL ,
        Titre   Varchar (25) ,
        PRIMARY KEY (Id_Role ) ,
        UNIQUE (Titre )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Message
#------------------------------------------------------------

CREATE TABLE Message(
        Id_Message INT NOT NULL AUTO_INCREMENT,
        Sujet      Varchar (100) ,
        Corps      Varchar (500) ,
        PRIMARY KEY (Id_Message )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Type_Competiion
#------------------------------------------------------------

CREATE TABLE Type_Competition(
        Id_Type_Competition INT NOT NULL AUTO_INCREMENT,
        Nom                 Varchar (25) ,
        Selectif            Bool ,
        PRIMARY KEY (Id_Type_Competition ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Palmares
#------------------------------------------------------------

CREATE TABLE Palmares(
        Id_Palmares    INT NOT NULL AUTO_INCREMENT,
        Classement     INT ,
        Id_Competition INT ,
        PRIMARY KEY (Id_Palmares )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Voyage
#------------------------------------------------------------

CREATE TABLE Voyage(
        Id_Voyage         INT NOT NULL AUTO_INCREMENT,
        Transport_Propose Varchar (25) ,
        Hebergement       Varchar (25) ,
        Id_Competition    INT ,
        PRIMARY KEY (Id_Voyage )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Type_Voyage
#------------------------------------------------------------

CREATE TABLE Type_Voyage(
        Id_Voyage INT NOT NULL ,
        Nom       Varchar (25) ,
        PRIMARY KEY (Id_Voyage )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Droit_Acces
#------------------------------------------------------------

CREATE TABLE Droit_Acces(
        Id_Droit_Acces INT NOT NULL ,
        Nom            Varchar (25) ,
        PRIMARY KEY (Id_Droit_Acces ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Participe_Competition_Equipe
#------------------------------------------------------------

CREATE TABLE Participe_Competition_Equipe(
        Id_Equipe      INT NOT NULL ,
        Id_Competition INT NOT NULL ,
        PRIMARY KEY (Id_Equipe ,Id_Competition )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Participant_Equipe
#------------------------------------------------------------

CREATE TABLE Participant_Equipe(
        Id_Competiteur INT NOT NULL ,
        Id_Equipe      INT NOT NULL ,
        PRIMARY KEY (Id_Competiteur ,Id_Equipe )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Envoie
#------------------------------------------------------------

CREATE TABLE Envoie(
        Id_Utilisateur INT NOT NULL ,
        Id_Message     INT NOT NULL ,
        PRIMARY KEY (Id_Utilisateur ,Id_Message )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Recois
#------------------------------------------------------------

CREATE TABLE Recois(
        Id_Message     INT NOT NULL ,
        Id_Utilisateur INT NOT NULL ,
        PRIMARY KEY (Id_Message ,Id_Utilisateur )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Tache
#------------------------------------------------------------

CREATE TABLE Tache(
        Id_Tache INT NOT NULL ,
        Nom          Varchar (100) ,
        PRIMARY KEY (Id_Tache ) ,
        UNIQUE (Nom )
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: Charger
#------------------------------------------------------------

CREATE TABLE Charger(
        Id_Voyage      INT NOT NULL ,
        Id_Utilisateur INT NOT NULL ,
        Id_Role        INT ,
        Id_Tache       INT ,
        PRIMARY KEY (Id_Voyage ,Id_Utilisateur )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ParticipeVoyage
#------------------------------------------------------------

CREATE TABLE Participe_Voyage(
        Autoriser             Bool ,
        Id_Voyage             INT NOT NULL ,
        Id_Competiteur        INT NOT NULL ,
        Id_Voyage_Type_Voyage INT NOT NULL ,
        Id_Utilisateur        INT ,
        PRIMARY KEY (Id_Voyage ,Id_Competiteur ,Id_Voyage_Type_Voyage)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Palmares_Equipe
#------------------------------------------------------------

CREATE TABLE Palmares_Equipe(
        Id_Equipe   INT NOT NULL ,
        Id_Palmares INT NOT NULL ,
        PRIMARY KEY (Id_Equipe ,Id_Palmares )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Palmares_Competiteur
#------------------------------------------------------------

CREATE TABLE Palmares_Competiteur(
        Id_Competiteur INT NOT NULL ,
        Id_Palmares    INT NOT NULL ,
        PRIMARY KEY (Id_Competiteur ,Id_Palmares )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ParticipeCompetitionSolo
#------------------------------------------------------------

CREATE TABLE Participe_Competition_Solo(
        Id_Competition INT NOT NULL ,
        Id_Competiteur INT NOT NULL ,
        PRIMARY KEY (Id_Competition ,Id_Competiteur )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Droits
#------------------------------------------------------------

CREATE TABLE Droits(
        Id_Droit_Acces INT NOT NULL ,
        Id_Adherent    INT NOT NULL ,
        PRIMARY KEY (Id_Droit_Acces ,Id_Adherent )
)ENGINE=InnoDB;

ALTER TABLE Utilisateur ADD CONSTRAINT FK_Utilisateur_Id_Sexe
FOREIGN KEY (Id_Sexe) REFERENCES Sexe(Id_Sexe);

ALTER TABLE Adherent ADD CONSTRAINT FK_Adherent_Id_Utilisateur
FOREIGN KEY (Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur);

ALTER TABLE Competiteur ADD CONSTRAINT FK_Competiteur_Id_Adherent
FOREIGN KEY (Id_Adherent) REFERENCES Adherent(Id_Adherent);

ALTER TABLE Competiteur ADD CONSTRAINT FK_Competiteur_Id_Specialite
FOREIGN KEY (Id_Specialite) REFERENCES Specialite(Id_Specialite);

ALTER TABLE Competiteur ADD CONSTRAINT FK_Competiteur_Id_Objectif
FOREIGN KEY (Id_Objectif) REFERENCES Objectif(Id_Objectif);

ALTER TABLE Competiteur ADD CONSTRAINT FK_Competiteur_Id_Categorie
FOREIGN KEY (Id_Categorie) REFERENCES Categorie(Id_Categorie);

ALTER TABLE Competition ADD CONSTRAINT FK_Competition_Id_Sexe
FOREIGN KEY (Id_Sexe) REFERENCES Sexe(Id_Sexe);

ALTER TABLE Competition ADD CONSTRAINT FK_Competition_Id_Type_Competition
FOREIGN KEY (Id_Type_Competition) REFERENCES Type_Competition(Id_Type_Competition);

ALTER TABLE Competition ADD CONSTRAINT FK_Competition_Id_Categorie
FOREIGN KEY (Id_Categorie) REFERENCES Categorie(Id_Categorie);

ALTER TABLE Competition ADD CONSTRAINT FK_Competition_Id_Specialite
FOREIGN KEY (Id_Specialite) REFERENCES Specialite(Id_Specialite);

ALTER TABLE Competition ADD CONSTRAINT FK_Competition_Id_Club_Organisateur
FOREIGN KEY (Id_Club_Organisateur) REFERENCES Club_Organisateur(Id_Club_Organisateur);

ALTER TABLE Palmares ADD CONSTRAINT FK_Palmares_Id_Competition
FOREIGN KEY (Id_Competition) REFERENCES Competition(Id_Competition);

ALTER TABLE Voyage ADD CONSTRAINT FK_Voyage_Id_Competition
FOREIGN KEY (Id_Competition) REFERENCES Competition(Id_Competition);

ALTER TABLE Participe_Competition_Equipe ADD CONSTRAINT FK_Participe_Competition_Equipe_Id_Equipe
FOREIGN KEY (Id_Equipe) REFERENCES Equipe(Id_Equipe);

ALTER TABLE Participe_Competition_Equipe ADD CONSTRAINT FK_Participe_Competition_Equipe_Id_Competition
FOREIGN KEY (Id_Competition) REFERENCES Competition(Id_Competition);

ALTER TABLE Participant_Equipe ADD CONSTRAINT FK_Participant_Equipe_Id_Competiteur
FOREIGN KEY (Id_Competiteur) REFERENCES Competiteur(Id_Competiteur);

ALTER TABLE Participant_Equipe ADD CONSTRAINT FK_Participant_Equipe_Id_Equipe
FOREIGN KEY (Id_Equipe) REFERENCES Equipe(Id_Equipe);

ALTER TABLE Envoie ADD CONSTRAINT FK_Envoie_Id_Utilisateur
FOREIGN KEY (Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur);

ALTER TABLE Envoie ADD CONSTRAINT FK_Envoie_Id_Message
FOREIGN KEY (Id_Message) REFERENCES Message(Id_Message);

ALTER TABLE Recois ADD CONSTRAINT FK_Recois_Id_Message
FOREIGN KEY (Id_Message) REFERENCES Message(Id_Message);

ALTER TABLE Recois ADD CONSTRAINT FK_Recois_Id_Utilisateur
FOREIGN KEY (Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur);

ALTER TABLE Charger ADD CONSTRAINT FK_Charger_Id_Voyage
FOREIGN KEY (Id_Voyage) REFERENCES Voyage(Id_Voyage);

ALTER TABLE Charger ADD CONSTRAINT FK_Charger_Id_Utilisateur
FOREIGN KEY (Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur);

ALTER TABLE Charger ADD CONSTRAINT FK_Charger_Id_Role
FOREIGN KEY (Id_Role) REFERENCES Role(Id_Role);

ALTER TABLE Charger ADD CONSTRAINT FK_Charger_Id_Tache
FOREIGN KEY (Id_Tache) REFERENCES Tache(Id_Tache);

ALTER TABLE Participe_Voyage ADD CONSTRAINT FK_Participe_Voyage_Id_Voyage
FOREIGN KEY (Id_Voyage) REFERENCES Voyage(Id_Voyage);

ALTER TABLE Participe_Voyage ADD CONSTRAINT FK_Participe_Voyage_Id_Competiteur
FOREIGN KEY (Id_Competiteur) REFERENCES Competiteur(Id_Competiteur);

ALTER TABLE Participe_Voyage ADD CONSTRAINT FK_Participe_Voyage_Id_Voyage_Type_Voyage
FOREIGN KEY (Id_Voyage_Type_Voyage) REFERENCES Type_Voyage(Id_Voyage);

ALTER TABLE Participe_Voyage ADD CONSTRAINT FK_Participe_Voyage_Id_Utilisateur
FOREIGN KEY (Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur);

ALTER TABLE Palmares_Equipe ADD CONSTRAINT FK_Palmares_Equipe_Id_Equipe
FOREIGN KEY (Id_Equipe) REFERENCES Equipe(Id_Equipe);

ALTER TABLE Palmares_Equipe ADD CONSTRAINT FK_Palmares_Equipe_Id_Palmares
FOREIGN KEY (Id_Palmares) REFERENCES Palmares(Id_Palmares);

ALTER TABLE Palmares_Competiteur ADD CONSTRAINT FK_Palmares_Competiteur_Id_Competiteur
FOREIGN KEY (Id_Competiteur) REFERENCES Competiteur(Id_Competiteur);

ALTER TABLE Palmares_Competiteur ADD CONSTRAINT FK_Palmares_Competiteur_Id_Palmares
FOREIGN KEY (Id_Palmares) REFERENCES Palmares(Id_Palmares);

ALTER TABLE Participe_Competition_Solo ADD CONSTRAINT FK_Participe_Competition_Solo_Id_Competition
FOREIGN KEY (Id_Competition) REFERENCES Competition(Id_Competition);

ALTER TABLE Participe_Competition_Solo ADD CONSTRAINT FK_Participe_Competition_Solo_Id_Competiteur
FOREIGN KEY (Id_Competiteur) REFERENCES Competiteur(Id_Competiteur);

ALTER TABLE Droits ADD CONSTRAINT FK_Droits_Id_Droit_Acces
FOREIGN KEY (Id_Droit_Acces) REFERENCES Droit_Acces(Id_Droit_Acces);

ALTER TABLE Droits ADD CONSTRAINT FK_Droits_Id_Adherent
FOREIGN KEY (Id_Adherent) REFERENCES Adherent(Id_Adherent);

#------------------------------------------------------------
# Insertion: Sexe
#------------------------------------------------------------
INSERT INTO Sexe VALUES(0,'M'),(1,'F'),(2,'O');

#------------------------------------------------------------
# Insertion: Specialite
#------------------------------------------------------------
INSERT INTO Specialite VALUES(0,"Canoe"),(1,"Kayak");

#------------------------------------------------------------
# Insertion: Categorie
#------------------------------------------------------------
INSERT INTO Categorie VALUES(0,"Minime"),(1,"Cadet"),(2,"Junior"),(3,"Senior"),(4,"Veteran");

#------------------------------------------------------------
# Insertion: Role
#------------------------------------------------------------
INSERT INTO Role VALUES(0,"Responsable officiel"),(1,"Juge"),(2,"Entraineur"),(3,"Benevole");

#------------------------------------------------------------
# Insertion: Tache
#------------------------------------------------------------
INSERT INTO Tache VALUES(0,"Reunion de confirmation des inscriptions"),(1,"Depot des reclamation"),(2,"Transport"),(3,"Gestion alimentation"),(4,"Gestion hebergement");

#------------------------------------------------------------
# Insertion: Droit_Acces
#------------------------------------------------------------
INSERT INTO Droit_Acces VALUES(0,"Administrateurs"),(1,"Entraineur"),(2,"Secretaire");

#------------------------------------------------------------
# Insertion: Type_Voyage
#------------------------------------------------------------
INSERT INTO Type_Voyage VALUES(0,"Club"),(1,"Autre");
