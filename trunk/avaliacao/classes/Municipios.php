<?php
/**
 * Table Definition for municipios
 */
require_once '../pear/DB/DataObject.php';

class Municipios extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'municipios';          // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $nome;                            // string(100)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Municipios',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getNome($id){
        $municipio = new Municipios();
        $municipio->get($id);
        return $municipio->nome;
    }
    
    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/municipios');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');
        
        $padrao = trim(Utils::getConfiguracaoPadrao('municipio'));

        while ($this->fetch()) {
            // atribui os dados da linha
            $padrao2 = ($this->id == $padrao) ? '<img src="images/ativoS.png" />' : '<a href="municipios.php?alterarPadrao='.$this->id.'"><img src="images/ativoN.png" /></a>';
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Nome' => $this->nome,
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
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/municipios');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#municipioEmUso" ).dialog( "open" );' : '';
            $tpl->setVariable('EmUso', $emUso);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Nome' => $this->nome
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
    <input type="hidden" name="classe" id="classe" value="municipio" />
    <label for="nome">Nome</label>
    <input type="text" name="nome" id="nome" class="text ui-widget-content ui-corner-all" />
</fieldset>        
<?

    }
    
    function getMunicipios($value = '') {
        $municipio = new Municipios();
        $municipio->find();
        $ret = '<select name="municipio_id">';
        while($municipio->fetch()) {
            $selected = ($value == $municipio->id) ? ' selected' : '';
            $ret .= '<option value="'.$municipio->id.'"'.$selected.'>'.$municipio->nome.' &nbsp; </option>';
        }
        echo $ret.'</select>';
    }
    
    function getMunicipios2($value = '') {
        $municipio = new Municipios();
        $municipio->find();
        $ret = '<select name="municipios" id="municipios"><option>Selecione...</option>';
        while($municipio->fetch()) {
            $selected = ($value == $municipio->id) ? ' selected' : '';
            $ret .= '<option value="'.$municipio->id.'"'.$selected.'>'.$municipio->nome.' &nbsp; </option>';
        }
        $ret.= '</select>';
        return $ret;
    }
    
    function setMunicipios($value = ''){
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/municipios');
        $tpl_select->loadTemplateFile('select.tpl.html');
        
        $municipio = new Municipios();
        $municipio->find();
        
        $value2 = ($value == '') ? trim(Utils::getConfiguracaoPadrao('municipio')) : $value;

        while ($municipio->fetch()){
            $tpl_select->setVariable('MunicipioIdOp', $municipio->id);
            $tpl_select->setVariable('MunicipioOp', $municipio->nome);
            $tpl_select->setVariable('MunicipioSelecionado', ($value2 == $municipio->id)?'selected':'');
            $tpl_select->parse('block_sl_municipio');
        }

        return $tpl_select->get();
    }
}
