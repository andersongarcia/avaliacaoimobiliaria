<?php
include_once("../includes/config.php");
require_once CLASS_DIR . "/Avaliacoes.php";

$aval = new Avaliacoes();

if (isset($_GET['avaliacao'])) {
    $aval->id = $_GET['avaliacao'];
    $aval->find();
    echo $aval->showElementosDePesquisa();
}
?>
