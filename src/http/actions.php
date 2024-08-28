<?php
include_once("../includes/config.php");
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
require_once CLASS_DIR.'/Usuarios.php';

$imovel_tipo = new Imovel_tipos();
$municipio   = new Municipios();
$zona        = new Zonas();
$topografia  = new Topografias();
$padrao      = new Padroes();
$estado_cons = new Estados_de_conservacoes();
$imobiliaria = new Imobiliarias();

if (isset($_POST['verificarSenha'])) {
    $usu = new Usuarios();
    $usu->get($_POST['id']);
    $senha = md5($_POST['senha']);
    
    if ($senha == $usu->senha) {
        echo "OK";
        return;
    } else {
        echo "NO";
        return;
    }
    die();
}

if (isset($_POST['show'])) {
    
    if ($_POST['classe'] == 'imovel_tipo') {
        $imovel_tipo->getFormAjax();
    }
    if ($_POST['classe'] == 'municipio') {
        $municipio->getFormAjax();
    }
    if ($_POST['classe'] == 'zona') {
        $zona->getFormAjax();
    }
    if ($_POST['classe'] == 'topografia') {
        $topografia->getFormAjax();
    }
    if (($_POST['classe'] == 'padrao1') || ($_POST['classe'] == 'padrao2')) {
        $padrao->getFormAjax($_POST['classe']);
    }
    if ($_POST['classe'] == 'estado_conservacao') {
        $estado_cons->getFormAjax();
    }
    if ($_POST['classe'] == 'imobiliaria') {
        $imobiliaria->getFormAjax();
    }

}

if (isset($_POST['ajax'])) {
    
    if ($_POST['classe'] == 'imovel_tipo') {
	$imovel_tipo->descricao        = formatDataAjax($_POST['descricao']);
	$imovel_tipo->forma            = formatDataAjax($_POST['forma']);
	$imovel_tipo->vagas_de_garagem = formatDataAjax($_POST['vagas_de_garagem']);
        $imovel_tipo->wcs              = formatDataAjax($_POST['wcs']);
        $imovel_tipo->dormitorios      = formatDataAjax($_POST['dormitorios']);
        $imovel_tipo->suites           = formatDataAjax($_POST['suites']);
        
        echo $ret = $imovel_tipo->insert();
        return $ret;
    }
    if ($_POST['classe'] == 'municipio') {
	$municipio->nome = formatDataAjax($_POST['nome']);
        
        echo $ret = $municipio->insert();
        return $ret;
    }
    if ($_POST['classe'] == 'zona') {
	$zona->codigo    = formatDataAjax($_POST['codigo']);
        $zona->descricao = formatDataAjax($_POST['descricao']);
        
        echo $ret = $zona->insert();
        return $ret;
    }
    if ($_POST['classe'] == 'topografia') {
	$topografia->descricao = formatDataAjax($_POST['descricao']);
        
        echo $ret = $topografia->insert();
        return $ret;
    }
    if (($_POST['classe'] == 'padrao1') || ($_POST['classe'] == 'padrao2')) {
	$padrao->codigo    = formatDataAjax($_POST['codigo']);
        $padrao->descricao = formatDataAjax($_POST['descricao']);
        
        echo $ret = $padrao->insert();
        return $ret;
    }
    if ($_POST['classe'] == 'estado_conservacao') {
	$estado_cons->classificacao = formatDataAjax($_POST['classificacao']);
        $estado_cons->descricao     = formatDataAjax($_POST['descricao']);
        
        echo $ret = $estado_cons->insert();
        return $ret;
    }
    if ($_POST['classe'] == 'imobiliaria') {
	$imobiliaria->nome     = formatDataAjax($_POST['nome']);
	$imobiliaria->endereco = formatDataAjax($_POST['endereco']);
	$imobiliaria->contato  = formatDataAjax($_POST['contato']);
        $imobiliaria->fone     = formatDataAjax($_POST['fone']);
        
        echo $ret = $imobiliaria->insert();
        return $ret;
    }
    
}

if (isset($_POST['load'])) {

    if ($_POST['classe'] == 'imovel_tipo') {
        $imovel_tipo->getTiposImoveis($_POST['id']);
    }
    if ($_POST['classe'] == 'municipio') {
        echo $municipio->setMunicipios($_POST['id']);
    }
    if ($_POST['classe'] == 'zona') {
        echo $zona->setZonas($_POST['id']);
    }
    if ($_POST['classe'] == 'topografia') {
        echo $topografia->setTopografias($_POST['id']);
    }
    if (($_POST['classe'] == 'padrao1') || ($_POST['classe'] == 'padrao2')) {
        $tag = ($_POST['classe'] == 'padrao1') ? 'padrao_construcao_principal_id' : 'padrao_construcao_secundaria_id';
        $padrao->getPadroes($tag, $_POST['id']);
    }
    if ($_POST['classe'] == 'estado_conservacao') {
        echo $estado_cons->setEstadosConservacoes($_POST['id']);
    }
    if ($_POST['classe'] == 'imobiliaria') {
        echo $imobiliaria->setImobiliarias($_POST['id']);
    }

}

if (isset($_POST['area_secundaria_add'])) {
    $ret = '<div style="margin: 0; padding: 0;">';
    $ret .= Areas_secundarias::setPadroes().' ';
    $ret .= '<b>&Aacute;rea</b> <input type="text" value="" name="area_construcao_secundaria[]" size="6" onfocus="alterarM();" class="medida" /> m&sup2; &nbsp;';
    $ret .= '</div>';
    echo $ret;
}

?>
