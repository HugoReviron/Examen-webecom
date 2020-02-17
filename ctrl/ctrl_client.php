<?php
//Variables utilisées par la vue
$datalist_ventes = "";
$tickets = [];
$tickets_answered = [];

//Construction des options du datalist de la vue
$vente = new Vente();
$vente->select_where(["client"], ["="], [SESSION::Get_uid()]);
while($row = DB::Fetch()){
    $vente = new Vente($row);
    $vente->format_dates();
    $show = htmlentities("$vente->num_serie (vendu le $vente->date)");
    $datalist_ventes .= "<option id='vente_$vente->id' data-prod='$vente->produit' value='$vente->id'>$show</option>";
}

//recuperation des tickets en cours
$ticket = new Ticket(NULL, ["vente" => "Vente"]);
$ticket->select_where(["client", "etat"], ["=", "<"], [SESSION::Get_uid(), _T_CLOSED]);
while($row = DB::Fetch()){
    $ticket = new Ticket($row, ["vente" => "Vente"]);
    $ticket->format_dates();
    
    if($ticket->etat == _T_ANSWERED) $tickets_answered[] = $ticket->format_fields();
    else $tickets[] = $ticket->format_fields();
}

include_once './ctrl/pages/client.php';

