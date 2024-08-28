<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Municipios.php";

$zona = new Municipios();

if (isset($_GET['alterarPadrao'])) {
    Utils::setConfiguracaoPadrao('municipio', $_GET['alterarPadrao']);
    header("Location: municipios.php");
}

if (isset($_POST['enviar'])) {
    $zona->nome = $_POST['nome'];
    if ($zona->insert())
        header("Location: municipios.php?msg=10");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->nome = $_POST['nome'];
    if ($zona->update())
        header("Location: municipios.php?msg=11");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: municipios.php?msg=12");
    } else {
        header("Location: municipios.php?show=1&id=".$_GET['id']."&msg=emUso");
    }
}

if (isset($_GET['id']))
    $zona->get($_GET['id']);
else
    $zona->find();

include_once INCLUDE_DIR.'/header.php';

if (isset($_GET['show'])) {
    echo $zona->showForm();
} else {
    echo $zona->showAll();
}

include_once INCLUDE_DIR.'/footer.php';
?>