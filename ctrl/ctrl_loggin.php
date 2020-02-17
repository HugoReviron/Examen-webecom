<?php
$msg = "";
$mail = "";
$mail_class = "";
$pwd_class = "";

if(key_exists("mail", $_POST) && key_exists("pwd", $_POST)){
    $user = new Utilisateur();
    $user->init_from_array($_POST);
    $mail = $user->mail;
    
    if(($err = $user->validate_data(["mail", "pwd"]))){
        $msg = "Saisie invalide";
        
        if(in_array("mail", $err)) $mail_class = "error";
        if(in_array("pwd", $err)) $pwd_class = "error";
            
        include_once './ctrl/pages/loggin.php';
        exit();
    }
    
    $user->select_where(["mail"], ["="], [$_POST["mail"]]);
    $user->init_from_row(DB::Fetch());
    
    if($user->id === "" || !password_verify($_POST["pwd"], $user->pwd)){
        $msg = "Identifiant et/ou mot de passe incorrectes";
        include_once './ctrl/pages/loggin.php';
        exit();
    }
    
    //Connection reussie
    SESSION::Init_values($user->id, $user->mail ,$user->statut, $user->prenom, $user->nom);
    if(SESSION::Get_status() == _U_CLIENT) include_once './ctrl/ctrl_client.php';
    else if(SESSION::Get_status() == _U_SELLER) include_once './ctrl/ctrl_vendeur.php';
    else if(SESSION::Get_status() == _U_TECH) include_once './ctrl/ctrl_technicien.php';
}
else include_once './ctrl/pages/loggin.php';

