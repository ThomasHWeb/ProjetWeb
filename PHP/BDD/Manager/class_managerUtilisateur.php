<?php
require_once "class_manager.php";
require_once "../class_Utilisateur.php";

class ManagerUtilisateur extends Manager{ // -- a modifier --

    //Constructeur
    public function __construct($Db)
    {
        parent::__construct($Db);
	}

    //Procédure qui ajoute un utilisateur dans la BDD
    public function add($objet)
    {
        //Recupere l'id du sexe de l'utilisateur
        $requeteId_Sexe = $this->getDb()->query('SELECT Id_Sexe FROM Sexe WHERE Type = '.$objet->getSexe());
        $donneId_Sexe = $requeteId_Sexe->fetch(PDO::FETCH_ASSOC);

        $requete = $this->getDb()->prepare('INSERT INTO Utilisateur (Nom,Prenom,Password,DateNaissance,Adresse,Mail,Telephone,Id_Sexe) VALUES(:nom,:prenom,:password,:dateNaissance,:adresse,:mail,:telephone,:id_Sexe)');

        $requete->execute(array('nom' => $objet->getNom(),
                                'prenom' => $objet->getPrenom(),
                                'password' => $objet->getPassword(),
                                'dateNaissance' => $objet->getDateNaissance(),
                                'adresse' => $objet->getAdresse(),
                                'mail' => $objet->getMail(),
                                'telephone' => $objet->getTelephone(),
                                'id_Sexe' => $donneId_Sexe['Id_Sexe'],
                               ));

        $this->addParente($objet);

        $this->addDroits($objet);

        //Recupere l'id de l'utilisateur genere par la base
        $requeteId_Utilisateur = $this->getDb()->query('SELECT Id_Utilisateur FROM Utilisateur WHERE Mail = '.$objet->getMail());
        $donneId_Utilisateur = $requeteId_Utilisateur->fetch(PDO::FETCH_ASSOC);

        $objet->setId_Utilisateur($donneId_Utilisateur['Id_Utilisateur']);

    }

    public function addParente($objet){

        $requeteEnfant = $this->getDb()->query('SELECT Id_Enfant FROM Parente WHERE Id_Parent = '.$objet->getId_Utilisateur());
        $donneEnfant = $requeteEnfant->fetchAll(PDO::FETCH_ASSOC);

        foreach($objet->getParente() as $enfant){

            if(!in_array($donneEnfant['Id_Enfant'],$enfant)){
                $requete = $this->getDb()->prepare('INSERT INTO Parente (Id_Parent,Id_Enfant) VALUES(:id_Parent,:id_Enfant)');

                $requete->execute(array('id_Parent' => $objet->getId_Utilisateur(),
                                        'id_Enfant' => $enfant,
                                        ));
            }
        }
    }

    //Ajoute les droits de l'adherent
    public function addDroits($objet){

        //on recupere les droits de l'adherent deja dans la base
        $requeteDroits = $this->getDb()->query('SELECT Id_Droit_Access FROM Droits WHERE Id_Utilisateur = '.$objet->getId_Utilisateur());

        $donneDroits = array();
        while ($donne = $requeteDroits->fetch(PDO::FETCH_ASSOC))
        {
            $donneDroits[] = $donne;
        }

        foreach($objet->getDroit() as $droit){
            $requeteDroit_Acces = $this->getDb()->query('SELECT Id_Droit_Acces FROM Droit_Acces WHERE Nom = '.$droit);
            $donneDroit_Acces = $requeteDroit_Acces->fetch(PDO::FETCH_ASSOC);

            if(!in_array($donneDroit_Acces['Id_Droit_Acces'],$donneDroits)){
                $requete = $this->getDb()->prepare('INSERT INTO Droits (Id_Droit_Acces,Id_Utilisateur) VALUES(:id_Droit_Acces,:id_Utilisateur)');

                $requete->execute(array('id_Droit_Acces' => $donneDroit_Acces['Id_Droit_Acces'],
                                        'id_Utilisateur' => $objet->getId_Utilisateur(),
                                        ));
            }

        }

    }

    //Suppression d'un utilisateur dans la BDD
    public function remove($objet)
    {
        $this->removeDroits($objet);

        $this->removeParente($objet);

        $this->getDb()->exec('DELETE FROM Utilisateur WHERE Id_Utilisateur = '.$objet->getId_Utilisateur());
    }

    //Suppression de tous les droits de l'adherent
    public function removeDroits($objet){
        $this->getDb()->exec('DELETE FROM Droits WHERE Id_Utilisateur = '.$objet->getId_Utilisateur());
    }

    //Suppression de tous les droits de l'adherent
    public function removeParente($objet){
        $this->getDb()->exec('DELETE FROM Parente WHERE Id_Utilisateur = '.$objet->getId_Utilisateur());
    }

    //Fonction qui retourne un utilisateur à partir de son id
    public function getId($id)
    {
        $requete = $this->getDb()->query('SELECT Id_Utilisateur, Nom, Prenom, Password, DateNaissance, Adresse, Mail, Telephone, Id_Sexe FROM Utilisateur WHERE Id_Utilisateur = '.$id);
        $donnees = $requete->fetch(PDO::FETCH_ASSOC);

        //Recupere le type du sexe de l'utilisateur
        $requeteType_Sexe = $this->getDb()->query('SELECT Type FROM Sexe WHERE Id_Sexe = '.$donnees['Id_Sexe']);
        $donneType_Sexe = $requeteType_Sexe->fetch(PDO::FETCH_ASSOC);

        $donnees['DateNaissance'] = new datetime($donnees['DateNaissance']);
        $donnees['Sexe'] = $donneType_Sexe['Type'];
        $donnees['Droit'] = $this->getDroit($id);
        $donnees['Parente'] = $this->getParente($id);

        unset($donnees['Id_Sexe']);

        return new Utilisateur($donnees);
    }

    public function getDroit($id){
        $droits

        $requeteDroits = $this->getDb()->query('SELECT Nom FROM Droits WHERE Id_Utilisateur = '.$id);

        while ($donne = $requeteDroits->fetch(PDO::FETCH_ASSOC))
        {
            $droits[] = $donne;
        }

        return $droits;
    }

    public function getParente($id){
        $enfants

        $requeteEnfants = $this->getDb()->query('SELECT Id_Enfant FROM Parente WHERE Id_Parent = '.$id);

        while ($donne = $requeteEnfants->fetch(PDO::FETCH_ASSOC))
        {
            $enfants[] = $donne;
        }

        return $enfants;
    }

    public function getMail($mail){
        $requete = $this->getDb()->query('SELECT Id_Utilisateur FROM Utilisateur WHERE Mail = '.$mail);
        $donnees = $requete->fetch(PDO::FETCH_ASSOC);

        return $this->getId($donnees['Id_Utilisateur']);
    }

    //Fonction qui retourne la liste de tous les utilisateur présents dans la BDD
    public function getList()
    {
        $utilisateurs = array();

        $requete = $this->getDb()->query('SELECT Id_Utilisateur FROM Utilisateur');

        while ($donnees = $requete->fetch(PDO::FETCH_ASSOC))
        {
            $utilisateurs[] = $this->getId($donnees['Id_Utilisateur']);
        }

        return $utilisateurs;
    }

    //Procédure qui met à jour un utilisateur donné en paramètre dans la BDD
    public function update($objet)
    {

        this->updateDroits($objet);

        this->updateParente($objet);

        //Recupere l'id du sexe de l'utilisateur
        $requeteId_Sexe = $this->getDb()->query('SELECT Id_Sexe FROM Sexe WHERE Type = '.$objet->getSexe());
        $donneId_Sexe = $requeteId_Sexe->fetch(PDO::FETCH_ASSOC);

        $requete = $this->getDb()->prepare('UPDATE Utilisateur SET Nom = :nom, Prenom = :prenom, Password = :password, DateNaissance = :dateNaissance, Adresse = :adresse, Mail = :mail, Telephone = :telephone, Id_Sexe = :id_sexe WHERE Id_Utilisateur = :id_Utilisateur');

        $requete->execute(array('nom' => $objet->getNom(),
                                'prenom' => $objet->getPrenom(),
                                'password' => $objet->getPassword(),
                                'dateNaissance' => $objet->getDateNaissance(),
                                'adresse' => $objet->getAdresse(),
                                'mail' => $objet->getMail(),
                                'telephone' => $objet->getTelephone(),
                                'id_Sexe' => $donneId_Sexe['Id_Sexe'],
                                'id_Utilisateur' => $objet->getId_Utilisateur(),
                               ));
    }

    public function updateDroit($objet){
        this->removeDroits($objet);
        this->addDroits($objet);
    }

    public function updateParente($objet){
        this->removeParente($objet);
        this->addParente($objet);
    }

}
?>
