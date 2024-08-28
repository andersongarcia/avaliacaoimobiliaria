<?php

/**
 * Table Definition for imobiliarias
 */
require_once '../pear/DB/DataObject.php';

class Imobiliarias extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'imobiliarias';        // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $nome;                            // string(50)  not_null
    public $endereco;                        // string(200)  
    public $fone;                            // string(15)  

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Imobiliarias', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imobiliarias');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.html');

        while ($this->fetch()) {
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Nome' => $this->nome,
                'Endereco' => $this->endereco,
                'Fone' => $this->fone,
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function showForm() {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imobiliarias');
        $tpl->loadTemplateFile('show_form.html');

        $botao = ($this->id) ? 'alterar' : 'enviar';
        ($this->id) ? $tpl->touchBlock("BLOCK_EXCLUIR") : $tpl->hideBlock("BLOCK_EXCLUIR");
        
        $emUso = ($_GET['msg'] == 'emUso') ? '$( "#imobiliariaEmUso" ).dialog( "open" );' : '';
        $tpl->setVariable('EmUso', $emUso);

        $tpl->setVariable(array(
            'ID' => $this->id,
            'Nome' => $this->nome,
            'Endereco' => $this->endereco,
            'Fone' => $this->fone,
            'Botao' => $botao,
            'Uri' => $_SERVER['REQUEST_URI'],
        ));

        $tpl->show();
    }

    function getFormAjax() {
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imobiliarias');
        $tpl->loadTemplateFile('getFormAjax.html');
        $tpl->show();
    }

    function getImobiliarias($value = '') {
        $imobiliaria = new Imobiliarias();
        $imobiliaria->find();
        $ret = '<select name="imobiliaria_id">';
        while ($imobiliaria->fetch()) {
            $selected = ($value == $imobiliaria->id) ? ' selected' : '';
            $ret .= '<option value="' . $imobiliaria->id . '"' . $selected . '>' . $imobiliaria->nome . ' &nbsp; </option>';
        }
        echo $ret . '</select>';
    }

    function setImobiliarias($value = '') {
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/imobiliarias');
        $tpl_select->loadTemplateFile('select.tpl.html');

        $imobiliaria = new Imobiliarias();
        $imobiliaria->find();

        while ($imobiliaria->fetch()) {
            $tpl_select->setVariable('ImobiliariaIdOp', $imobiliaria->id);
            $tpl_select->setVariable('ImobiliariaOp', $imobiliaria->nome);
            $tpl_select->setVariable('ImobiliariaSelecionado', ($value == $imobiliaria->id) ? 'selected' : '');
            $tpl_select->parse('block_sl_imobiliaria');
        }

        return $tpl_select->get();
    }

}