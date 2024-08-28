<?php
/**
 * Table Definition for melhoramentos
 */
require_once '../pear/DB/DataObject.php';

class Melhoramentos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'melhoramentos';       // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $descricao;                       // string(45)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Melhoramentos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/melhoramentos');

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
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/melhoramentos');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');

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
    
    function getMelhoramentos($imovel_id) {
        $melhoramentos = new Melhoramentos();
        $melhoramentos->find();
        $count = 0;
        $ret = '<ul class="options">';
        $ret2 = '';
        $todos = true;
        while($melhoramentos->fetch()) {
            if (Imoveis_melhoramentos::exist($imovel_id, $melhoramentos->id)) {
                $selected = ' checked';
            } else {
                $selected = '';
                $todos = false;
            }
            $ret2 .= '<li><input type="hidden" name="melhoramentos['.$melhoramentos->id.']" value="0">';
            $ret2 .= '<input onClick="document.getElementById(\'alterou_m\').value=1" class="melhor" type="checkbox" name="melhoramentos['.$melhoramentos->id.']" value="1"'.$selected.'><label class="option">'.$melhoramentos->descricao.'</label></li>';
        }
        $todosChecked = ($todos == true) ? ' checked' : '';
        $ret .= '<li><input onClick="document.getElementById(\'alterou_m\').value=1" type="checkbox" name="melhoramentos[]" id="selectAll" '.$todosChecked.' value="0"><label class="option"><b>Todos</b></li>';
        echo $ret.$ret2.'</tr></table>';
    }
    
    function getMelhoramentosToFicha($imovel_id) {
        $melhoramento = new Melhoramentos();
        $ret = null;
        if ($melhoramento->find()) {
            $cont = 0;
            while ($melhoramento->fetch()){
                if (Imoveis_melhoramentos::exist($imovel_id, $melhoramento->id)) {
                    $ret .= ($cont == 0) ? '<b>Melhoramentos:</b> ' : '';
                    if ($cont > 0) $ret .= ', ';
                    $ret .= $melhoramento->descricao;
                    $cont++;
                }
            }   
            $ret .= ($ret) ? '.' : null;
        }
        
        return $ret;
    }
    
    function setMelhoramentos($tpl, $imovel_id){
        $melhoramento = new Melhoramentos();
        $melhoramento->find();

        while ($melhoramento->fetch()){
            $tpl->setVariable('MelhoramentoId', $melhoramento->id);
            $tpl->setVariable('MelhoramentoOp', $melhoramento->descricao);
            $tpl->setVariable('MelhoramentoSelecionado', Imoveis_melhoramentos::exist($imovel_id, $melhoramento->id)?'checked':'');
            $tpl->parse('block_ck_melhoramentos');
        }
    }
}
