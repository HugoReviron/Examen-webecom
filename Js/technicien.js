//DIRECT HANDLERS///////////////////////////////////////////////////////////////
//Lors de la selection d'un ticket: frag_ticket_list.php 
//Balise: <div id="ticket_<?= $ticket["id"] ?>" class="ticket-list" onclick="showTicket(this)">
var prev_elem;
function showTicket(elem){
    console.log(elem);
    var ticket_vals = template_get_values(elem);
    template_set_values(ticket_vals, document.getElementById("view_ticket"));
    $("#view_ticket .ticket").val(ticket_vals.id);
    
    request_read("Produits.get_by_id", onGetProduit, {produit: ticket_vals.vente_produit});
    request_read("Message.get_by_ticket", onGetMessages, {ticket: ticket_vals.id});
    
    if(prev_elem)$(prev_elem).removeClass("ticket-selected");
    $(elem).addClass("ticket-selected");
    prev_elem = elem;
}

//AJAX CALLBACKS////////////////////////////////////////////////////////////////
function onGetProduit(data){
    template_set_values(data.data, document.getElementById("view_produit"));
    show_elem("view_produit");
}

function onCreateMessage(data){
    $("#form_message textarea").val("");
    data.data["auteur_nom"] = "Vous";
    template_build("_message_list", data.data, "view_messages");
    if(data.data.ticket_pre_etat < _T_ANSWERED){
        var ticket_elem = $("#ticket_" + data.data.ticket_id);
        $("#list_tickets_answered").append(ticket_elem);
    }
}

function onGetMessages(data){
    $("#view_messages").empty();
    show_elem("view_ticket");
    for(var msg_data of data.data)
        template_build("_message_list", msg_data, "view_messages");
}

function onCloseTicket(data){
    template_clear(document.getElementById("view_produit"));
    template_clear(document.getElementById("view_ticket"));
    hide_elem("view_ticket");
    hide_elem("view_produit");

    $("#ticket_" + data.data.id).remove();
}


