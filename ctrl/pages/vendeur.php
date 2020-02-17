<?php 
/* DATA FROM ctrl_vendeur.php
 * $datalist_produits:string -> html a inserer au datalist produit
 * $datalist_clients:string -> html a inserer au datalist client
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accueil Vendeur</title>
        <link href="./css/core.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        include './fragments/frag_header.php';
        ?>
        <main>
            <form id="form_vente">
                <div>
                    <input type="hidden" name="client" value=""/>
                    <label>Rechercher client (nom / prenom / mail)</label>
                    <input list="client_list" onselect="selectClient(this)"/>
                    <input type="button" value="Creer client" onclick="show_popup('popup_client')"/>
                    <datalist id="client_list"><?= $datalist_clients ?></datalist>

                    <input type="hidden" name="produit" value=""/>
                    <label>Rechercher produit (libellé / reference)</label>
                    <input list="produit_list" onselect="selectProduit(this)"/>
                    <datalist id="produit_list"><?= $datalist_produits ?></datalist>
                </div>
                
                <label>Client:</label>
                <span id="client_show"></span>
                
                <label>Produit:</label>
                <span id="produit_show"></span>

                <label>Numero de serie:</label>
                <input type="text" name="num_serie" value="" minlength="15" maxlength="15"/>
                <label>Date de vente:</label>
                <input type="date" value="<?= date("Y-m-d", time()) ?>" />
                <input type="hidden" name="date"/>
                <input type="button" value="Valider" onclick="createVente(this.form)"/>
            </form>
            <div id="popup_client" class="hidden">
                <form id="form_client">
                    <label>Nom:</label>
                    <input type="text" name="nom" value=""/>
                    <label>Prenom:</label>
                    <input type="text" name="prenom" value=""/>
                    <label>Email:</label>
                    <input type="text" name="mail" value="" />
                    <label>Mot de passe:</label>
                    <input type="text" name="pwd" value="" />
                    <input type="button" value="Valider" 
                    onclick="request_write('Utilisateur.create', onCreateClient, this.form)"/>
                    <input type="button" value="Fermer" onclick="hide_popup('popup_client')"/>
                </form>
            </div>
        </main>
    </body>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="./Js/core.js" type="text/javascript"></script>
    <script src="./Js/vendeur.js" type="text/javascript"></script>
</html>