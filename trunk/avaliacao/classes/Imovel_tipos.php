<?php
/**
 * Table Definition for imovel_tipos
 */
require_once '../pear/DB/DataObject.php';

class Imovel_tipos extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'imovel_tipos';        // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $descricao;                       // string(50)  not_null
    public $forma;                           // string(50)  
    public $vagas_de_garagem;                // int(1)  
    public $wcs;                             // int(1)  
    public $dormitorios;                     // int(1)  
    public $suites;                          // int(1)  
    public $armarios;                        // int(1)  
    public $reformas;                        // int(1)  
    public $andar;                           // int(1)  
    public $frente_fundos;                   // int(1)  
    public $facesol_sombra;                  // int(1)  
    public $elevadores;                      // int(1)  
    public $area_de_lazer;                   // int(1)  
    public $piscina;                         // int(1)  
    public $salao_de_festas;                 // int(1)  
    public $capacidade_de_uso;               // int(1)
    public $situacao_circulacao;              // int(1)

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Imovel_tipos', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getDescricao($id) {
        $imovel = new Imovel_tipos();
        $imovel->get($id);
        return $imovel->descricao;
    }

    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imovel_tipos');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.tpl.html');

        while ($this->fetch()) {
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Descricao' => $this->descricao,
                'Forma' => $this->forma
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function showForm() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imovel_tipos');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_form.tpl.html');

        if ($this->id) {
            $tpl->setVariable('botao', 'alterar');
            $emUso = ($_GET['msg'] == 'emUso') ? '$( "#tipoEmUso" ).dialog( "open" );' : '';
            $tpl->setVariable('EmUso', $emUso);
            
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Descricao' => $this->descricao,
                'VagasDeGaragemSelecionado' => $this->vagas_de_garagem ? 'checked' : '',
                'WcsSelecionado' => $this->wcs ? 'checked' : '',
                'DormitoriosSelecionado' => $this->dormitorios ? 'checked' : '',
                'SuitesSelecionado' => $this->suites ? 'checked' : '',
                'ArmariosSelecionado' => $this->armarios ? 'checked' : '',
                'ReformasSelecionado' => $this->reformas ? 'checked' : '',
                'AndarSelecionado' => $this->andar ? 'checked' : '',
                'FrenteFundosSelecionado' => $this->frente_fundos ? 'checked' : '',
                'FacesolSombraSelecionado' => $this->facesol_sombra ? 'checked' : '',
                'ElevadoresSelecionado' => $this->elevadores ? 'checked' : '',
                'AreaDeLazerSelecionado' => $this->area_de_lazer ? 'checked' : '',
                'PiscinaSelecionado' => $this->piscina ? 'checked' : '',
                'SalaoDeFestasSelecionado' => $this->salao_de_festas ? 'checked' : '',
                'CapacidadeDeUsoSelecionado' => $this->capacidade_de_uso ? 'checked' : '',
                'SituacaoCirculacaoSelecionado' => $this->situacao_circulacao ? 'checked' : ''
            ));
            switch ($this->forma) {
                case 'Terreno':
                    $tpl->setVariable('FormaTerrenoSelecionado', 'checked');
                    break;
                case 'Terreno Edificado':
                    $tpl->setVariable('FormaTerrenoEdificadoSelecionado', 'checked');
                    break;
                case 'ImÃ³vel em CondomÃ­nio':
                    $tpl->setVariable('FormaImovelEmCondominio', 'checked');
                    break;
                case 'ImÃ³vel Rural':
                    $tpl->setVariable('FormaImovelRural', 'checked');
                    break;
            }

            $tpl->touchBlock('block_delete');
        }else{
            $tpl->setVariable('botao', 'enviar');
            
            // valores padrÃ£o
            $tpl->setVariable('FormaTerrenoEdificadoSelecionado', 'checked');
            $tpl->setVariable(array(
                'VagasDeGaragemSelecionado' => 'checked',
                'WcsSelecionado' => 'checked',
                'DormitoriosSelecionado' => 'checked',
                'SuitesSelecionado' => 'checked',
                'ArmariosSelecionado' => 'checked',
                'ReformasSelecionado' => 'checked'
            ));
        }

        // imprime o html resultante
        $tpl->show();
    }

    function getFormAjax() {
        ?>
        <fieldset>
            <input type="hidden" name="classe" id="classe" value="imovel_tipo" />
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="text ui-widget-content ui-corner-all" />
            <label for="forma">Forma</label>
            <input type="text" name="forma" id="forma" value="" class="text ui-widget-content ui-corner-all" />
            <label for="vagas_de_garagem">Vagas de garagem</label>
            <input type="checkbox" onclick="changeValue('vagas_de_garagem')" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="vagas_de_garagem" id="vagas_de_garagem" value="0">
            <label for="wcs">Wcs</label>
            <input type="checkbox" onclick="changeValue('wcs')" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="wcs" id="wcs" value="0">
            <label for="wcs">Dormitórios</label>
            <input type="checkbox" onclick="changeValue('dormitorios')" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="dormitorios" id="dormitorios" value="0">
            <label for="suites">Suítes</label>
            <input type="checkbox" onclick="changeValue('suites')" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="suites" id="suites" value="0">
        </fieldset>        
        <?php
    }

    function getTiposImoveisDetalhes() {
        
    }

    function getTiposImoveis($value = '') {
        $imovel = new Imovel_tipos();
        $imovel->find();
        $ret = '<select name="imovel_tipo_id">';
        while ($imovel->fetch()) {
            $selected = ($value == $imovel->id) ? ' selected' : '';
            $ret .= '<option value="' . $imovel->id . '"' . $selected . '>' . $imovel->descricao . ' &nbsp; </option>';
        }
        echo $ret . '</select>';
    }

    function getTiposImoveis2($value = '') {
        $imovel = new Imovel_tipos();
        $imovel->find();
        $ret = '<select name="imovel_tipo" id="imovel_tipo"><option>Selecione...</option>';
        while ($imovel->fetch()) {
            $selected = ($value == $imovel->id) ? ' selected' : '';
            $ret .= '<option value="' . $imovel->id . '"' . $selected . '>' . $imovel->descricao . ' &nbsp; </option>';
        }
        $ret.= '</select>';
        return $ret;
    }

    function setTiposImoveis($tpl, $value = '') {
        $imovel = new Imovel_tipos();
        $imovel->find();

        while ($imovel->fetch()) {
            $tpl->setVariable('ImovelTipoIdOp', $imovel->id);
            $tpl->setVariable('ImovelTipoOp', $imovel->descricao);
            $tpl->setVariable('ImovelTipoSelecionado', ($value == $imovel->id) ? 'selected' : '');
            $tpl->parse('block_sl_imovel_tipo');
        }
    }

}
?>