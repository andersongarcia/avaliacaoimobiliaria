<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Melhoramentos.php";

$zona = new Melhoramentos();

if (isset($_POST['enviar'])) {
    $zona->descricao = $_POST['descricao'];
    if ($zona->insert())
        header("Location: melhoramentos.php?msg=13");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->descricao = $_POST['descricao'];
    if ($zona->update())
        header("Location: melhoramentos.php?msg=14");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete())
        header("Location: melhoramentos.php?msg=15");
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