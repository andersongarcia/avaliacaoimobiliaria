<?php

/**
 * Table Definition for avaliacoes_filtros
 */
require_once '../pear/DB/DataObject.php';

class Avaliacoes_filtros extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'avaliacoes_filtros';  // table name
    public $id;                              // int(11)  not_null primary_key
    public $avaliacao_id;                    // int(11)  not_null multiple_key
    public $chave;                           // string(50) 
    public $valor;                           // string(50) 

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Avaliacoes_filtros', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $filtro = array();

    function getFiltro($campo) {
        $aval = new Avaliacoes();
        switch ($aval->campos[$campo]['template']) {
            case 'intervalo_valores_monetarios':
                $criterio = array();
                foreach ($this->filtro[$campo] as $valor) {
                    $valores = explode(';', $valor);
                    if((!$valores[0]) || ((int)$valores[0] == 0)){
                        if((!$valores[1]) || ((int)$valores[1] == 0))
                            return '';
                        $valores[0] = 1;
                    }
                    else 
                        $valores[0] = Utils::desformataValorMonetario($valores[0]);
                    
                    $valor_da_oferta = 'valor >= ' . $valores[0];
                    $valor_da_transacao = 'valor_da_transacao >= ' . $valores[0];
                    if ($valores[1]){
                        $valores[1] = Utils::desformataValorMonetario($valores[1]);
                        $valor_da_oferta .= ' AND valor <= ' . $valores[1];
                        $valor_da_transacao .= ' AND valor_da_transacao <= ' . $valores[1];
                    }
                    $valor = '((' . $valor_da_oferta . ') OR (' . $valor_da_transacao . '))';
                    array_push($criterio, $valor);
                }
                $this->filtro[$campo] = Utils::array_prefix_sufix($this->filtro[$campo], "(", ")");
                return implode(' OR ', $criterio);
            case 'intervalo_valores':
                $criterio = array();
                foreach ($this->filtro[$campo] as $valor) {
                    $valores = explode(';', $valor);
                    if((!$valores[0]) || ((int)$valores[0] == 0)){
                        if((!$valores[1]) || ((int)$valores[1] == 0))
                            return '';
                        $valores[0] = 0;
                    }
                    
                    $valor = $campo . ' >= ' . $valores[0];

                    if ($valores[1])
                        $valor .= ' AND ' . $campo . ' <= ' . $valores[1];

                    array_push($criterio, $valor);
                }
                $this->filtro[$campo] = Utils::array_prefix_sufix($this->filtro[$campo], "(", ")");
                return implode(' OR ', $criterio);
            case 'intervalo_datas':
                $criterio = array();
                $datas = array( 'data_do_evento', 'data_da_transacao' );
                foreach ($this->filtro[$campo] as $valor) {
                    $valores = explode(';', $valor);
                    if ($valores[0] > 0) {
                        if ($valores[1])
                            $valor = '(data_do_evento >= \'' . Utils::transformDate($valores[0]) . '\' AND data_do_evento <= \'' . Utils::transformDate($valores[1]) . '\') ' .
                                'OR (data_da_transacao >= \'' . Utils::transformDate($valores[0]) . '\' AND data_da_transacao <= \'' . Utils::transformDate($valores[1]) . '\')';
                        else
                            $valor = '(data_do_evento >= ' . Utils::transformDate($valores[0]) . ') ' .
                                'OR (data_da_transacao >=  ' . Utils::transformDate($valores[0]) . ')';
                    }
                    else {
                        if ($valores[1])
                            $valor = '(data_do_evento <= \'' . Utils::transformDate($valores[1]) . '\') ' .
                                '(data_da_transacao <= \'' . Utils::transformDate($valores[1]) . '\')';
                        else {
                            $valor = '';
                        }
                    }
                    array_push($criterio, $valor);
                }
                $this->filtro[$campo] = Utils::array_prefix_sufix($this->filtro[$campo], "(", ")");
                return implode(' OR ', $criterio);
            case 'select':
            case 'modalidade':
                $this->filtro[$campo] = Utils::array_prefix_sufix($this->filtro[$campo], "'", "'");
                return '(' . Utils::array_implode_prefix(' OR ', $this->filtro[$campo], ' = ', $campo) . ')';
            case 'texto':
                $this->filtro[$campo] = Utils::array_prefix_sufix($this->filtro[$campo], "'%", "%'");
                return '(' . Utils::array_implode_prefix(' OR ', $this->filtro[$campo], ' LIKE ', $campo) . ')';
        }
    }

    function setFiltro() {
        if (!$this->id)
            return;

        switch ($this->chave) {
            default:
                $this->filtro[$this->chave][] = $this->valor;
        }
    }

}
