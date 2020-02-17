
<div id="ticket_<?= $ticket["id"] ?>" class="ticket-list" onclick="showTicket(this)">
    <label>Date:
        <span class="date_ouvert"><?= $ticket["date_ouvert"] ?></span>
    </label>
    <label>Numero de serie du produit:
        <span class="vente_num_serie"><?= $ticket["vente_num_serie"] ?></span>
    </label>
    <label>Description:
        <span class="description"><?= $ticket["description"] ?></span>
    </label>
    <input class="vente_produit" type="hidden" value="<?= $ticket["vente_produit"] ?>"/>
    <input class="id" type="hidden" value="<?= $ticket["id"] ?>"/>
    <!--<input type="button" value="Voir" onclick="showTicket(this.parentElement)"/>  showTicket(this)-->
</div>

