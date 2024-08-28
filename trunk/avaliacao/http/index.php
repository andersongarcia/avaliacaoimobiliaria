<?php
include_once("../includes/config.php");
require_once CLASS_DIR . "/Usuarios.php";

if (isset($_GET['logout']) && ($_GET['logout'] == 1)) {
    session_destroy();
    setcookie('AvaliacaoUsuario');
    setcookie('AvaliacaoTicket');
    header("Location: index.php");
}
$ret = '';
if (isset($_POST['efetuarLogin'])) {
    $usu = new Usuarios();
    $usu->email = $_POST['email'];
    
    $usu->find();

    if ($usu->find()) {
        $ret = $usu->autenticar($_POST['senha']);
    } else {
        $ret = 'Usuário não cadastrado';
    }
}
//session_destroy();
include_once INCLUDE_DIR . '/header.php';
?>
<div id="formLogin">
    <div class="quadro">
        <h2>Acesso Restrito</h2><br />
        <form method="post" action="index.php">
            <Br />
            <div>
                <label for="email">Email</label>
                <input type="text" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" /> <br /><br />
                <label for="senha">Senha</label>
                <input type="password" name="senha" /> <br /><br />
                <div class="error"><?= $ret ?></div>
                <input type="hidden" name="efetuarLogin" value="1" />
            </div>
            <div class="actions"><input type="submit" value="Login" /></div>
        </form>
    </div>
</div>
<?
include_once INCLUDE_DIR . '/footer.php';
?>
