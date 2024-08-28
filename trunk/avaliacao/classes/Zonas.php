<?php
/**
 * Table Definition for zonas
 */
require_once '../pear/DB/DataObject.php';

class Zonas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'zonas';               // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $codigo;                          // string(5)  not_null
    public $descricao;                       // string(50)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Zonas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getDescricao($id) {
        $zona = new Zonas();
        $zona->get($id);
        return $zona->descricao;
    }

    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/zonas');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');
        
        $padrao = trim(Utils::getConfiguracaoPadrao('zona'));

        while ($this->fetch()) {
            // atribui os dados da linha
            $padrao2 = ($this->id == $padrao) ? '<img src="images/ativoS.png" />' : '<a href="zonas.php?alterarPadrao='.$this->id.'"><img src="images/ativoN.png" /></a>';
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Codigo' => $this->codigo,
                'Descricao' => $this->descricao,
                'Padrao' => $padrao2
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function showForm() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/zonas');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#zonaEmUso" ).dialog( "open" );' : '';
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
    
    function getFormAjax() {
        
?>
<fieldset>
    <input type="hidden" name="classe" id="classe" value="zona" />
    <label for="codigo">Código</label>
    <input type="text" name="codigo" id="codigo" class="text ui-widget-content ui-corner-all" />
    <label for="descricao">Descrição</label>
    <input type="text" name="descricao" id="descricao" class="text ui-widget-content ui-corner-all" />
</fieldset>        
<?

    }
    
    function getZonas($value = '') {
        $zona = new Zonas();
        $zona->orderBy("codigo");
        $zona->find();
        $ret = '<select name="zona_id">';
        while($zona->fetch()) {
            $selected = ($value == $zona->id) ? ' selected' : '';
            $ret .= '<option value="'.$zona->id.'"'.$selected.'>'.$zona->codigo.' - '.$zona->descricao.' &nbsp; </option>';
        }
        echo $ret.'</select>';
    }
    
    function setZonas($value = ''){
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/zonas');
        $tpl_select->loadTemplateFile('select.tpl.html');
        
        $zona = new Zonas();
        $zona->orderBy("codigo");
        $zona->find();
        
        $value2 = ($value == '') ? trim(Utils::getConfiguracaoPadrao('zona')) : $value;

        while ($zona->fetch()){
            $tpl_select->setVariable('ZonaIdOp', $zona->id);
            $tpl_select->setVariable('ZonaOp', $zona->codigo.' - '.$zona->descricao);
            $tpl_select->setVariable('ZonaSelecionado', ($value2 == $zona->id)?'selected':'');
            $tpl_select->parse('block_sl_zona');
        }
        
        return $tpl_select->get();
    }
    
}