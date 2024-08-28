<?php
require_once CLASS_DIR . "/Usuarios.php";

$usuarioLogado = new Usuarios();
$usuarioLogado->checaAutenticacao();
    

?>