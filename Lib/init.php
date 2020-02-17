<?php
//password azeaze: $2y$10$Tl8AGuAFkWDhZuNyJArrQ.1Ni/KjBoufeDm2jfGcA/1/b7Y1MPeQa
//CONSTANTES DE CONFIG (voir aussi core.js)/////////////////////////////////////
const _DEBUG = TRUE;

//const _DB_DSN_APP = "mysql:host=localhost;dbname=projets_tickets_hreviron_dev;charset=UTF8";
//const _DB_DSN_EXT = "mysql:host=localhost;dbname=projets_tickets_produits;charset=UTF8";
const _DB_DSN_APP = "mysql:host=localhost;dbname=projets_tickets_hreviron;charset=UTF8";
const _DB_DSN_EXT = "mysql:host=localhost;dbname=projets_tickets_produits;charset=UTF8";
const _DB_USER = "";
const _DB_PWD = "";

const _DATE_FORMAT = "d-m-Y \à H:i:s";

//Utulisateur->statut
const _U_CLIENT = 0xa0;
const _U_SELLER = 0xa1;
const _U_TECH   = 0xa2;

//Ticket->etat
const _T_OPENED = 0xb0;
const _T_PENDING = 0xb1;//En attente d'action Technicien
const _T_ANSWERED = 0xb2;//En attente d'e retour'action Client
const _T_CLOSED = 0xb3;

//Code retour ajax
const _A_OK = 0xc0;
const _A_WARN = 0xc1;//Erreur non bloquante (ex: mauvais mot de passe)
const _A_DATA = 0xc2;//Données post rejetées par les regex (ex: mauvaise saisie formulaire)
const _A_ERR = 0xc3;//Erreur bloquante innatendue (ex: plantage requete sql, commande invalide)
const _A_CNX = 0xc4;//Connexion requise (ex: session expirée)

//Type d'acces de requete ajax
const _READ = 0xd0;
const _WRITE = 0xd1;

//CONFIG DE GESTION D'ERREURS///////////////////////////////////////////////////
if(_DEBUG){
    ini_set("display_errors", TRUE);
    error_reporting(E_ALL);
}

//AUTOLOADER DE CLASSES/////////////////////////////////////////////////////////
const PATHS = ["Lib", "Class"];
spl_autoload_register("autoloader");
function autoloader($class){
    foreach(PATHS as $dir) {
        $path = "./$dir/$class.php";
        if(file_exists($path)){
            require_once $path;
            break;
        }
    }
}

//DEMARRAGE DE SESSION//////////////////////////////////////////////////////////
SESSION::Start();

