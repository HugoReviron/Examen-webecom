<?php

class DB {
    private static $DSN;//PDO Database host
    private static $DBH;//PDO Database host
    private static $STMT;//PDO Statement
    
    private static function CONNECT(){
        self::$DBH = new PDO(self::$DSN, _DB_USER, _DB_PWD);
        self::$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    
    private static function SET_DSN($dsn){
        if($dsn !== self::$DSN) {
            self::$DSN = $dsn;
            self::Connect();
        }
    }    
    
    public static function Exec($dsn, $req, $params = []){
        self::SET_DSN($dsn);
        
        self::$STMT = self::$DBH->prepare($req);
        self::$STMT->execute($params);
    }
    
    public static function Fetch($fetch_style = PDO::FETCH_ASSOC){
        $row = self::$STMT->fetch($fetch_style);
        return $row === FALSE ? [] : $row;
    }
    
    public static function FetchAll($fetch_style = PDO::FETCH_ASSOC){
        return self::$STMT->fetchAll($fetch_style);
    }
    
    public static function LastInsertId(){
        return self::$DBH->lastInsertId();
    } 
}
