<?php
//################################################ EDITAR O DIRETÓRIO ########
define('DIR', "c:/www/avaliacao/trunk");
define('INCLUDE_DIR', DIR."/includes");
define('CLASS_DIR', DIR."/classes");
define('HTTP_DIR', DIR."/http");
define('TEMPLATE_DIR', DIR."/template");

################################################ EDITAR A URL ##############
define('HOME', "http://localhost/avaliacao/trunk/");
define('URL', HOME . "/http");
define('IMAGE_URL', URL."/images");
define('CSS_URL', URL."/css");
define('JS_URL', URL."/js");

define('LANG', "pt-br");
$PHP_SELF = $_SERVER['PHP_SELF'];
session_start();

ini_set("include_path", DIR."/pear");

// Works as of PHP 4.3.0
//echo get_include_path();

require_once '../pear/DB/DataObject.php';
require_once '../pear/Template/Sigma.php';

//Da um require na classe do DataObject
$options = &PEAR::getStaticProperty('DB_DataObject','options');

################################################ EDITAR ESSE ARRAY #########
/*$options = array(
    'database'          => 'mysql://erickpre_erick:oemxl2!$@erickpreviato.com.br/erickpre_avaliacao',
    'schema_location'   => 'D:/htdocs/avaliacao/trunk/conf',
    'class_location'    => 'D:/htdocs/avaliacao/trunk/classes',
    'require_prefix'    => 'D:/htdocs/avaliacao/trunk/classes/',
    'quote_identifiers' => true
);*/
$options = array(
    'database'          => 'mysql://root@localhost/avaliacao',
    'schema_location'   => 'c:/www/avaliacao/trunk/conf',
    'class_location'    => 'c:/www/avaliacao/trunk/classes',
    'require_prefix'    => 'c:/www/avaliacao/trunk/classes/',
    'quote_identifiers' => true
);
////$config = parse_ini_file('db.ini',TRUE);
//Pega as configurações do arquivo db.ini que criamos no começo
////$options = $config['DB_DataObject'];

include_once 'login.php';
include_once CLASS_DIR.'/Utils.php';
include_once 'messages.php';

function formatDataAjax($data) {
        // Esta função tem o objetivo de evitar o SQL Injection
        // Consulte o Google [http://www.google.com.br/search?hl=pt-BR&q=sql+injection] para mais informações sobre o assunto.
        // E trata os acentos que poderão conter nos dados enviados através da URL
        $data = strip_tags($data);
        $data = trim($data);
        $data = get_magic_quotes_gpc() == 0 ? addslashes($data) : $data;
        $data = preg_replace("@(--|\#|\*|;)@s", "", $data);
        //$data = urldecode($data);   // específico no caso do Ajax
        //$data = utf8_decode($data); // específico no caso do Ajax
        return $data;
}

?>
