<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Avaliacoes.php";
require_once CLASS_DIR."/Imoveis.php";
require_once CLASS_DIR."/Avaliacoes_imoveis.php";
require_once CLASS_DIR."/Imobiliarias.php";
require_once CLASS_DIR."/Imovel_tipos.php";
require_once CLASS_DIR."/Municipios.php";

$aval = new Avaliacoes();
$imov = new Imoveis();

include_once INCLUDE_DIR.'/header.php';
    
if (isset($_GET['imovel'])) {
    $imov->get($_GET['imovel']);

    $aval->imovel_id = $_GET['imovel'];
    if ($aval->find()) {
        $aval->get('imovel_id', $_GET['imovel']);
        $imov->showDelete($aval->id);
    } else {
        $imov->showDelete();
    }

}

include_once INCLUDE_DIR.'/footer.php';
?>
