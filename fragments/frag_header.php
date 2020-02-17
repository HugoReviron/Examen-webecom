
<header>
    <span>Bonjour <?= SESSION::Get_name() ?></span>
    <nav>
        <input type="button" value="Rafraichir la page" onclick="refresh_page()"/>

        <?php if(SESSION::Get_status() == _U_CLIENT): ?>
        <input type="button" value="Modifier mon email" onclick="$('#form_mail').toggle();$('#form_pwd').hide();"/>
        <?php endif; ?>

        <input type="button" value="Modifier mon mot de passe" onclick="$('#form_pwd').toggle();$('#form_mail').hide();"/>
        <input type="button" value="Deconnexion" onclick="request_read('kill', null)"/>
    </nav>
    <div>
        <form id="form_pwd" style="display:none">
            <label>Nouveau mot de passe:</label>
            <input type="text" name="pwd_new" value="">
            <label>Ancien mot de passe:</label>
            <input type="text" name="pwd" value="">
            <input type="button" value="Changer de mot de passe"
            onclick="request_write('Utilisateur.update_pwd', onPwdUpdate, this.form)">
        </form>

        <?php if(SESSION::Get_status() == _U_CLIENT): ?>
        <form id="form_mail" style="display:none">
            <label>Email:</label>
            <input type="text" name="mail" value="<?= SESSION::Get_mail() ?>">
            <label>Mot de passe:</label>
            <input type="text" name="pwd" value="">
            <input type="button" value="Changer d'email"
            onclick="request_write('Utilisateur.update_mail', onMailUpdate, this.form)">
        </form>
        <?php endif; ?>
    </div>
</header>

