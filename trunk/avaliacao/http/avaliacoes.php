<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Areas_secundarias.php";
require_once CLASS_DIR."/Avaliacoes.php";
require_once CLASS_DIR."/Imobiliarias.php";
require_once CLASS_DIR."/Imoveis.php";
require_once CLASS_DIR."/Imovel_tipos.php";
require_once CLASS_DIR."/Municipios.php";
require_once CLASS_DIR."/Avaliacoes_imoveis.php";
require_once CLASS_DIR."/Avaliacoes_filtros.php";
require_once CLASS_DIR."/Zonas.php";
require_once CLASS_DIR."/Estados_de_conservacoes.php";
require_once CLASS_DIR."/Imoveis_melhoramentos.php";
require_once CLASS_DIR."/Melhoramentos.php";
require_once CLASS_DIR."/Padroes.php";
require_once CLASS_DIR."/Topografias.php";

$aval = new Avaliacoes();
$imov = new Imoveis();
$im   = new Imoveis_melhoramentos();
$as   = new Areas_secundarias();

//print_r($_POST['area_construcao_secundaria']);
//die();

if(isset($_GET['duplicar']) && ($_GET['duplicar'] > 0)) {
    if ($_GET['id'] > 0) {
        $imovel->get($_GET['id']);

        $clone = new Imoveis();
        $clone = clone $imovel;
        $id = $clone->insert();

        if ($id) {
            header("Location: avaliacoes.php?new=1&duplicado=ok&imovel_id=".$id);
        }
    }
}

if (isset($_GET['show'])) {
    if (isset($_GET['imovel'])) {
        $aval->imovel_id = $_GET['imovel'];
        if (!$aval->find()) {
            $aval->data_avaliacao = date("Y-m-d H:i:s");
            $aval->insert();
            header("Location: avaliacoes.php?show=true&imovel=".$_GET['imovel']);
        }   
    }
}

if (isset($_POST['enviar'])) {
    $imov->setDados($_REQUEST);
    $id = $imov->insert();
    if ($id) {
        $imov->setDadosRelacionados($_REQUEST);

        $aval->imovel_id = $id;
        $aval->data_avaliacao = date("Y-m-d H:i:s");
        $id_aval = $aval->insert();

        if ($id_aval) {
            $af = new Avaliacoes_filtros();
            $af->avaliacao_id = $id_aval;
            $af->chave = 'modalidade';
            $af->valor = $imov->modalidade;
            $af->insert();
        }
        header("Location: avaliacoes.php?msg=25");
    }
} else if (isset($_POST['alterar'])) {
    $imov->get($_GET['imovel_id']);
    $imov->setDados($_REQUEST);

    if (($imov->update()) || ($_POST['alterou_m'])) {
        $imov->setDadosRelacionados($_REQUEST);
        header("Location: avaliacoes.php?msg=26");
    }
} else if (isset($_GET['excluir'])) {
    $aval->get($_GET['id']);
    
    $ai = new Avaliacoes_imoveis();
    $ai->avaliacao_id = $_GET['id'];

    if ($ai->find()) while($ai->fetch()) $ai->delete();
    
    $imovel = new Imoveis();
    $imovel->get($aval->imovel_id);
    
    if ($imovel->delete())
        header("Location: avaliacoes.php?msg=30");
}

if (isset($_GET['id']))
    $aval->get($_GET['id']);
else
    $aval->find();

if (isset($_GET['imovel_id']))
    $imov->get($_GET['imovel_id']);
else
    $imov->find();

include_once INCLUDE_DIR.'/header.php';

if (isset($_GET['show'])) {
    
    if (isset($_GET['imovel'])) {
        $imov->get($_GET['imovel']);
        
        $aval->imovel_id = $_GET['imovel'];
        if ($aval->find()) {
            $aval->get('imovel_id', $_GET['imovel']);
            //$imov->showFormAvaliacao($aval->id);
            
        } //else $imov->showFormAvaliacao();
        $aval->showForm();        
    } else {
        $imov->find();
        $imov->showFormImovel();
    }
    
} else {
    if (isset ($_GET['new'])) {
        $imov->showForm(true);
    } else
        $aval->showAll();
}

include_once INCLUDE_DIR.'/footer.php';
?>