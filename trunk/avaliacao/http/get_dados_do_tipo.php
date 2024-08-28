<?php
include_once("../includes/config.php");
require_once CLASS_DIR . '/Imovel_tipos.php';

$tipo = new Imovel_tipos();
$tipo->get($_GET['id']);

// Chama o construtor do template
$tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imovel_tipos');

// carrega o arquivo de template
$tpl->loadTemplateFile('get_dados_do_tipo.tpl.html');

// define exibição ou não dos campos
$tpl->setVariable('Forma', $tipo->forma);
$tpl->setVariable('ShowVagasDeGaragem', $tipo->vagas_de_garagem);
$tpl->setVariable('ShowWcs', $tipo->wcs);
$tpl->setVariable('ShowDormitorios', $tipo->dormitorios);
$tpl->setVariable('ShowSuites', $tipo->suites);
$tpl->setVariable('ShowArmarios', $tipo->armarios);
$tpl->setVariable('ShowReformas', $tipo->reformas);
$tpl->setVariable('ShowAndar', $tipo->andar);
$tpl->setVariable('ShowElevadores', $tipo->elevadores);
$tpl->setVariable('ShowFrenteFundos', $tipo->frente_fundos);
$tpl->setVariable('ShowFacesolSombra', $tipo->facesol_sombra);
$tpl->setVariable('ShowAreaDeLazer', $tipo->area_de_lazer);
$tpl->setVariable('ShowPiscina', $tipo->piscina);
$tpl->setVariable('ShowSalaoDeFestas', $tipo->salao_de_festas);
$tpl->setVariable('ShowCapacidadeDeUso', $tipo->capacidade_de_uso);
$tpl->setVariable('ShowSituacaoCirculacao', $tipo->situacao_circulacao);

return $tpl->show();


?>
