<?php

/**
 * Table Definition for areas_secundarias
 */
require_once '../pear/DB/DataObject.php';

class Areas_secundarias extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'areas_secundarias';    // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $area_construcao;                 // real(12)  
    public $padrao_construcao_id;            // int(11)  multiple_key
    public $imovel_id;                       // int(11)  multiple_key

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Areas_secundarias', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getAreasSecundarias($imovel) {
        $ret = '';
        $as = new Areas_secundarias();
        $as->imovel_id = $imovel;
        if ($as->find()) {
            while ($as->fetch()) {
                $ret .= Padroes::getPadroes('padrao_construcao_secundaria_id[]', $as->padrao_construcao_id) . ' ';
                $ret .= '<b>&Aacute;rea</b> <input type="text" value="' . $as->area_construcao . '" name="area_construcao_secundaria[]" size="6" onfocus="alterarM();" class="medida" /> m&sup2; &nbsp;';
            }
        }
        return $ret;
    }

    function setAreasSecundarias($imovel) {
        $ret = '';
        if ($imovel) {
            $as = new Areas_secundarias();
            $as->imovel_id = $imovel;
            if ($as->find()) {
                while ($as->fetch()) {
                    $ret .= '<div style="margin: 0; padding: 0;">';
                    $ret .= Areas_secundarias::setPadroes($as->padrao_construcao_id) . ' ';
                    $ret .= '<b>&Aacute;rea</b> <input type="text" value="' . str_replace('.', ',', $as->area_construcao) . '" name="area_construcao_secundaria[]" size="6" onfocus="alterarM();" class="medida" /> m&sup2; &nbsp;';
                    $ret .= '<span class="actions"><button style="top: 10px;"><span class="ui-icon ui-icon-trash"></span></button></span>';
                    $ret .= '</div>';
                }
            }
        }
        return $ret;
    }

    function setPadroes($value = '') {
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/areas_secundarias');
        $tpl_select->loadTemplateFile('select.tpl.html');

        $padrao = new Padroes();
        $padrao->find();

        while ($padrao->fetch()) {
            $tpl_select->setVariable('PadraoConstrucaoSecundariaIdOp', $padrao->id);
            $tpl_select->setVariable('PadraoConstrucaoSecundariaOp', utf8_encode($padrao->descricao));
            $tpl_select->setVariable('PadraoConstrucaoSecundariaSelecionado', ($value == $padrao->id) ? 'selected' : '');
            $tpl_select->parse('block_sl_padrao_construcao_secundaria');
        }

        return $tpl_select->get();
    }

    function getAreasSecundariasToFicha($imovel_id) {
        $as = new Areas_secundarias();
        $as->imovel_id = $imovel_id;
        $ret = null;
        if ($as->find()) {
            $ret = "<span class='label'>&Aacute;reas Secund&aacute;rias:</span><br />";
            while ($as->fetch()){
                $ret .= "<b>Padr&atilde;o: </b>".Padroes::getDescricao($as->padrao_construcao_id)." <b>&Aacute;rea: </b>".$as->area_construcao." m&sup2;<br />";
            }
        }
        
        return $ret;
    }

}
