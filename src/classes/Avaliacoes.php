<?php

/**
 * Table Definition for avaliacoes
 */
require_once '../pear/DB/DataObject.php';
require_once CLASS_DIR . "/Imoveis.php";
require_once CLASS_DIR . "/Imovel_tipos.php";
require_once CLASS_DIR . "/Avaliacoes_imoveis.php";
require_once CLASS_DIR . "/Avaliacoes_filtros.php";
require_once CLASS_DIR . "/Utils.php";

class Avaliacoes extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'avaliacoes';          // table name
    public $id;                              // int(11)  not_null primary_key
    public $imovel_id;                       // int(11)  not_null multiple_key
    public $data_avaliacao;                  // datetime(19)  binary

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Avaliacoes', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $campos = array(
        'codigo_referencia' => array('label' => 'Cod. Refer&ecirc;ncia', 'template' => 'texto'),
        'municipio_id' => array('label' => 'Munic&iacute;pio', 'template' => 'select'),
        'imovel_tipo_id' => array('label' => 'Tipo de Im&oacute;vel', 'template' => 'select'),
        'logradouro' => array('label' => 'Logradouro', 'template' => 'texto'),
        //'complemento' => array('label' => 'Complemento', 'template' => 'texto'),
        'bairro' => array('label' => 'Bairro', 'template' => 'texto'),
        //'indice_fiscal_ano' => array('label' => 'Ano', 'template' => 'intervalo_anos'),
        'nome_local' => array('label' => 'Nome do Edif&iacute;cio/Condomínio', 'template' => 'texto'),
        'zona_id' => array('label' => 'Zona', 'template' => 'select'),
        'modalidade' => array('label' => 'Modalidade', 'template' => 'modalidade'),
        //'natureza' => array('label' => 'Natureza', 'template' => 'natureza'),
        'data_do_evento' => array('label' => 'Data', 'template' => 'intervalo_datas'),
        //'data_da_transacao' => array('label' => 'Data da transa&ccedil;&atilde;o', 'template' => 'intervalo_datas'),
        'valor' => array('label' => 'Valor', 'template' => 'intervalo_valores_monetarios'),
        'area' => array('label' => '&Aacute;rea', 'template' => 'intervalo_valores'),
        //'testada' => array('label' => 'Testada', 'template' => 'intervalo_valores'),
        //'testada2' => array('label' => 'Testada 2Âª Frente', 'template' => 'intervalo_valores'),
        //'regular' => array('label' => 'Regular', 'template' => 'flag'),
        //'frentes_multiplas' => array('label' => 'Frentes MÃºltiplas', 'template' => 'flag')
    );
    
    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.html');
        $exite = false;

        while ($this->fetch()) {
            $imovel = new Imoveis();
            $imovel->get($this->imovel_id);
            $tipo = new Imovel_tipos();
            $tipo->get($imovel->imovel_tipo_id);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'ImovelId' => $this->imovel_id,
                'CodRef' => $imovel->codigo_referencia,
                'Tipo' => utf8_encode($tipo->descricao),
                'Local' => $imovel->logradouro . (($imovel->numero) ? ', ' . $imovel->numero : ''),
                'Bairro' => $imovel->bairro,
                'DataAvaliacao' => Utils::reTransformDate($this->data_avaliacao),
                'Alterar' => '<a class="alterar" href="avaliacoes.php?new=1&imovel_id=' . $this->imovel_id . '" title="Alterar Im&oacute;vel"><span class="ui-icon ui-icon-pencil"></span></a>',
                'Excluir' => '<a class="excluir" href="' . $this->id . '" title="Excluir"><span class="ui-icon ui-icon-trash"></span></a>',
            ));
            // processa o bloco
            $tpl->parse('table_row');
            $exite = true;
        }

        $usuario = new Usuarios();
        $usuario->getLogado();
        if ($usuario->hasPermission('Cadastro'))
            $tpl->touchBlock('block_add');

        // imprime o html resultante
        $tpl->show();
        if (!$exite)
            echo '<center>Nenhuma avaliação encontrada!</center>';
    }

    function showForm() {
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');
        $tpl->loadTemplateFile('show_form.tpl.html');

        $tpl->setVariable('JS_URL', JS_URL);
        $tpl->setVariable('HOME', HOME);
        $tpl->setVariable('URL', URL);
        $tpl->setVariable('TEMPLATE_DIR', TEMPLATE_DIR);
        $id = (isset($this->id)) ? $this->id : 0;
            
        $avaliando = new Imoveis();
        $avaliando->get($this->imovel_id);

        $tpl->setVariable('AvaliacaoId', $id);
        $tpl->setVariable('CodRef', $avaliando->codigo_referencia);

        $usuario = new Usuarios();
        $usuario->getLogado();

        if (isset($this->id)) {
            $tpl->setVariable('ImovelId', $this->imovel_id);

            foreach ($this->campos as $campo => $campoAtrib) {
                $tpl->setVariable('NomeCampo', $campo);
                $tpl->setVariable('LabelCampo', $campoAtrib['label']);
                $tpl->parse('block_campo_filtro');
            }

            if ($usuario->hasPermission('Cadastro')) {
                $tpl->touchBlock('block_delete');
                $tpl->setVariable('botao', 'alterar');
            }
            
            $tpl->setVariable('ListaImoveisSelecionados', $this->showSelecionados());
            
        } else {
            $tpl->setVariable('botao', 'enviar');
            $tpl->hideBlock('block_delete');
        }
        
        $tpl->setVariable('ListaImoveis', $this->showElementosDePesquisa());

        $tpl->show();
        return;
    }

    function showLastest() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_lastest.tpl.html');

        $this->limit(5);
        $this->orderBy('data_avaliacao DESC');
        $this->find();
        while ($this->fetch()) {
            $imovel = new Imoveis();
            $imovel->get($this->imovel_id);
            $tipo = new Imovel_tipos();
            $tipo->get($imovel->imovel_tipo_id);

            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'ImovelId' => $this->imovel_id,
                'CodRef' => $imovel->codigo_referencia,
                'Tipo' => $tipo->descricao,
                'Local' => $imovel->logradouro . (($imovel->numero) ? ', ' . $imovel->numero : ''),
                'Bairro' => $imovel->bairro,
                'DataAvaliacao' => Utils::reTransformDate($this->data_avaliacao)
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        return $tpl->get();
    }
    
    function showReport($formato = 'html') {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');

        $this->find();
        if ($this->fetch()) {
            // carrega o arquivo de template
            $tpl->loadTemplateFile('show_report.tpl.html');

            $tpl->setVariable('CSS_URL', CSS_URL);
            $tpl->setVariable('IMAGE_URL', IMAGE_URL);
            
            $avaliando = new Imoveis();
            $avaliando->get($this->imovel_id);

            $tpl->setVariable('CodRef', $avaliando->codigo_referencia);
            
            $imovel = new Imoveis();
            $ai = new Avaliacoes_imoveis();
            $imovel->query("SELECT {$imovel->__table}.* FROM {$imovel->__table} " .
                " INNER JOIN {$ai->__table} ON {$ai->__table}.imovel_id = {$imovel->__table}.id " .
                "   AND {$ai->__table}.avaliacao_id = {$this->id} AND selecionado = 1");
                
            $filename = $avaliando->codigo_referencia?$avaliando->codigo_referencia:'avaliacao_'.$this->id;
            if($formato == 'excel'){
                $imovel->ExportarXLS($filename);
                return;
            }else{
                $contador = 1;
                while ($imovel->fetch()){
                    // processa o bloco
                    $tpl->setVariable('DadosImovel', $imovel->getFicha($contador));

                    if(($contador % 4) == 0) {
                        $tpl->touchBlock('block_break');
                    }
                    $contador++;

                    $tpl->parse('imoveis_selecionados');

                    if($formato == 'word'){
                        header("Content-type: application/vnd.ms-word");
                        header("Content-Disposition: attachment;Filename={$filename}.doc");
                    }
                }
            }
        }else{
            // carrega o arquivo de template
            $tpl->loadTemplateFile('show_report_nenhum.tpl.html');
        }

        // imprime o html resultante
        return $tpl->show();
    }

    function getFiltros() {
        $filtros = new Avaliacoes_filtros();
        $filtros->avaliacao_id = $this->id;
        $filtros->find();

        while ($filtros->fetch()) {
            $filtros->setFiltro();
        }

        return $filtros;
    }

    function showFiltroAtivo() {
        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes/filtro');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('filtro_ativo.tpl.html');
        $tpl->setVariable('URL', URL);
        $tpl->setVariable('ImovelId', $this->imovel_id);
        
        $filtros = $this->getFiltros();

        foreach ($filtros->filtro as $chave => $filtro) {
            foreach ($filtro as $valor) {
                $filtroValor = $valor;
                switch ($chave) {
                    case 'imovel_tipo_id':
                        $tipo = new Imovel_tipos();
                        $tipo->get($valor);
                        $valor = utf8_encode($tipo->descricao);
                        break;
                    case 'municipio_id':
                        $municipio = new Municipios();
                        $municipio->get($valor);
                        $valor = utf8_encode($municipio->nome);
                        break;
                    case 'zona_id':
                        $zona = new Zonas();
                        $zona->get($valor);
                        $valor = $zona->descricao;
                        break;
                    case 'indice_fiscal_ano':
                    case 'area':
                    case 'testada':
                    case 'testada2':
                    case 'data_do_evento':
                    case 'data_da_transacao':
                        $valores = explode(';', $valor);
                        $valor = '';
                                
                        if (!($valores[0] == 0))
                            $valor = 'de ' . $valores[0] . ' ';
                        if ($valores[1])
                            $valor .= 'até ' . $valores[1];
                        break;
                    case 'valor':
                        $valores = explode(';', $valor);
                        $valor = '';
                        foreach ($valores as $i => $v)
                            $valores[$i] = 'R$ '.$v;
                                
                        if (!($valores[0] == ''))
                            $valor = 'de ' . $valores[0] . ' ';
                        if ($valores[1])
                            $valor .= 'até ' . $valores[1];
                        break;
                }
                $tpl->setVariable('Chave', $this->campos[$chave]['label']);
                $tpl->setVariable('Valor', $valor);
                $tpl->setVariable('FiltroChave', $chave);
                $tpl->setVariable('FiltroValor', $filtroValor);
                $tpl->parse('filtro_ativo');
            }
        }

        $tpl->show();
    }

    function showElementosDePesquisa() {
        $imovel = new Imoveis();
        
        if(!$this->id)
            return false;
        
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');
        $tpl->loadTemplateFile('elementos_de_pesquisa.tpl.html');

        $tpl->setVariable('AvaliacaoId', $this->id);
        $tpl->setVariable('URL', URL);

        $ai = new Avaliacoes_imoveis();
        $ai->avaliacao_id = $this->id;
        $ai->find();
        
        while ($ai->fetch()){
            $imovel->whereAdd("id <> " . $ai->imovel_id);
        }
        
        $filtros = $this->getFiltros();

        foreach ($filtros->filtro as $campo => $valor) {
            $imovel->whereAdd($filtros->getFiltro($campo));
        }

        $aval = new Avaliacoes();
        if ($aval->find())
            while ($aval->fetch()) {
                $imovel->whereAdd("id <> " . $aval->imovel_id);
            }

        $imovel->orderBy("id DESC");
        
        $imovel->find();
        
        $imovel->parseLista($tpl);

        return $tpl->get();
    }

    function showSelecionados() {
        $imovel = new Imoveis();
        $selecionados = array();

        $aval = new Avaliacoes_imoveis();
        $aval->avaliacao_id = $this->id;
        $aval->find();
        
        $imovel->whereAdd('1<>1');
        while ($aval->fetch()){
            $imovel->whereAdd("id = " . $aval->imovel_id, 'OR');
            $selecionados[$aval->imovel_id] = $aval->selecionado;
        }

        $aval = new Avaliacoes();
        if ($aval->find())
            while ($aval->fetch()) {
                $imovel->whereAdd("id <> " . $aval->imovel_id);
            }

        $imovel->orderBy("id DESC");
            
        $imovel->find();
        
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');
        $tpl->loadTemplateFile('imoveis_selecionados.tpl.html');

        $tpl->setVariable('AvaliacaoId', $this->id);
        $tpl->setVariable('URL', URL);
        
        $imovel->parseLista($tpl, $selecionados);

        return $tpl->get();
    }

    function getCampoFiltro($campo) {
        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes/filtro');
        // carrega o arquivo de template
        $tpl->loadTemplateFile($this->campos[$campo]['template'] . '.tpl.html');
        switch ($campo) {
            case 'imovel_tipo_id':
                $tipos = new Imovel_tipos();
                $tipos->find();
                while ($tipos->fetch()) {
                    $tpl->setVariable('ItemIdOp', $tipos->id);
                    $tpl->setVariable('ItemOp', utf8_encode($tipos->descricao));
                    $tpl->parse('block_item');
                }
                break;
                
            case 'municipio_id':
                $municipios = new Municipios();
                $municipios->find();
                while ($municipios->fetch()) {
                    $tpl->setVariable('ItemIdOp', $municipios->id);
                    $tpl->setVariable('ItemOp', utf8_encode($municipios->nome));
                    $tpl->parse('block_item');
                }
                break;
                
            case 'zona_id':
                $zonas = new Zonas();
                $zonas->find();
                while ($zonas->fetch()) {
                    $tpl->setVariable('ItemIdOp', $zonas->id);
                    $tpl->setVariable('ItemOp', $zonas->descricao);
                    $tpl->parse('block_item');
                }
                break;

            case 'indice_fiscal_ano':
                $tpl->setVariable('AnoInicio', Utils::setComboAno('filtro_valor[]'));
                $tpl->setVariable('AnoFim', Utils::setComboAno('filtro_valor[]'));
                break;

        }
        return $tpl->show();
    }

}