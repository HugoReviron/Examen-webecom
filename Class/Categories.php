<?php

class Categories extends Table {
    public function __construct($init_from = NULL, $foreign_keys = array()) {
        $fields = ["libelle"];
        $this->regex = [];
        parent::__construct(_DB_DSN_EXT, $fields, $init_from, $foreign_keys);
    }
}
