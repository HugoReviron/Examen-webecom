<?php

class Ticket extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["date_ouvert", "date_ferme", "description", "etat", "vente", "client"];
        $this->regex = ["description" => "/^.{15,}$/i"];
        parent::__construct(_DB_DSN_APP, $fields, $init_from, $foreign_keys);
    }
    
    public function format_dates(){
        $this->date_ouvert = date(_DATE_FORMAT, $this->date_ouvert);
        $this->date_ferme = $this->date_ferme == 0 ? "" : date(_DATE_FORMAT, $this->date_ferme);
    }
    
    //UTILITAIRES///////////////////////////////////////////////////////////////
    //Change l'etat du ticket en _T_PENDING ou _T_ANSWERED en fonction de qui
    //y a repondu _U_CLIENT ou _U_TECH respectivement
    //Appelée par Message->cmd_create
    public function change_state(){
        if(SESSION::Get_status() == _U_CLIENT){
            if($this->etat == _T_ANSWERED) $this->etat = _T_PENDING;
            else return;//Maj non necessaire
        }
        else if(SESSION::Get_status() == _U_TECH){
            if($this->etat == _T_ANSWERED) return;//Maj non necessaire
            else $this->etat = _T_ANSWERED;//si etat = _T_PENDING ou _T_OPENED
        }
        
        $this->update(["etat"]);
    }
    
    //COMMANDES AJAX////////////////////////////////////////////////////////////
    //WRITE//
    //Droits: Client
    public function cmd_create(){
        $this->init_from_array($_POST);
        
        if(($err = $this->validate_data()))
            Ajax_return(_A_DATA, "Saisie incorrecte", $err);
        if($this->vente === "")
            Ajax_return(_A_WARN, "Veuillez selectionner un achat");
        
        $this->client = SESSION::Get_uid();
        $this->etat = _T_OPENED;
        $this->date_ouvert = time();
        $this->date_ferme = 0;
        $this->insert();
        
        $this->init_foreign_keys(["vente" => "Vente"]);
        $this->init_from_id($this->id);
        
        $this->format_dates();
        Ajax_return(_A_OK, "Ticket crée", $this->format_fields());
    }
    
    //Droits: Client, Technicien
    public function cmd_close(){
        if(!isset($_POST["ticket"]) || $_POST["ticket"] === "")
            Ajax_return(_A_WARN, "Veuillez selectionner un ticket");
        
        $this->init_from_id($_POST["ticket"]);
        
        $this->etat = _T_CLOSED;
        $this->date_ferme = time();
        $this->update(["etat", "date_ferme"]);
        Ajax_return(_A_OK, "Ticket fermé", $this->format_fields());
    }
}
