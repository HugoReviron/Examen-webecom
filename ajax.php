<?php
include_once './Lib/init.php';

//DEFINITION DES DROITS/////////////////////////////////////////////////////////
const _RIGHTS = [
_U_CLIENT => [
    "Utilisateur" => ["cmd_update_pwd", "cmd_update_mail"],
    "Produits" => ["cmd_get_by_id"],
    "Message" => ["cmd_get_by_ticket", "cmd_create"],
    "Ticket" => ["cmd_create", "cmd_close"]
    ],
_U_SELLER => [
    "Utilisateur" => ["cmd_create", "cmd_update_pwd"],
    "Vente" => ["cmd_create"]
],
_U_TECH => [
    "Utilisateur" => ["cmd_update_pwd"],
    "Produits" => ["cmd_get_by_id"],
    "Message" => ["cmd_get_by_ticket", "cmd_create"],
    "Ticket" => ["cmd_close"]
]];
//Verification des droits en fonction du statut de l'utilisateur
function Can_I($obj_type, $method_name){
    $my_rights = _RIGHTS[SESSION::Get_status()];
    return (key_exists($obj_type, $my_rights) && in_array($method_name, $my_rights[$obj_type]));
}

//FORMATAGE DE RETOUR///////////////////////////////////////////////////////////
function Ajax_return($code, $msg = "", $data = []){
    if(!_DEBUG && $code === _A_ERR) $msg = "";
    $return_data = ["code" => $code, "msg" => $msg, "data" => $data];
    if(_DEBUG) $return_data["post"] = $_POST;
    echo json_encode($return_data);
    exit;
}

//TRAITEMENT DE LA REQUETE//////////////////////////////////////////////////////
if(SESSION::Is_logged() && key_exists("cmd", $_POST) && key_exists("access", $_POST)){
    if($_POST["cmd"] === "kill") {
        SESSION::Kill();
        Ajax_return(_A_CNX);
    }
    
    list($obj_type, $method) = explode(".", $_POST["cmd"]);
    $access = $_POST["access"];
    $method_name = "cmd_$method";
    
    //Verif de la requete
    if(!method_exists($obj_type, $method_name)) 
        Ajax_return(_A_ERR, "AJAX Invalid Request $obj_type.$method_name");
    
    //Verif des droits
    if(!Can_I($obj_type, $method_name)) 
        Ajax_return(_A_ERR, "AJAX Forbidden Request $obj_type.$method_name");
    
    //Verif du type d'access
    if($access != _READ && $access != _WRITE) 
        Ajax_return(_A_ERR, "AJAX Invalid access $obj_type.$method_name $access ");
    
    //Execution de la requete
    try {
        $obj = new $obj_type();
        if($access == _READ) $obj->$method_name(json_decode($_POST["args"]));
        else $obj->$method_name();
    } 
    catch (Exception $ex) {
        Ajax_return(_A_ERR, $ex->getMessage());
    }
}
else Ajax_return(_A_CNX);



