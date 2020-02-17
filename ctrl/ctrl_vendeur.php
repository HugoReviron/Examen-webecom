<?php
//Variables utilisées par la vue
$datalist_produits = "";
$datalist_clients = "";

//Construction des options des datalists de la vue
$produit = new Produits();
$produit->select_where();
while ($row = DB::Fetch()) {
    $produit = new Produits($row);
    $show = htmlentities("$produit->libelle ($produit->ref)");
    $datalist_produits .= "<option id='prod_$produit->id' value='$produit->id'>$show</option>";
}

$client = new Utilisateur();
$client->select_where(["statut"], ["="], [_U_CLIENT]);
while ($row = DB::Fetch()) {
    $client = new Utilisateur($row);
    $show = htmlentities("$client->prenom $client->nom ($client->mail)");
    $datalist_clients .= "<option id='cli_$client->id' value='$client->id'>$show</option>";
}

include_once './ctrl/pages/vendeur.php';

