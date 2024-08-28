<?php

include_once("../includes/config.php");
require_once CLASS_DIR . "/Avaliacoes.php";
require_once CLASS_DIR . "/Avaliacoes_filtros.php";

$aval = new Avaliacoes();
$aval->imovel_id = $_REQUEST['imovel_id'];
$aval->find();
$aval->fetch();

switch ($_REQUEST['acao']) {
    case 'carregar':
        $aval->getCampoFiltro($_REQUEST['campo']);
        break;

    case 'incluir':
        $filtro = new Avaliacoes_filtros();
        $filtro->avaliacao_id = $aval->id;
        $filtro->chave = $_REQUEST['chave'];
        $filtro->valor = implode(';', $_POST['valor']);
        $filtro->valor = utf8_decode($filtro->valor);
        
        $filtro->insert();
        echo $aval->showElementosDePesquisa();
        break;

    case 'excluir':
        $filtro = new Avaliacoes_filtros();
        $filtro->avaliacao_id = $aval->id;
        $filtro->chave = $_POST['chave'];
        $filtro->valor = $_POST['valor'];
        $filtro->valor = utf8_decode($_POST['valor']);
        
        $filtro->find();
        if($filtro->fetch())
            $filtro->delete();
        echo $aval->showElementosDePesquisa();
        break;

    case 'filtro_ativo':
        $aval->showFiltroAtivo();
        break;
}
?>
