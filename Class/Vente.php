<?php

class Vente extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["date", "num_serie", "client", "produit"];
        $this->regex = ["num_serie" => "/^[A-Z0-9]{15}$/"];
        parent::__construct(_DB_DSN_APP, $fields, $init_from, $foreign_keys);
    }
    
    public function format_dates(){
        $this->date = date(_DATE_FORMAT, $this->date);
    }
    
    //COMMANDES AJAX////////////////////////////////////////////////////////////
    //WRITE//
    //Droits: Vendeur
    public function cmd_create(){
        $this->init_from_array($_POST);
        $this->date = time();
        
        if(($err = $this->validate_data()))
            Ajax_return(_A_DATA, "Saisie incorrecte", $err);
        if($this->client === "")
            Ajax_return(_A_WARN, "Veuillez selectionner un client");
        if($this->produit === "")
            Ajax_return(_A_WARN, "Veuillez selectionner un produit");
        
        
        $this->insert();
        Ajax_return(_A_OK, "Vente enregistrée", $this->format_fields());
    }
}
