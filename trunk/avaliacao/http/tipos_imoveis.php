<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Imovel_tipos.php";

function SetDados($zona){
    $zona->descricao        = $_POST['descricao'];
    $zona->forma            = $_POST['forma'];
    $zona->vagas_de_garagem = isset($_POST['vagas_de_garagem'])?'1':'0';
    $zona->wcs              = isset($_POST['wcs'])?'1':'0';
    $zona->dormitorios      = isset($_POST['dormitorios'])?'1':'0';
    $zona->suites           = isset($_POST['suites'])?'1':'0';
    $zona->armarios         = isset($_POST['armarios'])?'1':'0';
    $zona->reformas         = isset($_POST['reformas'])?'1':'0';
    $zona->andar            = isset($_POST['andar'])?'1':'0';
    $zona->frente_fundos    = isset($_POST['frente_fundos'])?'1':'0';
    $zona->facesol_sombra   = isset($_POST['facesol_sombra'])?'1':'0';
    $zona->elevadores       = isset($_POST['elevadores'])?'1':'0';
    $zona->area_de_lazer    = isset($_POST['area_de_lazer'])?'1':'0';
    $zona->piscina          = isset($_POST['piscina'])?'1':'0';
    $zona->salao_de_festas  = isset($_POST['salao_de_festas'])?'1':'0';
    $zona->capacidade_de_uso = isset($_POST['capacidade_de_uso'])?'1':'0';
    $zona->situacao_circulacao = isset($_POST['situacao_circulacao'])?'1':'0';
}

$zona = new Imovel_tipos();

if (isset($_POST['enviar'])) {
    SetDados($zona);
    if ($zona->insert())
        header("Location: tipos_imoveis.php?msg=16");
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    SetDados($zona);
    if($zona->update())
        header("Location: tipos_imoveis.php?msg=17");
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete()) {
        header("Location: tipos_imoveis.php?msg=18");
    } else {
        header("Location: tipos_imoveis.php?show=1&id=".$_GET['id']."&msg=emUso");
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