<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Topografias.php";

$zona = new Topografias ();

if (isset($_POST['enviar'])) {
    $zona->descricao = $_POST['descricao'];
    if ($zona->insert())
        header("Location: topografias.php?msg=4");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->descricao = $_POST['descricao'];
    if ($zona->update())
        header("Location: topografias.php?msg=5");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: topografias.php?msg=6");
    } else {
        header("Location: topografias.php?show=1&id=".$_GET['id']."&msg=emUso");
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