<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Padroes.php";

$zona = new Padroes();

if (isset($_POST['enviar'])) {
    $zona->codigo = $_POST['codigo'];
    $zona->descricao = $_POST['descricao'];
    if ($zona->insert())
        header("Location: padroes.php?msg=7");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->codigo = $_POST['codigo'];
    $zona->descricao = $_POST['descricao'];
    if ($zona->update())
        header("Location: padroes.php?msg=8");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: padroes.php?msg=9");
    } else {
        header("Location: padroes.php?show=1&id=".$_GET['id']."&msg=emUso");
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