//DIRECT HANDLERS///////////////////////////////////////////////////////////////
//Lors de la selection d'un produit: vendeur.php
//Balise: <input list="produit_list" onselect="selectProduit(this)"/>
function selectProduit(elem){
    var prod_id = elem.value;
    var prod_name = $("#prod_"+prod_id).html();
    
    $("input[name='produit']").val(prod_id);
    $("#produit_show").html(prod_name);
    $(elem).val("");
}

//Lors de la selection d'un client: vendeur.php
//Balise: <input list="client_list" onselect="selectClient(this)"/>
function selectClient(elem){
    var client_id = elem.value;
    var client_name = $("#cli_"+client_id).html();
    
    $("input[name='client']").val(client_id);
    $("#client_show").html(client_name);
    $(elem).val("");
}

function createVente(form){
    var date = new Date($(form).children("input[type='date']").val());
    var ts = Math.round(date.getTime() / 1000);
    $(form).children("input[name='date']").val(ts);
    
    request_write('Vente.create', onCreateVente, form);
}

//AJAX CALLBACKS////////////////////////////////////////////////////////////////
function onCreateVente(data){
    document.getElementById("form_vente").reset();
    $("input[name='client']").val("");
    $("input[name='produit']").val("");
    $("#client_show").html("");
    $("#produit_show").html("");
}

function onCreateClient(data){
    var client_name = data.data.prenom + " " + data.data.nom + " " + data.data.mail;
    $("#client_show").html(client_name);
    $("input[name='client']").val(data.data.id);
    hide_popup('popup_client');

    var new_opt = "<option id='cli_"+data.data.id+"' value='"+data.data.id+"'>"+client_name+"</option>";
    $("#client_list").prepend(new_opt);

    document.getElementById("form_client").reset();
}

