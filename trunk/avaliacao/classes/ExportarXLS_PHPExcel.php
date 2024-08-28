<?php
/**
 * Description of ExportarXLS_PHPExcel
 *
 * @author Anderson
 */

require_once CLASS_DIR . '/IExportarXLS.php';
require_once DIR . '/lib/php-excel.class.php';

class ExportarXLS_PHPExcel implements IExportarXLS {
    
    public function AdicionarNumero($valor){
        
    }
    
    public function AdicionarTexto($valor){
        
    }
    
    public function GerarXLS($dados, $nome_do_arquivo = 'dados'){
        $xls = new Excel_XML;
        $xls->setEncoding('iso-8859-1');
        $xls->addArray ( $dados );
        $xls->generateXML ( $nome_do_arquivo );
    }
}

?>
