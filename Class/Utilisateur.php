<?php

class Utilisateur extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["nom", "prenom", "mail", "pwd", "statut"];
        $this->regex = ["nom" => "/^[a-z -]{2,30}$/i",
                        "prenom" => "/^[a-z -]{2,30}$/i",
                        "mail" => "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",
                        "pwd" => "/^[0-9a-z -&'!?._]{6,30}$/i"];
        parent::__construct(_DB_DSN_APP, $fields, $init_from, $foreign_keys);
    }
    
    //COMMANDES AJAX////////////////////////////////////////////////////////////
    //WRITE//
    //Droits: Vendeur
    public function cmd_create(){
        $this->select_where(["mail"], ["="], [$_POST["mail"]]);
        $this->init_from_row(DB::Fetch());
        
        if(!empty($this->id))
            Ajax_return(_A_WARN, "Un compte existe deja a cette adresse");
        
        $this->init_from_array($_POST);
        
        if(($err = $this->validate_data()))
            Ajax_return(_A_DATA, "Saisie incorrecte", $err);
        
        $this->pwd = password_hash($this->pwd, PASSWORD_DEFAULT);
        $this->statut = _U_CLIENT;
        $this->nom = strtoupper($this->nom);
        $this->prenom = ucfirst(strtolower($this->prenom));
        $this->insert();
        Ajax_return(_A_OK, "Compte client crée", $this->format_fields());
    }
    
    //Droits: Client, Vendeur, Technicien
    public function cmd_update_pwd(){
        $this->init_from_id(SESSION::Get_uid());
        
        if(!password_verify($_POST["pwd"], $this->pwd))
            Ajax_return(_A_WARN, "Mauvais mot de passe");
        
        if(!preg_match($this->regex["pwd"], $_POST["pwd_new"]))
            Ajax_return(_A_DATA, "Saisie incorrecte", ["pwd_new"]);
        
        $this->pwd = password_hash($_POST["pwd_new"], PASSWORD_DEFAULT);
        $this->update(["pwd"]);
        Ajax_return(_A_OK, "Mot de passe modifié");
    }
    
    //Droits: Client
    public function cmd_update_mail(){
        $this->init_from_id(SESSION::Get_uid());
        
        if(!password_verify($_POST["pwd"], $this->pwd))
            Ajax_return(_A_WARN, "Mauvais mot de passe");
        
        if(($err = $this->validate_data(["mail"])))
            Ajax_return(_A_DATA, "Saisie incorrecte", $err);
        
        $this->mail = $_POST["mail"];
        $this->update(["mail"]);
        Ajax_return(_A_OK, "Email modifié", ["mail" => $this->mail]);
    }
}
