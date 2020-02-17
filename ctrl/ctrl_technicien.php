<?php
//Variables utilisées par la vue
$tickets_opened = [];
$tickets_pending = [];
$tickets_answered = [];

//recuperation des tickets non _T_CLOSED
$ticket = new Ticket(NULL, ["vente" => "Vente"]);
$ticket->select_where(["etat"], ["<"], [_T_CLOSED]);
while($row = DB::Fetch()){
    $ticket = new Ticket($row, ["vente" => "Vente"]);
    $ticket->format_dates();
    
    if($ticket->etat == _T_OPENED) $tickets_opened[] = $ticket->format_fields();
    else if($ticket->etat == _T_PENDING) $tickets_pending[] = $ticket->format_fields();
    else $tickets_answered[] = $ticket->format_fields();
}

include_once './ctrl/pages/technicien.php';

