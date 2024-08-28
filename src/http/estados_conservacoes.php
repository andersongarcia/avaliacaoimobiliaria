<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Estados_de_conservacoes.php";

$zona = new Estados_de_conservacoes();

if (isset($_POST['enviar'])) {
    $zona->classificacao           = $_POST['classificacao'];
    $zona->descricao               = $_POST['descricao'];
    $zona->coeficiente_depreciacao = str_replace(',', '.', $_POST['coeficiente_depreciacao']);
    if ($zona->insert())
        header("Location: estados_conservacoes.php?msg=19");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->classificacao           = $_POST['classificacao'];
    $zona->descricao               = $_POST['descricao'];
    $zona->coeficiente_depreciacao = str_replace(',', '.', $_POST['coeficiente_depreciacao']);
    if ($zona->update())
        header("Location: estados_conservacoes.php?msg=20");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: estados_conservacoes.php?msg=21");
    } else {
        header("Location: estados_conservacoes.php?show=1&id=".$_GET['id']."&msg=emUso");
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