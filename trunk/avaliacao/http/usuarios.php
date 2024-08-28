<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Usuarios.php";

$usu = new Usuarios();

if (isset($_GET['ativar'])) {
    $usu->get($_GET['id']);
    $usu->ativo = $_GET['ativar'];
    if ($usu->update())
        header("Location: usuarios.php?msg=23");
}

if (isset($_GET['alterar_dados'])) {
    $usu->get($_GET['id']);
    $usu->nome = $_POST['nome'];
    $usu->email = $_POST['email'];
    if ($usu->update())
        header("Location: usuarios.php?dadosPessoais=1&id=".$_GET['id']);
}
if ($_GET['alterar_senha']) {
    $usu->get($_GET['id']);
    $usu->senha = md5($_POST['nova_senha']);
    if ($usu->update())
        header("Location: usuarios.php?dadosPessoais=1&id=".$_GET['id']);
}

function SetDados($usu){
    $usu->nome  = $_POST['nome'];
    $usu->email = $_POST['email'];
    if (isset($_POST['senha']) && strlen($_POST['senha']) != '')
        $usu->senha = md5($_POST['senha']);
    $usu->permissao = $_POST['permissao'];
    $usu->ativo = $_POST['ativo'];
}

if (isset($_POST['enviar'])) {
    SetDados($usu);
    if ($usu->insert())
        header("Location: usuarios.php?msg=22");
} else if (isset($_POST['alterar'])) {
    $usu->get($_GET['id']);
    SetDados($usu);
    
    if ($usu->update())
        header("Location: usuarios.php?msg=23");
} else if (isset($_GET['excluir'])) {
    $usu->get($_GET['id']);
    if ($usu->delete())
        header("Location: usuarios.php?msg=24");
}

if (isset($_GET['id']))
    $usu->get($_GET['id']);
else
    $usu->find();

include_once INCLUDE_DIR.'/header.php';

if (isset($_GET['dadosPessoais'])) {
    $usu->showUpdateForm();
} else {
    if (isset($_GET['show'])) {
        $usu->showForm();
    } else {
        $usu->showAll();
    }
}
include_once INCLUDE_DIR.'/footer.php';
?>