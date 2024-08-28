<?php
include_once "../includes/config.php";
require_once "../classes/Areas_secundarias.php";
require_once "../classes/Imoveis.php";

$imo = new Imoveis();
if ($_REQUEST['imovel'] > 0) {
    $imovel_id = $_REQUEST['imovel'];
} else {
    $imovel_id = 'tmp';
}

print_r($_REQUEST);
echo "<br /><br />";
print_r($_FILES);
die();

// JQuery File Upload Plugin v1.5.0 by RonnieSan - (C)2009 Ronnie Garcia
if (!empty($_FILES)) {
        $tempFile   = $_FILES['Filedata']['tmp_name'];
	$targetPath = 'files/';
        $extensao   = explode(".", $_FILES['Filedata']['name']);
        foreach ($extensao as $key => $value) {
            $ext = $value;
        }
        $_FILES['Filedata']['name'] = $imovel_id.'.'.$ext;
	$targetFile =  $targetPath.$_FILES['Filedata']['name'];
	
	// Uncomment the following line if you want to make the directory if it doesn't exist
	// mkdir(str_replace('//','/',$targetPath), 0755, true);
	
	move_uploaded_file($tempFile,$targetFile);
    
        echo $tempFile.' - '.$targetFile;
        
        if ($imovel_id != 'tmp') {
            $imo->get($imovel_id);
            $imo->foto_url = $_FILES['Filedata']['name'];
            $imo->update();
        }
}
echo '1'; // Important so upload will work on OSX
?>