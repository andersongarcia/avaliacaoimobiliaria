<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Zonas.php";

$zona = new Zonas();

if (isset($_GET['alterarPadrao'])) {
    Utils::setConfiguracaoPadrao('zona', $_GET['alterarPadrao']);
    header("Location: zonas.php");
}

if (isset($_POST['enviar'])) {
    $zona->codigo = $_POST['codigo'];
    $zona->descricao = $_POST['descricao'];
    if ($zona->insert())
        header("Location: zonas.php?msg=1");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->codigo = $_POST['codigo'];
    $zona->descricao = $_POST['descricao'];
    if ($zona->update())
        header("Location: zonas.php?msg=2");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: zonas.php?msg=3");
    } else {
        header("Location: zonas.php?show=1&id=".$_GET['id']."&msg=emUso");
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