<?php

abstract class Table {
    protected $DSN;
    protected $name;
    protected $fields;
    protected $links;
    protected $regex;

    public function __construct($datadase, $fields, $init_from, $foreign_keys) {
        $this->DSN = $datadase;
        $this->name = strtolower(get_called_class());
        $this->links = [];
        $this->init_foreign_keys($foreign_keys);
        
        $this->fields = [];
        $this->fields["id"] = "";
        foreach ($fields as $field) $this->fields[$field] = "";
        
        if(!is_null($init_from)){
            $init_type = gettype($init_from);
            if($init_type === "string") $this->init_from_id($init_from);
            else if($init_type === "array") $this->init_from_row($init_from);
        }
    }
    
    //INITIALISATION////////////////////////////////////////////////////////////
    public function init_foreign_keys($foreign_keys){
        foreach ($foreign_keys as $field => $type)
            $this->links[$field] = new $type();
    }
    
    public function init_from_id($id){
        $this->select_where(["id"], ["="], [$id]);
        $this->init_from_row(DB::Fetch());
    }
    
    public function init_from_row($row){
        foreach($row as $key => $value) {
            list($alias, $field) = explode(".", $key);
            if($alias === $this->name) $this->$field = $value;
            else if(key_exists($alias, $this->links)) $this->links[$alias]->$field = $value;
        }
    }
    
    public function init_from_array($array){
        foreach($array as $key => $value) $this->$key = $value;
    }
    
    //VALIDATION DES DONNEES////////////////////////////////////////////////////
    public function validate_data($fields = []){
        if(empty($fields)) $fields = array_keys($this->fields);
        $errors = [];
        foreach($fields as $field)
            if(key_exists($field, $this->regex) && !preg_match($this->regex[$field], $this->fields[$field]))
                $errors[] = $field;
            
        return empty($errors) ? FALSE : $errors;
    }
    
    //FORMATAGE/////////////////////////////////////////////////////////////////
    protected function format_set_string($fields){
        $str = [];
        
        foreach($fields as $key => $value) $str[] = "`$this->name`.`$key` = :$key";
        
        return implode(",", $str);
    }
    
    protected function format_set_params($fields){
        $params = [];
        
        foreach($fields as $key => $value) $params[":$key"] = $value;
        
        return $params;
    }
    
    protected function format_select_string($table_alias){
        $str = [];
        
        foreach($this->fields as $key => $value)
            $str[] = "`$table_alias`.`$key` as '$table_alias.$key'";
        
        foreach($this->links as $alias => $fk_obj)
            $str[] = $fk_obj->format_select_string($alias);
        
        return implode(",", $str);
    }
    
    protected function format_join_string(){
        $str = [];
        
        foreach($this->links as $alias => $fk_obj) {
            $str[] = "LEFT JOIN `$fk_obj->name` as $alias";
            $str[] = "ON `$alias`.`id` = `$this->name`.`$alias`";
        }
        
        return implode(" ", $str);
    }
    
    public function format_fields(){
        $html_fields = $this->fields;
        foreach($html_fields as $key => $value)
            $html_fields[$key] = htmlentities($value);
        
        foreach($this->links as $fk => $obj){
            $link_fields = $obj->format_fields();
            foreach($link_fields as $key => $value)
                $html_fields[$fk . "_" . $key] = $value;
        }
            
        return $html_fields;
    }
    
    //REQUETES BASIQUES/////////////////////////////////////////////////////////
    protected function insert(){
        $fields = $this->fields;
        unset($fields["id"]);
        
        $req = "INSERT INTO `$this->name` SET " . $this->format_set_string($fields);
        $params = $this->format_set_params($fields);

        DB::Exec($this->DSN, $req, $params);
        $this->id = DB::LastInsertId();
    } 
    
    protected function update($fields = []){
        if(empty($fields)){
            $fields = $this->fields;
            unset($fields["id"]);
        }
        else $fields = array_intersect_key($this->fields, array_flip($fields));
        
        $req = "UPDATE `$this->name` SET " . $this->format_set_string($fields);
        $req .= " WHERE `$this->name`.`id` = :id";
        $fields["id"] = $this->id;
        $params = $this->format_set_params($fields);

        DB::Exec($this->DSN, $req, $params);
    }
    
    public function select_where($fields = [], $operators = [], $values = []){
        $req = "SELECT " . $this->format_select_string($this->name) . " FROM `$this->name` ";
        $req .= $this->format_join_string();
        
        $params = [];
        for($i = 0; $i < count($fields); $i++){
            $key_word = $i === 0 ? "WHERE" : "AND";
            $req .= " $key_word `$this->name`.`$fields[$i]` $operators[$i] :$fields[$i]";
            $params[":$fields[$i]"] = $values[$i];
        }

        DB::Exec($this->DSN, $req, $params);
    }
    
    //GETTERS/SETTERS///////////////////////////////////////////////////////////
    public function set_link($field, $obj){
        //Pas de verif de coherence, ne pas faire nimp!
        $this->links[$field] = $obj;
    }
    
    public function __get($field) {
        return key_exists($field, $this->fields) ? $this->fields[$field] : "";
    }
    
    public function __set($field, $value) {
        if(key_exists($field, $this->fields)) $this->fields[$field] = $value;
    }
}
