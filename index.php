<?php
include_once './Lib/init.php';

if(SESSION::Is_logged()){
    if(SESSION::Get_status() == _U_CLIENT) include_once './ctrl/ctrl_client.php';
    else if(SESSION::Get_status() == _U_SELLER) include_once './ctrl/ctrl_vendeur.php';
    else if(SESSION::Get_status() == _U_TECH) include_once './ctrl/ctrl_technicien.php';
}
else include_once './ctrl/ctrl_loggin.php';

