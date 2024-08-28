<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Avaliacoes.php";
require_once CLASS_DIR."/Avaliacoes_imoveis.php";
require_once CLASS_DIR."/Imoveis.php";
require_once CLASS_DIR.'/Municipios.php';
require_once CLASS_DIR.'/Imovel_tipos.php';
require_once CLASS_DIR.'/Imoveis_melhoramentos.php';
require_once CLASS_DIR.'/Padroes.php';
require_once CLASS_DIR.'/Zonas.php';
require_once CLASS_DIR.'/Topografias.php';
require_once CLASS_DIR.'/Melhoramentos.php';
require_once CLASS_DIR.'/Imobiliarias.php';
require_once CLASS_DIR.'/Estados_de_conservacoes.php';

$aval = new Avaliacoes();
$imov = new Imoveis();
$ai   = new Avaliacoes_imoveis();

if (isset($_POST['avaliacao']) && isset($_POST['imovel'])) {
    $ai->imovel_id = $_POST['imovel'];
    $ai->avaliacao_id = $_POST['avaliacao'];
    $aval->id = $ai->avaliacao_id;
    switch ($_POST['acao']){
        case 'inserir':
            $ai->insert();
            break;
        case 'excluir':
            $ai->delete();
            break;
        case 'ativar':
            $ai->selecionado = true;
            $ai->update();
            break;
        case 'desativar':
            $ai->selecionado = false;
            $ai->update();
            break;
    }
    echo $aval->showSelecionados();
    exit;
}
if (isset($_POST['avaliacao']) && isset($_POST['imoveisSelecionados'])) {
    switch ($_POST['acao']){
        case 'inserir_selecionados':
            $ai->avaliacao_id = $_POST['avaliacao'];
            $aval->id = $ai->avaliacao_id;
            foreach ($_POST['imoveisSelecionados'] as $selecionado) {
                $ai->imovel_id = $selecionado;
                $ai->insert();
            }
            echo $aval->showSelecionados();
            exit;
            break;
        default:
            $ai->avaliacao_id = $_POST['avaliacao'];
            $ai->AtualizarAtivos($_POST['imoveisSelecionados']);
            echo "ok";
            exit;
    }
}
if (isset($_POST['avaliacao']) && ($_POST['acao'] == 'excluir_todos')) {
    $ai->avaliacao_id = $_POST['avaliacao'];
    $aval->id = $ai->avaliacao_id;
    $ai->delete();
    echo $aval->showSelecionados();
    exit;
}
if ($_POST['classe'] == 'filtro') {
    
    if (isset($_POST['show'])) {
        
        /*
        var modalidade = $("input#modalidade").val();
        var dataIni    = $("input#dataIni").val();
        var dataFim    = $("input#dataFim").val();*/
        if ($aval->find())
            while ($aval->fetch()) {
                $imov->whereAdd("id <> ".$aval->imovel_id);
            }
        //if (isset($_POST['imovel'])) $imov->whereAdd("id <> ".$_POST['imovel']);
        
        if ($_POST['municipio'] > 0) $imov->whereAdd("municipio_id = '".$_POST['municipio']."'");
        if ($_POST['tipoImovel'] > 0) $imov->whereAdd("imovel_tipo_id = '".$_POST['tipoImovel']."'");
        if ($_POST['setor'] != null) $imov->whereAdd("setor like '%".$_POST['setor']."%'");
        if ($_POST['modalidade'] != null) $imov->whereAdd("modalidade like '%".$_POST['modalidade']."%'");
        if (($_POST['dataIni'] != null) && ($_POST['dataFim'] != null)) {
            $imov->whereAdd("data_do_evento between '".Utils::transformDate($_POST['dataIni'])."' and '".Utils::transformDate($_POST['dataFim'])."'");
        } else if ($_POST['dataIni'] != null) {
            $imov->whereAdd("data_do_evento > '".Utils::transformDate($_POST['dataIni'])."'");
        } else if ($_POST['dataFim'] != null) {
            $imov->whereAdd("data_do_evento < '".Utils::transformDate($_POST['dataFim'])."'");
        }
        
        $imov->find();
        $imov->showFormImovel($_POST['parte'], $_POST['aval']);
        
    } else {
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');
        $tpl->loadTemplateFile('filtro.html');
                
        $tpl->setVariable(array(
            'Municipio' => Municipios::getMunicipios2(),
            'TipoImovel' => Imovel_tipos::getTiposImoveis2(),
        ));

        $tpl->show();
        //include(TEMPLATE_DIR."/avaliacoes/filtro.html");
    }
    
    die();
}
if (isset($_POST['show'])) {
    $parte = isset($_POST['parte'])?$_POST['parte']:null;
    
    if ($parte == '2') {
        if ($aval->find())
            while ($aval->fetch()) {
                $imov->whereAdd("id <> '".$aval->imovel_id."'");
            }
    } else {
        if ($aval->find())
            while ($aval->fetch()) {
                $imov->whereAdd("id = '".$aval->imovel_id."'");
            }
    }
    //if (isset($_POST['imovel'])) $imov->whereAdd("id <> ".$_POST['imovel']);
    $imov->find();
    $aval_id = isset($_POST['aval'])?$_POST['aval']:null;
    $imov->showFormImovel($parte, $aval_id);
    
    die();
}
?>