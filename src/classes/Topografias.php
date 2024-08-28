<?php
/**
 * Table Definition for topografias
 */
require_once '../pear/DB/DataObject.php';

class Topografias extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'topografias';         // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $descricao;                       // string(50)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Topografias',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getDescricao($id) {
        $topografia = new Topografias();
        $topografia->get($id);
        return $topografia->descricao;
    }
    
    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/topografias');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');

        while ($this->fetch()) {
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
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
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/topografias');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#topografiaEmUso" ).dialog( "open" );' : '';
            $tpl->setVariable('EmUso', $emUso);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Descricao' => $this->descricao
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
    <input type="hidden" name="classe" id="classe" value="topografia" />
    <label for="descricao">Descrição</label>
    <input type="text" name="descricao" id="descricao" class="text ui-widget-content ui-corner-all" />
</fieldset>        
<?

    }
    
    function getTopografias($value = '') {
        $topo = new Topografias();
        $topo->find();
        $ret = '<select name="topografia_id">';
        while($topo->fetch()) {
            $selected = ($value == $topo->id) ? ' selected' : '';
            $ret .= '<option value="'.$topo->id.'"'.$selected.'>'.$topo->descricao.' &nbsp; </option>';
        }
        echo $ret.'</select>';
    }
    
    function setTopografias($value = ''){
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/topografias');
        $tpl_select->loadTemplateFile('select.tpl.html');
        
        $topografia = new Topografias();
        $topografia->find();

        while ($topografia->fetch()){
            $tpl_select->setVariable('TopografiaIdOp', $topografia->id);
            $tpl_select->setVariable('TopografiaOp', $topografia->descricao);
            $tpl_select->setVariable('TopografiaSelecionado', ($value == $topografia->id)?'selected':'');
            $tpl_select->parse('block_sl_topografia');
        }
        
        return $tpl_select->get();
     }
}
