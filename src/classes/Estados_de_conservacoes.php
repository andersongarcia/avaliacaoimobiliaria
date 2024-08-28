<?php
/**
 * Table Definition for estados_de_conservacoes
 */
require_once '../pear/DB/DataObject.php';

class Estados_de_conservacoes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'estados_de_conservacoes';    // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $classificacao;                   // string(1)  
    public $descricao;                       // string(50)
    public $coeficiente_depreciacao;         // real(12)

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Estados_de_conservacoes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getDescricao($id) {
        $estado_de_conservacao = new Estados_de_conservacoes();
        $estado_de_conservacao->get($id);
        return $estado_de_conservacao->descricao;
    }
    
    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/estados_de_conservacao');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');

        $this->setVariable('JS_URL', JS_URL);

        while ($this->fetch()) {
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Classificacao' => $this->classificacao,
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
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/estados_de_conservacao');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#estadoConservacaoEmUso" ).dialog( "open" );' : '';
            $tpl->setVariable('EmUso', $emUso);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Classificacao' => $this->classificacao,
                'Descricao' => $this->descricao,
                'Coeficiente' => str_replace('.', ',', $this->coeficiente_depreciacao)
            ));

            $tpl->touchBlock('block_delete');
        }else{
            $tpl->setVariable('botao', 'enviar');
        }				

        // imprime o html resultante
        $tpl->show();
    }
    
    function getFormAjax() {
        
?>
<fieldset>
    <input type="hidden" name="classe" id="classe" value="estado_conservacao" />
    <label for="classificacao">Classificação</label>
    <input type="text" name="classificacao" id="classificacao" class="text ui-widget-content ui-corner-all" />
    <label for="descricao">Descrição</label>
    <input type="text" name="descricao" id="descricao" class="text ui-widget-content ui-corner-all" />
</fieldset>        
<?

    }
    
    function getEstadosConservacoes($value = '') {
        $estado = new Estados_de_conservacoes();
        $estado->find();
        $ret = '<select name="estados_de_conservacao_id" style="width: 195px;">';
        while($estado->fetch()) {
            $selected = ($value == $estado->id) ? ' selected' : '';
            $ret .= '<option value="'.$estado->id.'"'.$selected.'>'.$estado->descricao.' &nbsp; </option>';
        }
        echo $ret.'</select>';
    }
    
    function setEstadosConservacoes($value = ''){
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/estados_de_conservacao');
        $tpl_select->loadTemplateFile('select.tpl.html');
        
        $estado = new Estados_de_conservacoes();
        $estado->find();

        while ($estado->fetch()){
            $tpl_select->setVariable('EstadoConservacaoIdOp', $estado->id);
            $tpl_select->setVariable('EstadoConservacaoOp', $estado->descricao);
            $tpl_select->setVariable('EstadoConservacaoSelecionado', ($value == $estado->id)?'selected':'');
            $tpl_select->parse('block_sl_estados_de_conservacao');
        }
        
        return $tpl_select->get();
    }
}
