<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Imobiliarias.php";

$zona = new Imobiliarias();

if (isset($_POST['enviar'])) {
    $zona->nome     = $_POST['nome'];
    $zona->endereco = $_POST['endereco'];
    $zona->fone     = $_POST['fone'];
    if ($zona->insert())
        header("Location: imobiliarias.php?msg=22");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->nome     = $_POST['nome'];
    $zona->endereco = $_POST['endereco'];
    $zona->fone     = $_POST['fone'];
    if ($zona->update())
        header("Location: imobiliarias.php?msg=23");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: imobiliarias.php?msg=24");
    } else {
        header("Location: imobiliarias.php?show=1&id=".$_GET['id']."&msg=emUso");
    }
}

if (isset($_GET['id']))
    $zona->get($_GET['id']);
else
    $zona->find();

include_once INCLUDE_DIR.'/header.php';

if (isset($_GET['show'])) {
    $zona->showForm();
} else {
    $zona->showAll();
}

include_once INCLUDE_DIR.'/footer.php';
?>