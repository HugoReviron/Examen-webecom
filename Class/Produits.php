<?php

class Produits extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["ref", "libelle", "categorie", "description", "pv"];
        $this->regex = [];
        parent::__construct(_DB_DSN_EXT, $fields, $init_from, $foreign_keys);
    }
    
    //COMMANDES AJAX////////////////////////////////////////////////////////////
    //READ (param object args)//
    //Droits: Client, Technicien
    public function cmd_get_by_id($args){
        if(!property_exists($args, "produit"))
            Ajax_return(_A_WARN, "Veuillez selectionner un achat");
        
        $this->init_foreign_keys(["categorie" => "Categories"]);
        $this->select_where(["id"], ["="], [$args->produit]);
        $this->init_from_row(DB::Fetch());
        
        Ajax_return(_A_OK, "", $this->format_fields());
    }
}
