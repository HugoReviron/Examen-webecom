<?php 
/* DATA FROM ctrl_loggin.php
 * $msg:string -> message eventuel a afficher
 * $mail:string -> email
 * $mail_class -> vaut 'error' en cas de mauvaise saisie
 * $pwd_class -> vaut 'error' en cas de mauvaise saisie
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Loggin</title>
        <link href="./css/core.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <main>
            <div><span><?= $msg ?></span></div>
            <div>
                <form method="post" action="index.php">
                    <label>Email:</label>
                    <input class="<?= $mail_class ?>" type="text" name="mail" value="<?= $mail ?>"/>
                    <label>Mot de passe:</label>
                    <input class="<?= $pwd_class ?>" type="text" name="pwd" value=""/>
                    <input type="submit" value="Connexion"/>
                </form>
            </div>
        </main>
    </body>
</html>
