<?php
require_once "lib/SimpleImage.class.php";

// JQuery File Upload Plugin v1.5.0 by RonnieSan - (C)2009 Ronnie Garcia
if (!empty($_FILES)) {
        if ($_REQUEST['imovel'] > 0) {
            $nome = $_REQUEST['imovel'];
        } else {
            $nome = 'tmp';
        }
        
       $image = new SimpleImage();
        
        $extensao = explode(".", $_FILES['Filedata']['name']);
        foreach ($extensao as $key => $value) {
            $ext = $value;
        }
        
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/avaliacao/http/files/';
	//$targetPath = '/home/rogeriog/public_html/avaliacao/http/files/';
	$targetFile =  str_replace('//','/',$targetPath) . trim($nome) .'.'. trim($ext);
	
	// Uncomment the following line if you want to make the directory if it doesn't exist
	// mkdir(str_replace('//','/',$targetPath), 0755, true);

        switch (strtoupper($ext)){
            case "png":
                $tipo = IMAGETYPE_PNG;
                break;
            case "gif":
                $tipo = IMAGETYPE_GIF;
                break;
            default:
                $tipo = IMAGETYPE_JPEG;
                break;
        }
        $image->load($tempFile);
        $image->resizeToWidth(440);
        $image->save($targetFile, $tipo, 80, 0755);
	
	//move_uploaded_file($tempFile,$targetFile);
}
echo '1'; // Important so upload will