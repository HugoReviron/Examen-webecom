//CONSTANTES DE CONFIG (voir aussi init.php)////////////////////////////////////
//Utulisateur->statut
const _U_CLIENT = 0xa0;
const _U_SELLER = 0xa1;
const _U_TECH   = 0xa2;

//Ticket->etat
const _T_OPEN = 0xb0;
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

//duree d'affichage de la message_box
const _MB_TIME = 4000;

//REQUETES AJAX/////////////////////////////////////////////////////////////////
function request_read(cmd, callback, args = {}){
    var formData = new FormData();
    formData.append("cmd", cmd);
    formData.append("access", _READ);
    formData.append("args", JSON.stringify(args));
    
    send_request(callback, formData);
}

function request_write(cmd, callback, form){
    var formData = new FormData(form);
    formData.append("cmd", cmd);
    formData.append("access", _WRITE);
    
    send_request(callback, formData);
}

function send_request(callback, formData){
    $.ajax("ajax.php", {
        method: "post",
        dataType: "json",
        contentType: false,
        processData: false,
        data: formData,
        success: (data)=>onSuccess(data, callback),
        error: onError
    });
}

function refresh_page(){
    //window.location.href = window.location.href -> Etrange mais bon, ca marche. 
    //window.location.reload() affiche une alerte pour le renvoi du POST qd present
    window.location.href = window.location.href;
}

function onSuccess(data, callback){
    if(data.code === _A_CNX) refresh_page();
    else if(data.code === _A_ERR) {
        console.log("Ajax error:", data.msg);
        message_box("Une erreur critique est survenue, contactez le ministere de l'interieur");
    }
    else if(data.code === _A_DATA){ 
        message_box(data.msg);
        $("form input").removeClass("error");
        for(var name of data.data){
            $("form input[name='"+name+"']").addClass("error");
            $("form textarea[name='"+name+"']").addClass("error");
            setTimeout(()=>{
                $("form input[name='"+name+"']").removeClass("error");
                $("form textarea[name='"+name+"']").removeClass("error");
            },_MB_TIME);
        }
    }
    else if(data.code === _A_WARN) message_box(data.msg);
    else if(data.code === _A_OK){
        message_box(data.msg);
        callback(data);
    }
}

function onError(evt){
    console.log("Ajax internal error:", evt);
}

//MESSAGE BOX///////////////////////////////////////////////////////////////////
function message_box(msg){
    if(msg !== ""){
        var div = document.createElement("div");
        var span = document.createElement("span");

        span.textContent = msg;
        $(div).addClass("mbox");
        div.appendChild(span);
        document.body.appendChild(div);

        setTimeout(()=>document.body.removeChild(div),_MB_TIME);
    }
}

//CALLBACK UPDATE PASSWORD//////////////////////////////////////////////////////
function onPwdUpdate(data){
    if(data.code === _A_OK){
        $("#form_pwd").hide();
        document.getElementById("form_pwd").reset();
    }
    else console.log(data.msg);
}

//CONSTRUCTION DE TEMPLATES/////////////////////////////////////////////////////
function template_get_values(root){
    var values = {};
    for(var child of root.children){
        if(child.className !== ""){
            if(child.nodeName === "INPUT") values[child.className] = $(child).val();
            else values[child.className] = $(child).html();
        }
        else if(child.nodeName === "LABEL" && child.children.length && child.children[0].className !== ""){
            values[child.children[0].className] = $(child.children[0]).html();
        }
    }
    return values;
}

function template_set_values(values, root){
    for(var child of root.children){
        var val = "undef";
        if(values.hasOwnProperty(child.className)){
            val = values[child.className];
            if(child.nodeName === "INPUT") $(child).val(val);
            else $(child).html(val);
        }
        else if(child.nodeName === "LABEL" && child.children.length && values.hasOwnProperty(child.children[0].className)){
            val = values[child.children[0].className];
            $(child.children[0]).html(val);
        }
    }
}

function template_clear(root){
    for(var child of root.children){
        if(child.className !== ""){
            if(child.nodeName === "INPUT") $(child).val("");
            else $(child).html("");
        }
        else if(child.nodeName === "FORM") child.reset();
        else if(child.nodeName === "LABEL" && child.children.length)
            $(child.children[0]).html("");
    }
}

function template_build(template_id, values, parent_id, id = ""){
    var template = document.getElementById(template_id).content.cloneNode(true).querySelector("div");
    var parent = document.getElementById(parent_id);
    
    if(id !== "") template.setAttribute("id", id);
    template_set_values(values, template);
    parent.appendChild(template);
}

//GESTION SHOW/HIDE/////////////////////////////////////////////////////////////
function show_popup(popup_id){
    $("#"+popup_id).removeClass("hidden");
    $("#"+popup_id).addClass("popup");
}

function hide_popup(popup_id){
    $("#"+popup_id).removeClass("popup");
    $("#"+popup_id).addClass("hidden");
}

function show_elem(elem_id){
    $("#"+elem_id).removeClass("hidden");
    $("#"+elem_id).addClass("visible-block");
}

function hide_elem(elem_id){
    $("#"+elem_id).removeClass("visible-block");
    $("#"+elem_id).addClass("hidden");
}

