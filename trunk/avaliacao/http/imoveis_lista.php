<?php
include_once("../includes/config.php");
require_once CLASS_DIR . "/Imoveis.php";

$imovel = new Imoveis();

echo $imovel->lista();

?>
