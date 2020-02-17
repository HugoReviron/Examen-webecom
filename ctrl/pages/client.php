<?php 
/* DATA FROM ctrl_client.php
 * $tickets:array -> liste des tickets en attente d'une reponse technicien
 * list_tickets_answered -> liste des tickets en attente d'une reponse client
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accueil Client</title>
        <link href="./css/core.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './fragments/frag_header.php';
        ?>
        <main class="main-ticket">
            <div>
                <div>
                    <h4>Rechercher un achat:</h4>
                    <label>Par numero de serie / date:
                    <input list="vente_list" onselect="selectVente(this)"/>
                    </label>
                    <datalist id="vente_list"><?= $datalist_ventes ?></datalist>
                    <label>Achat selectionné:
                        <span id="vente_show">aucun</span>
                    </label>
                </div>
                <div id="view_produit" class="hidden">
                    <h4>Resumé du produit:</h4>
                    <label>Reference:
                    <span class="ref"></span>
                    </label>
                    <label>Libellé:
                    <span class="libelle"></span>
                    </label>
                    <label>Categorie:
                    <span class="categorie_libelle"></span>
                    </label>
                    <label>Prix:
                    <span class="pv"></span>
                    </label>
                    <label>Description:</label>
                    <p class="description"></p>
                </div>
                <form id="form_ticket" class="hidden">
                    <h4>Ouvrir un ticket pour ce produit:</h4>
                    <input type="hidden" name="vente" value=""/>
                    <label>Description du probleme:</label>
                    <textarea name="description"></textarea>
                    <input type="button" value="Envoyer" 
                    onclick="request_write('Ticket.create', onCreateTicket, this.form)"/>
                </form>
                <div id="view_ticket" class="hidden">
                    <h4>Resumé du ticket:</h4>
                    <label>Date:
                        <span class="date_ouvert"></span>
                    </label>
                    <label>Numero de serie du produit:
                        <span class="vente_num_serie"></span>
                    </label>
                    <label>Description:</label>
                    <p class="description"></p>
                    <input class="id" type="hidden" value=""/>
                    
                    <form id="form_message">
                        <input class="ticket" name="ticket" type="hidden" value=""/>
                        <label>Envoyer un message:</label>
                        <textarea name="texte"></textarea>
                        <input type="button" value="Envoyer" 
                        onclick="request_write('Message.create', onCreateMessage, this.form)"/>
                        <input type="button" value="Fermer le ticket" 
                        onclick="request_write('Ticket.close', onCloseTicket, this.form)"/>
                    </form>
                    
                    <h4>Historique des messages:</h4>
                    <div id="view_messages">
                    </div>
                </div>
            </div>
            <aside>
                <h3>Un technicien vous a repondu:</h3>
                <div id="list_tickets_answered">
                    <?php foreach($tickets_answered as $ticket)
                        include './fragments/frag_ticket_list.php';
                    ?>
                </div>
                <h3>Vos tickets en attente:</h3>
                <div id="list_tickets">
                    <?php foreach($tickets as $ticket)
                        include './fragments/frag_ticket_list.php';
                    ?>
                </div>
            </aside>
        </main>
    </body>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="./Js/core.js" type="text/javascript"></script>
    <script src="./Js/client.js" type="text/javascript"></script>
</html>

<template id="_ticket_list">
    <?php
    //Creation d'un objet ticket vide pour le template
    $ticket = new Ticket(NULL, ["vente" => "Vente"]);
    $ticket = $ticket->format_fields();
    include './fragments/frag_ticket_list.php';
    ?>
</template>

<template id="_message_list">
    <?php
    include './fragments/frag_message_list.php';
    ?>
</template>
