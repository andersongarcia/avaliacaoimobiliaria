<?php
/**
 * Table Definition for padroes
 */
require_once '../pear/DB/DataObject.php';

class Padroes extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'padroes';             // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $codigo;                          // string(5)  
    public $descricao;                       // string(50)  

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Padroes', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getDescricao($id) {
        $padrao = new Padroes();
        $padrao->get($id);
        return $padrao->descricao;
    }

    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/padroes');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');

        while ($this->fetch()) {
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Codigo' => $this->codigo,
                'Descricao' => $this->descricao
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function showForm() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/padroes');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#padraoEmUso" ).dialog( "open" );' : '';
            $tpl->setVariable('EmUso', $emUso);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Codigo' => $this->codigo,
                'Descricao' => $this->descricao
            ));

            $tpl->touchBlock('block_delete');
        } else {
            $tpl->setVariable('botao', 'enviar');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function getFormAjax($value) {
        ?>
        <fieldset>
            <input type="hidden" name="classe" id="classe" value="<?= $value ?>" />
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo" class="text ui-widget-content ui-corner-all" />
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="text ui-widget-content ui-corner-all" />
        </fieldset>        
        <?
    }

    function getPadroes($name, $value = '') {
        $padrao = new Padroes();
        $padrao->find();
        $ret = '<select name="' . $name . '" onChange="alterarM();">';
        while ($padrao->fetch()) {
            $selected = ($value == $padrao->id) ? ' selected' : '';
            $ret .= '<option value="' . $padrao->id . '"' . $selected . '>' . $padrao->descricao . ' &nbsp; </option>';
        }
        return $ret . '</select>';
    }

    function setPadroes($value = '') {
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/padroes');
        $tpl_select->loadTemplateFile('select.tpl.html');

        $padrao = new Padroes();
        $padrao->find();

        while ($padrao->fetch()) {
            $tpl_select->setVariable('PadraoIdOp', $padrao->id);
            $tpl_select->setVariable('PadraoOp', $padrao->descricao);
            $tpl_select->setVariable('PadraoSelecionado', ($value == $padrao->id) ? 'selected' : '');
            $tpl_select->parse('block_sl_padrao_construcao_principal');
        }

        return $tpl_select->get();
    }

}
