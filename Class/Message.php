<?php

class Message extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["date", "texte", "auteur", "ticket"];
        $this->regex = ["texte" => "/^.{15,}$/i"];
        parent::__construct(_DB_DSN_APP, $fields, $init_from, $foreign_keys);
    }
    
    public function format_dates(){
        $this->date = date(_DATE_FORMAT, $this->date);
    }
    
    //COMMANDES AJAX////////////////////////////////////////////////////////////
    //READ (param object args)//
    //Droits: Client, Technicien
    public function cmd_get_by_ticket($args){
        $this->init_foreign_keys(["auteur" => "Utilisateur"]);
        $this->select_where(["ticket"], ["="], [$args->ticket]);
        
        $messages = [];
        while($row = DB::Fetch()){
            $message = new Message($row, ["auteur" => "Utilisateur"]);
            $message->format_dates();
            $html_fields = $message->format_fields();
            
            //Annonymisation des clients
            if(SESSION::Get_uid() == $message->auteur)
                $html_fields["auteur_nom"] = "Vous";
            else if(SESSION::Get_status() == _U_TECH && $html_fields["auteur_statut"] == _U_CLIENT)
                $html_fields["auteur_nom"] = "Client";
            else $html_fields["auteur_nom"] = $html_fields["auteur_prenom"] . " " . $html_fields["auteur_nom"];
            
            //Purge des données sensibles de l'auteur
            unset($html_fields["auteur_mail"]);
            unset($html_fields["auteur_pwd"]);
            unset($html_fields["auteur_prenom"]);
            unset($html_fields["auteur_id"]);
            unset($html_fields["auteur"]);
            
            $messages[] = $html_fields;
        }
        
        Ajax_return(_A_OK, "", $messages);
    }
    
    //WRITE//
    //Droits: Client, Technicien
    public function cmd_create(){
        $this->init_from_array($_POST);
        
        if(($err = $this->validate_data()))
            Ajax_return(_A_DATA, "Saisie incorrecte", $err);
        if($this->ticket === "")
            Ajax_return(_A_WARN, "Veuillez selectionner un ticket");
        
        $this->auteur = SESSION::Get_uid();
        $this->date = time();
        $this->insert();
        
        $ticket = new Ticket($this->ticket);
        $prev_state = $ticket->etat;
        $ticket->change_state();
        $this->set_link("ticket", $ticket);
        
        //Ajout de l'ancien etat du ticket afin d'update la vue en consequence
        $this->format_dates();
        $data = $this->format_fields();
        $data["ticket_pre_etat"] = $prev_state;
        
        Ajax_return(_A_OK, "Message envoyé", $data);
    }
}
