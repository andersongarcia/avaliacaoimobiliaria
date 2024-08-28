<?php

/**
 *
 * @author Anderson
 */
interface IExportarXLS {
    //put your code here
    public function AdicionarNumero($valor);
    public function AdicionarTexto($valor);
    public function GerarXLS($dados, $nome_do_arquivo = 'dados');
}

?>