<?php
/**
 * Table Definition for avaliacoes_imoveis
 */
require_once '../pear/DB/DataObject.php';

class Avaliacoes_imoveis extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'avaliacoes_imoveis';    // table name
    public $imovel_id;                       // int(11)  not_null primary_key multiple_key
    public $avaliacao_id;                    // int(11)  not_null primary_key multiple_key
    public $selecionado;                     // int(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Avaliacoes_imoveis',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function atualizarAtivos($selecionados){
        if($this->avaliacao_id){
            $this->find();
            
            while ($this->fetch()){
                $this->selecionado = in_array($this->imovel_id, $selecionados);
                $this->update();
            }
        }
    }
}
