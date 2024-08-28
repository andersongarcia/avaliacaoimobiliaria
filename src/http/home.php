<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Avaliacoes.php";

include_once INCLUDE_DIR . '/header.php';

// Chama o construtor do template
$tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/pages');

// carrega o arquivo de template
$tpl->loadTemplateFile('home.tpl.html');

$aval = new Avaliacoes();

$tpl->setVariable('__ultimas_avaliacoes__', $aval->showLastest());

$tpl->show();


include_once INCLUDE_DIR . '/footer.php';
?>