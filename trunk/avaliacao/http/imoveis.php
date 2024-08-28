<?php
include_once("../includes/config.php");
require_once CLASS_DIR . "/Imoveis.php";

//print_r($_POST['padrao_construcao_secundaria_id']);
//die($_POST['valor']);

$imovel = new Imoveis();

if(isset($_GET['exportar']) && ($_GET['exportar'] > 0)){
    $imovel->ExportarXLS();
    exit;
}

if(isset($_GET['duplicar']) && ($_GET['duplicar'] > 0)) {
    if ($_GET['id'] > 0) {
        $imovel->get($_GET['id']);

        $clone = new Imoveis();
        $clone = clone $imovel;
        $id = $clone->insert();
        
        $clone->cloneDadosRelacionados($imovel);

        if ($id) {
            header("Location: imoveis.php?show=1&duplicado=ok&id=".$id);
        }
    }
}

if (isset($_POST['enviar'])) {
    $imovel->setDados($_REQUEST);
    $id = $imovel->insert();

    if ($id) {
        $imovel->setDadosrelacionados($_REQUEST);
        
        $ext = explode(".", $_REQUEST['foto_url']);
        $nome = $id.'.'.$ext[1];
        rename("files/" . $_POST['foto_url'], "files/" . $nome);
        $imovelTmp = new Imoveis();
        $imovelTmp->get($id);
        $imovelTmp->foto_url = $nome;
        if ($imovelTmp->update())
            header("Location: imoveis.php?msg=25");
    }
} else if (isset($_POST['alterar'])) {
    $imovel->get($_GET['id']);
    $imovel->setDados($_REQUEST);
    if (($imovel->update()) || (isset($_POST['alterou_m']))) {
        $imovel->setDadosrelacionados($_REQUEST);
        header("Location: imoveis.php?msg=26");
    }
} else if (isset($_GET['excluir'])) {
    $imovel->get($_GET['id']);
    if ($imovel->delete())
        return "true";
    else {
        echo "false";
    }
    exit;
    //$error = &PEAR::getStaticProperty('DB_DataObject','lastError');
    //echo $error->getUserInfo();exit;
}

if (isset($_GET['id']))
    $imovel->get($_GET['id']);
else
    $imovel->find();

include_once INCLUDE_DIR . '/header.php';

if (isset($_GET['show'])) {
    $imovel->showForm();
} else {
    $imovel->showAll();
}

include_once INCLUDE_DIR . '/footer.php';
?>