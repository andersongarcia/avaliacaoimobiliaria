<?php
include_once("../includes/config.php");
include_once("../lib/html_to_doc.inc.php");
require_once CLASS_DIR."/Avaliacoes.php";

if(isset($_REQUEST['imovel_id'])){
    $aval = new Avaliacoes();
    
    $aval->imovel_id = $_REQUEST['imovel_id'];
    
    $aval->ShowReport($_REQUEST['formato']);
}
?>
