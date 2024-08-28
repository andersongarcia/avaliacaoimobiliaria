<?php
/**
 * Table Definition for imoveis
 */
require_once '../pear/DB/DataObject.php';
if (!strstr($_SERVER['REQUEST_URI'], 'upload.php')) {
    require_once CLASS_DIR . '/Areas_secundarias.php';
    require_once CLASS_DIR . "/Avaliacoes.php";
    require_once CLASS_DIR . "/Imoveis.php";
    require_once CLASS_DIR . '/Municipios.php';
    require_once CLASS_DIR . '/Imovel_tipos.php';
    require_once CLASS_DIR . '/Imoveis_melhoramentos.php';
    require_once CLASS_DIR . '/Padroes.php';
    require_once CLASS_DIR . '/Zonas.php';
    require_once CLASS_DIR . '/Topografias.php';
    require_once CLASS_DIR . '/Melhoramentos.php';
    require_once CLASS_DIR . '/Imobiliarias.php';
    require_once CLASS_DIR . '/Estados_de_conservacoes.php';
    require_once CLASS_DIR . "/Usuarios.php";
    require_once CLASS_DIR . "/Utils.php";
    require_once CLASS_DIR . '/ExportarXLS_PHPExcel.php';
    require_once CLASS_DIR . "/../lib/SimpleImage.class.php";
}
/*
 */

class Imoveis extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'imoveis';             // table name
    public $id;                              // int(11)  not_null primary_key
    public $codigo_referencia;               // string(20)  
    public $imovel_tipo_id;                  // int(11)  not_null multiple_key
    public $municipio_id;                    // int(11)  not_null multiple_key
    public $logradouro;                      // string(200)  
    public $numero;                          // string(5)  
    public $complemento;                     // string(100)  
    public $nome_local;                      // string(50)  
    public $bairro;                          // string(45)  
    public $setor;                           // string(6)  
    public $quadra;                          // string(5)  
    public $zona_id;                         // int(11)  multiple_key
    public $lat;                             // real(10)  
    public $lng;                             // real(10)  
    public $indice_fiscal;                   // string(20)  
    public $indice_fiscal_ano;               // year(4)  unsigned zerofill
    public $indice_fiscal_2;                 // string(20)   
    public $indice_fiscal_2_ano;             // year(4)  unsigned zerofill
    public $modalidade;                      // string(20)  
    public $natureza;                        // string(20)  
    public $valor;                           // real(12)  
    public $data_do_evento;                  // datetime(19)  binary
    public $valor_da_transacao;              // real(12)  
    public $data_da_transacao;               // datetime(19)  binary
    public $area;                            // real(12)  
    public $area_unidade;                    // string(3)  
    public $testada;                         // real(12)  
    public $testada2;                         // real(12)
    public $regular;                         // int(1)  
    public $topografia_id;                   // int(11)  multiple_key
    public $frentes_multiplas;               // int(1)  
    public $area_construcao_principal;       // real(12)  
    public $padrao_construcao_principal_id;    // int(11)  multiple_key
    public $idade;                           // int(11)  
    public $estados_de_conservacao_id;       // int(11)  multiple_key
    public $vagas_de_garagem;                // int(11)  
    public $wcs;                             // int(11)  
    public $dormitorios;                     // int(11)  
    public $suites;                          // int(11)  
    public $armarios;                        // string(5)  
    public $reformas;                        // string(5)  
    public $andar;                           // int(11)  
    public $frente_fundos;                   // string(10)  
    public $facesol_sombra;                  // string(10)  
    public $elevadores;                      // int(11)  
    public $area_de_lazer;                   // string(5)  
    public $piscina;                         // string(5)  
    public $salao_de_festas;                 // string(5)  
    public $capacidade_de_uso;               // string(20)  
    public $situacao_circulacao;             // string(20)  
    public $fonte_de_informacao;             // string(100)  
    public $fonte_de_informacao_telefone;    // string(15)  
    public $imobiliaria_id;                  // int(11)  multiple_key
    public $imobiliaria_ref;                 // string(15)  
    public $foto_url;                        // string(20)  
    public $observacoes;                     // blob(65535)  blob

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Imoveis', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function __clone() { return $this; }

    function showAll() {
        
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');
        $tpl->loadTemplateFile('show_all.html');
        
        if ($_GET['filtro_mod'] == 1) {
            $tpl->setVariable('Checked1', ' checked');
            $this->whereAdd("modalidade = 'Venda'");
        } else if ($_GET['filtro_mod'] == 2) {
        	$tpl->setVariable('Checked2', ' checked');
            $this->whereAdd("modalidade = 'Locação'");
        } else {
            $tpl->setVariable('Checked3', ' checked');
        }

        $this->orderBy("id DESC");
        
        $aval = new Avaliacoes();
        if ($aval->find())
            while ($aval->fetch()) {
                $this->whereAdd("id <> " . $aval->imovel_id);
            }
            
        $this->find();
        
        $this->parseLista($tpl);

        $usuario = new Usuarios();
        $usuario->getLogado();

        if ($usuario->hasPermission('Cadastro'))
            $tpl->touchBlock('block_add');

        $tpl->show();
    }
    
    function parseLista($tpl, $ativos = null){
        while ($this->fetch()) {
            $modalidade = ($this->modalidade == ("Venda") ? 'V' : 'L');
            $logradouro = ($this->logradouro != null) ? $this->logradouro.', ' : '<no>';
            $numero     = ($this->numero != null) ? $this->numero : 's/ n&ordm;';
            $logradouro = ($logradouro == '<no>') ? 'S/ endereço' : $logradouro.$numero;
            if(($this->valor_da_transacao != '') && ($this->valor_da_transacao != 0)) {
                $natureza = 'V';
                $valor = number_format($this->valor_da_transacao, 2, ',', '.');
                $data = ($this->data_da_transacao) ? Utils::reTransformDate($this->data_da_transacao) : '';
            } elseif(($this->valor != '') && ($this->valor != 0)) {
                $natureza = $this->natureza == 'Oferta' ? 'O' : 'P';
                $valor = number_format($this->valor, 2, ',', '.');
                $data = ($this->data_do_evento) ? Utils::reTransformDate($this->data_do_evento) : '';
            } else {
                $natureza = '';
                $valor = '';
                $data = '';
            }
            if($this->nome_local)
                $logradouro .= '<br />'.$this->nome_local;
            
            $tpl->setVariable(array(
                'ID' => $this->id,
                'TipoImovel' => utf8_encode(Imovel_tipos::getDescricao($this->imovel_tipo_id)),
                'Modalidade' => $modalidade,
                'Logradouro' => $logradouro,
                'Bairro' => $this->bairro,
                'Municipio' => Municipios::getNome($this->municipio_id),
                'NaturezaValor' => $natureza,
                'Valor' => ($valor)?'R$ '.$valor:'',
                'Data' => $data 
            ));
            
            if($ativos){
                $tpl->setVariable('Ativo', ($ativos[$this->id])?'checked':'');
            }
            
            $tpl->parse('table_row');
        }        
    }

    function showForm($avaliacao = null) {
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');
        $tpl->loadTemplateFile('show_form.html');

        $tpl->setVariable('JS_URL', JS_URL);
        $tpl->setVariable('HOME', HOME);
        $tpl->setVariable('URL', URL);
        $id = (isset ($this->id)) ? $this->id : 0;
        $tpl->setVariable('ImovelId', $id);
        $tpl->setVariable('PostAction', $_SERVER['REQUEST_URI']);

        Imovel_tipos::setTiposImoveis($tpl, $this->imovel_tipo_id);
        $tpl->setVariable('__select_municipio__', Municipios::setMunicipios($this->municipio_id));
        $tpl->setVariable('__select_zonas__', Zonas::setZonas($this->zona_id));
        $tpl->setVariable('__select_topografias__', Topografias::setTopografias($this->topografia_id));
        $tpl->setVariable('__select_padrao_principal__', Padroes::setPadroes($this->padrao_construcao_principal_id));
        $tpl->setVariable('__select_estados_de_conservacao__', Estados_de_conservacoes::setEstadosConservacoes($this->estados_de_conservacao_id));
        $tpl->setVariable('__areas_secundarias__', Areas_secundarias::setAreasSecundarias($this->id));
        Melhoramentos::setMelhoramentos($tpl, $this->id);

        $tpl->setVariable('IndiceFiscalAno', Utils::setComboAno('indice_fiscal_ano', $this->indice_fiscal_ano));
        $tpl->setVariable('IndiceFiscal2Ano', Utils::setComboAno('indice_fiscal_2_ano', $this->indice_fiscal_2_ano));
        
        $usuario = new Usuarios();
        $usuario->getLogado();

        if (isset($this->id)) {
            $tpl->setVariable('ImovelTipoId', $this->imovel_tipo_id);

            if ($usuario->hasPermission('Cadastro')) {
                $tpl->touchBlock('block_delete');
                $tpl->setVariable('botao', 'alterar');
                $tpl->setVariable('botao2', 'alterar');
            }

            $tpl->setVariable('Logradouro', $this->logradouro);
            $tpl->setVariable('Numero', $this->numero);
            $tpl->setVariable('Complemento', $this->complemento);
            $tpl->setVariable('NomeLocal', $this->nome_local);
            $tpl->setVariable('Bairro', $this->bairro);
            $tpl->setVariable('Setor', $this->setor);
            $tpl->setVariable('Quadra', $this->quadra);
            
            if (strlen($this->lat) == 8) {
                $lati = '  '.$this->lat;
            } else if (strlen($this->lat) == 9) {
                $lati = ' '.$this->lat;
            } else {
                $lati = $this->lat;
            }
            
            if (strlen($this->lng) == 9) {
                $long = ' '.$this->lng;
            } else if (strlen($this->lng) == 8) {
                $long = '  '.$this->lng;
            } else {
                $long = $this->lng;
            }
            $tpl->setVariable('Latitude', $lati);
            $tpl->setVariable('Longitude', $long);

            $tpl->setVariable('IndiceFiscal', $this->indice_fiscal);

            $tpl->setVariable('Valor', ($this->valor > 0)?number_format($this->valor, 2, ',', '.'):'');
            $tpl->setVariable('ValorDaTransacao', ($this->valor_da_transacao > 0)?number_format($this->valor_da_transacao, 2, ',', '.'):'');
            $tpl->setVariable('DataDaTransacao', ($this->data_da_transacao) ? Utils::reTransformDate($this->data_da_transacao) : '');
            $tpl->setVariable('DataDoEvento', ($this->data_do_evento) ? Utils::reTransformDate($this->data_do_evento) : date("d/m/Y"));
            $tpl->setVariable('Idade', $this->idade);
            $area = ($this->area_unidade == 'h') ? number_format($this->area, 4, ',', '.') : number_format($this->area, 2, ',', '.');
            $tpl->setVariable('Area', $area);
            $tpl->setVariable('AreaUnidadeM', ($this->area_unidade == 'm') ? 'selected' : '');
            $tpl->setVariable('AreaUnidadeH', ($this->area_unidade == 'h') ? 'selected' : '');
            $tpl->setVariable('Testada', number_format($this->testada, 2, ',', '.'));

            $tpl->setVariable('RegularSelecionado', $this->regular ? 'checked' : '');
            $tpl->setVariable('FrentesMultiplasSelecionado', $this->frentes_multiplas ? 'checked' : '');

            $tpl->setVariable('IndiceFiscal2', $this->indice_fiscal_2);
            $tpl->setVariable('Testada2', number_format($this->testada2, 2, ',', '.'));
            
            $tpl->setVariable('Observacoes', $this->observacoes);

            switch ($this->modalidade) {
                case "Locação":
                    $tpl->setVariable('SelecionadoModalidadeLocacao', 'checked');
                    break;
                case "Venda":
                    $tpl->setVariable('SelecionadoModalidadeVenda', 'checked');
                    break;
            }

            switch ($this->natureza) {
                case "Oferta":
                    $tpl->setVariable('SelecionadoNaturezaOferta', 'checked');
                    break;
                case "Opiniao":
                    $tpl->setVariable('SelecionadoNaturezaOpiniao', 'checked');
                    break;
            }

            $tpl->setVariable('AreaConstrucaoPrincipal', str_replace('.', ',', $this->area_construcao_principal));
            
            
        } else {
            $tpl->setVariable('ImovelTipoId', '1');

            // Valores padrões
            $tpl->setVariable('SelecionadoModalidadeVenda', 'checked');
            $tpl->setVariable('SelecionadoNaturezaOferta', 'checked');
            $tpl->setVariable('RegularSelecionado', 'checked');

            $tpl->setVariable('botao', 'enviar');
                $tpl->setVariable('botao2', 'enviar');
            $tpl->hideBlock('block_delete');
        }
        
        $imovel_tipo = new Imovel_tipos();
        $imovel_tipo->get($this->imovel_tipo_id);
        
        if($imovel_tipo->vagas_de_garagem)
            $tpl->setVariable('VagasDeGaragem', $this->vagas_de_garagem);
        if($imovel_tipo->wcs)
            $tpl->setVariable('Wcs', $this->wcs);
        if($imovel_tipo->dormitorios)
            $tpl->setVariable('Dormitorios', $this->dormitorios);
        if($imovel_tipo->suites)
            $tpl->setVariable('Suites', $this->suites);
        if($imovel_tipo->armarios)
            $tpl->setVariable('Armarios'.str_replace ('á', 'a', $this->armarios), 'checked');
        if($imovel_tipo->reformas)
            $tpl->setVariable('Reformas'.str_replace ('á', 'a', $this->reformas), 'checked');
        if($imovel_tipo->andar)
            $tpl->setVariable('Andar', $this->andar);
        if($imovel_tipo->elevadores)
            $tpl->setVariable('Elevadores', $this->elevadores);
        if($imovel_tipo->frente_fundos)
            $tpl->setVariable('FrenteFundos'.$this->frente_fundos, 'checked');
        if($imovel_tipo->facesol_sombra)
            $tpl->setVariable('FacesolSombra'. str_replace(' ', '', $this->facesol_sombra), 'checked');
        if($imovel_tipo->area_de_lazer)
            $tpl->setVariable('AreaDeLazer'.str_replace ('ã', 'a', $this->area_de_lazer), 'checked');
        if($imovel_tipo->piscina)
            $tpl->setVariable('Piscina'.str_replace ('ã', 'a', $this->piscina), 'checked');
        if($imovel_tipo->salao_de_festas)
            $tpl->setVariable('SalaoDeFestas'.str_replace ('ã', 'a', $this->salao_de_festas), 'checked');
        if($imovel_tipo->capacidade_de_uso)
            $tpl->setVariable('CapacidadeDeUso', $this->capacidade_de_uso);
        if($imovel_tipo->situacao_circulacao)
            $tpl->setVariable('SituacaoCirculacao', $this->situacao_circulacao);

        if (isset($this->foto_url) && file_exists(HTTP_DIR . '/files/' . $this->foto_url))
            $tpl->setVariable('FotoUrl', $this->foto_url);
        else
            $tpl->setVariable('FotoUrl', 'semImagem.png');

        if (!$avaliacao) {
            $tpl->setVariable('ShowAll', 'imoveis');
            $tpl->setVariable('FonteDeInformacao', $this->fonte_de_informacao);
            $tpl->setVariable('FonteDeInformacaoTelefone', $this->fonte_de_informacao_telefone);
            $tpl->setVariable('__select_imobiliarias__', Imobiliarias::setImobiliarias($this->imobiliaria_id));
            $tpl->setVariable('ImobiliariaRef', $this->imobiliaria_ref);
            $tpl->touchBlock('block_dados_avaliacao');
            $tpl->touchBlock('block_dados_avaliacao2');
            $tpl->touchBlock('block_natureza');
            $tpl->hideBlock('block_referencia');
            $tpl->touchBlock('block_id');
        } else {
            $tpl->setVariable('ShowAll', 'avaliacoes');
            $tpl->setVariable('CodigoReferencia', $this->codigo_referencia);
            $tpl->touchBlock('block_referencia');
            $tpl->hideBlock('block_id');
            $tpl->hideBlock('block_natureza');
            $tpl->hideBlock('block_dados_avaliacao');
            $tpl->hideBlock('block_dados_avaliacao2');
            $tpl->hideBlock('block_fonte_de_informacao');
        }
        
        if(isset($_GET['duplicado']) && ($_GET['duplicado'] == 'ok'))
            $tpl->setVariable('Duplicado', 'openDuplicado();');
        else
            $tpl->setVariable('Duplicado', '');
        
        $hide = ($this->frentes_multiplas != 1) ? ' style="display:none;"' : '';

        $tpl->show();
        return;
    }

    function showFormAvaliacao($id = null) {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');

        $ai = new Avaliacoes_imoveis();
        $ai->avaliacao_id = $id;

        if ($ai->find()) {

            $tpl->loadTemplateFile('show_form_avaliacao.html');

            while ($ai->fetch()) {
                $imovel = new Imoveis();
                $imovel->get('id', $ai->imovel_id);

                $im = new Imobiliarias();
                $im->get('id', $imovel->imobiliaria_id);
                $foto2 = (isset($imovel->foto_url)) ? '<img class="foto" src="files/' . $imovel->foto_url . '">' : '';

                $tpl->setVariable(array(
                    'ID2' => $imovel->id,
                    'Tipo2' => Imovel_tipos::getDescricao($imovel->imovel_tipo_id),
                    'Logradouro2' => $imovel->logradouro . ", " . $imovel->numero,
                    'Bairro2' => $imovel->bairro,
                    'Municipio2' => Municipios::getNome($imovel->municipio_id),
                    'Setor2' => $imovel->setor,
                    'Quadra2' => $imovel->quadra,
                    'Valor2' => $imovel->valor,
                    'Area2' => $imovel->area,
                    'Testada2' => $imovel->testada,
                    'Fonte2' => $imovel->fonte_de_informacao,
                    'Codigo2' => '???',
                    'Contato2' => $imovel->fonte_de_informacao_telefone,
                    'Telefone2' => $im->fone,
                    'Foto2' => $foto2,
                ));
                $tpl->parse('table_row');
            }
        } else
            $tpl->loadTemplateFile('show_form_avaliacao_nenhum.html');

        $im = new Imobiliarias();
        $im->get($this->imobiliaria_id);
        $foto = (isset($this->foto_url)) ? '<img class="foto" src="files/' . $this->foto_url . '">' : '';

        $tpl->setVariable(array(
            'Avaliacao' => $id,
            'ID' => $this->id,
            'Tipo' => Imovel_tipos::getDescricao($this->imovel_tipo_id),
            'Logradouro' => $this->logradouro . ", " . $this->numero,
            'Bairro' => $this->bairro,
            'Municipio' => Municipios::getNome($this->municipio_id),
            'Setor' => $this->setor,
            'Quadra' => $this->quadra,
            'Valor' => $this->valor,
            'Area' => $this->area,
            'Testada' => $this->testada,
            'Fonte' => $this->fonte_de_informacao,
            'Codigo' => '???',
            'Contato' => $this->fonte_de_informacao_telefone,
            'Telefone' => $im->fone,
            'Foto' => $foto,
        ));

        $tpl->show();
    }

    function showFormImovel($parte, $aval) {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');
        $par = ($parte == '2') ? '2' : '';
        $tpl->loadTemplateFile('show_form_imovel' . $par . '.html');
        $flag = true;

        while ($this->fetch()) {

            $ai = new Avaliacoes_imoveis();
            $ai->imovel_id = $this->id;
            $ai->avaliacao_id = $aval;

            if ($ai->find()) {
                $selecionado = '1';
                $claseSelecionada = ' class="selecionada"';
            } else {
                $selecionado = '0';
                $claseSelecionada = '';
            }

            $tpl->setVariable(array(
                'ID' => $this->id,
                'Tipo' => Imovel_tipos::getDescricao($this->imovel_tipo_id),
                'Logradouro' => $this->logradouro . ", " . $this->numero,
                'Bairro' => $this->bairro,
                'Municipio' => Municipios::getNome($this->municipio_id),
                'Setor' => $this->setor,
                'Quadra' => $this->quadra,
                'Selecionado' => $selecionado,
                'ClasseSelecionada' => $claseSelecionada,
            ));
            $tpl->parse('table_row');
            $flag = false;
        }

        if ($flag)
            echo '<center>Nenhum imÃ³vel encontrado!!</center>';
        $tpl->show();
    }

    function showDelete($id = null) {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/avaliacoes');

        $ai = new Avaliacoes_imoveis();
        $ai->avaliacao_id = $id;

        if ($ai->find()) {

            $tpl->loadTemplateFile('show_delete.html');

            while ($ai->fetch()) {
                $imovel = new Imoveis();
                $imovel->get('id', $ai->imovel_id);

                $im = new Imobiliarias();
                $im->get('id', $imovel->imobiliaria_id);
                $foto2 = (isset($imovel->foto_url)) ? '<img class="foto" src="files/' . $imovel->foto_url . '">' : '';

                $tpl->setVariable(array(
                    'ID2' => $imovel->id,
                    'Tipo2' => Imovel_tipos::getDescricao($imovel->imovel_tipo_id),
                    'Logradouro2' => $imovel->logradouro . ", " . $imovel->numero,
                    'Bairro2' => $imovel->bairro,
                    'Municipio2' => Municipios::getNome($imovel->municipio_id),
                    'Setor2' => $imovel->setor,
                    'Quadra2' => $imovel->quadra,
                    'Valor2' => $imovel->valor,
                    'Area2' => $imovel->area,
                    'Testada2' => $imovel->testada,
                    'Fonte2' => $imovel->fonte_de_informacao,
                    'Codigo2' => '???',
                    'Telefone2' => $im->fone,
                    'Foto2' => $foto2,
                ));
                $tpl->parse('table_row');
            }
        } else
            $tpl->loadTemplateFile('show_delete_nenhum.html');

        $im = new Imobiliarias();
        $im->get($this->imobiliaria_id);
        $foto = (isset($this->foto_url)) ? '<img class="foto" src="files/' . $this->foto_url . '">' : '';

        $tpl->setVariable(array(
            'Avaliacao' => $id,
            'ID' => $this->id,
            'Tipo' => Imovel_tipos::getDescricao($this->imovel_tipo_id),
            'Logradouro' => $this->logradouro . ", " . $this->numero,
            'Bairro' => $this->bairro,
            'Municipio' => Municipios::getNome($this->municipio_id),
            'Setor' => $this->setor,
            'Quadra' => $this->quadra,
            'Valor' => $this->valor,
            'Area' => $this->area,
            'Testada' => $this->testada,
            'Fonte' => $this->fonte_de_informacao,
            'Codigo' => '???',
            'Telefone' => $im->fone,
            'Foto' => $foto,
        ));

        $tpl->show();
    }

    function montaModalidade($mod) {
        $loc = ($mod == 'Locação') ? ' checked' : '';
        $ven = ($mod == 'Venda') ? ' checked' : '';
        return '<input type="radio" name="modalidade" value="Locação"' . $loc . ' /><label class="option">Locação</label>
           <input type="radio" name="modalidade" value="Venda"' . $ven . ' /><label class="option">Venda</label>';
    }

    function montaNatureza($nat) {
        $ofe = ($nat == 'Oferta') ? ' checked' : '';
        $loc = ($nat == 'Locação') ? ' checked' : '';
        $ven = ($nat == 'Venda') ? ' checked' : '';
        return '<input type="radio" name="natureza" value="Oferta"' . $ofe . ' /><label class="option">Oferta</label>
            <input type="radio" name="natureza" value="Locação"' . $loc . ' /><label class="option">Locação</label>
           <input type="radio" name="natureza" value="Venda"' . $ven . ' /><label class="option">Venda</label>';
    }

    function setDados($dados) {
        $this->codigo_referencia = $dados['codigo_referencia'];
        $this->imovel_tipo_id = $dados['imovel_tipo_id'];
        $this->municipio_id = $dados['municipio_id'];
        $this->logradouro = $dados['logradouro'];
        $this->numero = $dados['numero'];
        $this->complemento = $dados['complemento'];
        $this->nome_local = $dados['nome_local'];
        $this->bairro = $dados['bairro'];
        $this->setor = $dados['setor'];
        $this->quadra = $dados['quadra'];
        if ($dados['zona_id'] > 0)
            $this->zona_id = $dados['zona_id'];
        $this->lat = $dados['lat'];
        $this->lng = $dados['lng'];
        $this->indice_fiscal = $dados['indice_fiscal'];
        $this->indice_fiscal_ano = $dados['indice_fiscal_ano'];
        $this->indice_fiscal_2 = $dados['indice_fiscal_2'];
        $this->indice_fiscal_2_ano = $dados['indice_fiscal_2_ano'];
        if (isset($dados['modalidade']))
            $this->modalidade = $dados['modalidade'];
        if (isset($dados['natureza']))
            $this->natureza = $dados['natureza'];
        $valor = str_replace('R$ ', '', $dados['valor']);
        $valor = str_replace('.', '', $valor);
        if ((double)$valor > 0)
            $this->valor = (float) str_replace(',', '.', $valor);
        else
            $this->valor = (float) NULL;
        $valor_da_transacao = str_replace('R$ ', '', $dados['valor_da_transacao']);
        $valor_da_transacao = str_replace('.', '', $valor_da_transacao);
        if ((double)$valor_da_transacao > 0)
            $this->valor_da_transacao = (float) str_replace(',', '.', $valor_da_transacao);
        else
            $this->valor_da_transacao = (float) NULL;
        $area = str_replace('.', '', $dados['area']);
        $this->area = (float) str_replace(',', '.', $area);
        $this->area_unidade = $dados['area_unidade'];
        $testada = str_replace('.', '', $dados['testada']);
        $this->testada = (float) str_replace(',', '.', $testada);
        $testada2 = str_replace('.', '', $dados['testada2']);
        $this->testada2 = (float) str_replace(',', '.', $testada2);
        $this->regular = $dados['regular'];
        if ($dados['topografia_id'] > 0)
            $this->topografia_id = $dados['topografia_id'];
        $this->frentes_multiplas = $dados['frentes_multiplas'];
        $this->area_construcao_principal = (float) str_replace(',', '.', $dados['area_construcao_principal']);
        if ($dados['padrao_construcao_principal_id'] > 0)
            $this->padrao_construcao_principal_id = $dados['padrao_construcao_principal_id'];
        $this->idade = $dados['idade'];
        if ($dados['estados_de_conservacao_id'] > 0)
            $this->estados_de_conservacao_id = $dados['estados_de_conservacao_id'];
        if (isset($dados['vagas_de_garagem']))
            $this->vagas_de_garagem = $dados['vagas_de_garagem'];
        if (isset($dados['wcs']))
            $this->wcs = $dados['wcs'];
        if (isset($dados['dormitorios']))
            $this->dormitorios = $dados['dormitorios'];
        if (isset($dados['suites']))
            $this->suites = $dados['suites'];
        if (isset($dados['armarios']))
            $this->armarios = $dados['armarios'];
        if (isset($dados['reformas']))
            $this->reformas = $dados['reformas'];
        if (isset($dados['andar']))
            $this->andar = $dados['andar'];
        if (isset($dados['frente_fundos']))
            $this->frente_fundos = $dados['frente_fundos'];
        if (isset($dados['facesol_sombra']))
            $this->facesol_sombra = $dados['facesol_sombra'];
        if (isset($dados['elevadores']))
            $this->elevadores = $dados['elevadores'];
        if (isset($dados['area_de_lazer']))
            $this->area_de_lazer = $dados['area_de_lazer'];
        if (isset($dados['piscina']))
            $this->piscina = $dados['piscina'];
        if (isset($dados['salao_de_festas']))
            $this->salao_de_festas = $dados['salao_de_festas'];
        if (isset($dados['capacidade_de_uso']))
            $this->capacidade_de_uso = $dados['capacidade_de_uso'];
        if (isset($dados['situacao_circulacao']))
            $this->situacao_circulacao = $dados['situacao_circulacao'];
        $this->fonte_de_informacao = $dados['fonte_de_informacao'];
        $this->fonte_de_informacao_telefone = $dados['fonte_de_informacao_telefone'];
        if ($dados['imobiliaria_id'] > 0)
            $this->imobiliaria_id = $dados['imobiliaria_id'];
        $this->imobiliaria_ref = $dados['imobiliaria_ref'];
        $this->data_do_evento = Utils::transformDate($dados['data_do_evento']);
        $this->data_da_transacao = Utils::transformDate($dados['data_da_transacao']);
        $this->observacoes = $dados['observacoes'];
        $this->foto_url = $dados['foto_url'];
    }

    function setDadosRelacionados($dados) {
        $im = new Imoveis_melhoramentos();
        $as = new Areas_secundarias();

        if ($dados['alterou_m'] > 0) {
            $im->imovel_id = $this->id;
            $im->delete();
            foreach ($dados['melhoramentos'] as $key => $value) {

                if (isset($value)) {
                    $im->imovel_id = $this->id;
                    $im->melhoramento_id = $value;
                    $im->insert();
                }
            }
        }

        $as->imovel_id = $this->id;
        $as->delete();
        $i = 0;
        if (isset($dados['padrao_construcao_secundaria_id'])) {
            foreach ($dados['padrao_construcao_secundaria_id'] as $key => $padrao_id) {
                if (isset($padrao_id)) {
                    $j = 0;
                    foreach ($dados['area_construcao_secundaria'] as $key2 => $area) {
                        if (isset($area)) {
                            if ($i == $j) {
                                $as->imovel_id = $this->id;
                                $as->area_construcao = (float) str_replace(',', '.', $area);
                                $as->padrao_construcao_id = $padrao_id;
                                $as->insert();
                            }
                        }
                        $j++;
                    }
                }
                $i++;
            }
        }
    }
    
    function cloneDadosRelacionados($imovel){
        $im = new Imoveis_melhoramentos();
        $im->imovel_id = $imovel->id;
        $im->find();
        
        while ($im->fetch()){
            $im->imovel_id = $this->id;
            $im->insert();
        }
        
        $as = new Areas_secundarias();
        $as->imovel_id = $imovel->id;
        $as->find();
        
        while($as->fetch()){
            $as->imovel_id = $this->id;
            $as->insert();
        }
    }

    function getLista() {

        $this->orderBy("id DESC");
        
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');
        $tpl->loadTemplateFile('lista.tpl.html');

        $aval = new Avaliacoes();
        if ($aval->find())
            while ($aval->fetch()) {
                $this->whereAdd("id <> " . $aval->imovel_id);
            }
            
        $this->find();

        while ($this->fetch()) {
            $tpl->setVariable(array(
                'ID' => $this->id,
                'TipoImovel' => Imovel_tipos::getDescricao($this->imovel_tipo_id),
                'Logradouro' => $this->logradouro,
                'Bairro' => $this->bairro,
                'Municipio' => Municipios::getNome($this->municipio_id),
                'Setor' => $this->setor,
                'Quadra' => $this->quadra
            ));
            $tpl->parse('table_row');
        }

        return $tpl->get();
    }
    
    function getFicha($contador) {
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/imoveis');
        $tpl->loadTemplateFile('ficha.tpl.html');
        
        $tipo = new Imovel_tipos();
        $tipo->get($this->imovel_tipo_id);
        
        if(($this->valor_da_transacao != '') && ($this->valor_da_transacao != 0)) {
            $natureza = 'Venda';
            $valor = number_format($this->valor_da_transacao, 2, ',', '.');
            $data = ($this->data_da_transacao) ? Utils::reTransformDate($this->data_da_transacao) : '';
        } elseif(($this->valor != '') && ($this->valor != 0)) {
            $natureza = 'Oferta';
            $valor = number_format($this->valor, 2, ',', '.');
            $data = ($this->data_do_evento) ? Utils::reTransformDate($this->data_do_evento) : '';
        } else {
            $natureza = '';
            $valor = '';
            $data = '';
        }
        
        // atribui os dados do imóvel
        if (isset($this->foto_url) && file_exists(HTTP_DIR . '/files/' . $this->foto_url)){
            $image = new SimpleImage();
            $image->load(HTTP_DIR . '/files/' . $this->foto_url);
            $image->resizeToWidth(300);
            $foto = HTTP_DIR . '/files/report/' . $this->foto_url;
            $image->save($foto);

            $tpl->setVariable('Foto', '<img class="foto" src="'.HOME.'/http/files/report/' . $this->foto_url . '" />');
        }
        else
            $tpl->setVariable('Foto', '<img class="foto" src="'.HOME.'/http/files/semImagem.png" />');

        if (isset($this->id)) {
            if (strlen($this->lat) == 8) {
                $lati = '  '.$this->lat;
            } else if (strlen($this->lat) == 9) {
                $lati = ' '.$this->lat;
            } else {
                $lati = $this->lat;
            }
            
            if (strlen($this->lng) == 9) {
                $long = ' '.$this->lng;
            } else if (strlen($this->lng) == 8) {
                $long = '  '.$this->lng;
            } else {
                $long = $this->lng;
            }

        $tpl->setVariable(array(
            'Elemento' => $contador . ' (Ficha '.$this->id.')',
            'Tipo' => $tipo->descricao?'- '.$tipo->descricao:'',
            'IndiceFiscal' => $this->indice_fiscal,
            'Valor' => $valor,
            'Data' => $data,
            'Local' => $this->logradouro . (($this->numero) ? ', ' . $this->numero : ''),
            'Bairro' => $this->bairro,
            'Municipio' => Municipios::getNome($this->municipio_id),
            'Zona' => Zonas::getDescricao($this->zona_id),
            'Regular' => $this->regular?'Sim':'Não',
            'FrentesMultiplas' => $this->frentes_multiplas?'Sim':'Não',
            'Topografia' => Topografias::getDescricao($this->topografia_id),
            'PadraoPrincipal' => Padroes::getDescricao($this->padrao_construcao_principal_id),
            'EstadoDeConservacao' => Estados_de_conservacoes::getDescricao($this->estados_de_conservacao_id),
            'IndiceFiscalAno' => $this->indice_fiscal_ano,
            'NaturezaValor' => $natureza,
            'Idade' => $idade
        ));
        
        if($this->setor || $this->quadra){
            // Setor
            if($this->setor){
                $tpl->setVariable('Setor', '<b>Setor:</b> ' . $this->setor);
                $tpl->touchBlock('block_setor');
            }  else {
                $tpl->hideBlock('block_setor');
            }
            
            // Quadra
            if($this->quadra){
                $tpl->setVariable('Quadra', '<b>Quadra:</b> ' . $this->quadra);
                $tpl->touchBlock('block_quadra');
            }  else {
                $tpl->hideBlock('block_quadra');
            }
        }  else {
            $tpl->hideBlock('block_setor_quadra');
        }
        
        if(isset($lati) && ((int)$lati != 0))
            $tpl->setVariable('Latitude', $lati);
        if(isset($long) && ((int)$long != 0))
            $tpl->setVariable('Longitude', $long);
        
        if ($this->complemento) $tpl->setVariable('Complemento', '<b>Complemento:</b> '.$this->complemento.'<br />');
        if ($this->nome_local) $tpl->setVariable('NomeLocal', '<b>Nome do Edifício/Condomínio:</b> '.$this->nome_local.'<br />');
        $tpl->setVariable('Idade', $this->idade);
        $area = ($this->area_unidade == 'h') ? number_format($this->area, 4, ',', '.') : number_format($this->area, 2, ',', '.');
        $tpl->setVariable('Area', $area);
        $tpl->setVariable('AreaUnidadeM', ($this->area_unidade == 'm') ? 'selected' : '');
        $tpl->setVariable('AreaUnidadeH', ($this->area_unidade == 'h') ? 'selected' : '');
        $tpl->setVariable('Testada', number_format($this->testada, 2, ',', '.'));

        if($this->frentes_multiplas){
            $tpl->setVariable('IndiceFiscal2', '<span class="label">Indice Fiscal 2ª Frente:</span> ' . $this->indice_fiscal_2);
            $tpl->setVariable('Testada2', '<span class="label">Testada 2ª Frente:</span> ' . number_format($this->testada2, 2, ',', '.'));
        }
            
        if ($this->observacoes) $tpl->setVariable('Observacoes', '<h3>Observações</h3> '.$this->observacoes);

        $tpl->setVariable('AreaConstrucaoPrincipal', str_replace('.', ',', $this->area_construcao_principal));   
        }
        $melhoramentos = Melhoramentos::getMelhoramentosToFicha($this->id);
        $tpl->setVariable('Melhoramentos', (($melhoramentos) ? $melhoramentos : ''));
        
        /** Para fazer
         * $tpl->setVariable('__areas_secundarias__', Areas_secundarias::setAreasSecundarias($this->id));
         */
        $areasSecundarias = Areas_secundarias::getAreasSecundariasToFicha($this->id);
        $tpl->setVariable('AreasSecundarias', (($areasSecundarias) ? $areasSecundarias : ''));
        
        $imovel_tipo = new Imovel_tipos();
        $imovel_tipo->get($this->imovel_tipo_id);
        
        if($imovel_tipo->forma != 'Terreno'){
            $tpl->touchBlock('block_dados_construcao');
            if($imovel_tipo->vagas_de_garagem)
                $tpl->setVariable('VagasDeGaragem', '<span class="label">Vagas de Garagem:</span> ' . $this->vagas_de_garagem);
            if($imovel_tipo->wcs)
                $tpl->setVariable('Wcs', '<span class="label">Wcs:</span> ' . $this->wcs);
            if($imovel_tipo->dormitorios)
                $tpl->setVariable('Dormitorios', '<span class="label">Dormitórios:</span> ' . $this->dormitorios);
            if($imovel_tipo->suites)
                $tpl->setVariable('Suites', '<span class="label">Suítes:</span> ' . $this->suites);
            if($imovel_tipo->armarios)
                $tpl->setVariable('Armarios', '<span class="label">Armários:</span> ' . $this->armarios);
            if($imovel_tipo->reformas)
                $tpl->setVariable('Reformas', '<span class="label">Reformas:</span> ' . $this->reformas);
            if($imovel_tipo->andar)
                $tpl->setVariable('Andar', '<span class="label">Andar:</span> ' . $this->andar);
            if($imovel_tipo->elevadores)
                $tpl->setVariable('Elevadores', '<span class="label">Elevadores:</span> ' . $this->elevadores);
            if($imovel_tipo->frente_fundos)
                $tpl->setVariable('FrenteFundos', '<span class="label">Frente/Fundos:</span> ' . $this->frente_fundos);
            if($imovel_tipo->facesol_sombra)
                $tpl->setVariable('FrenteFundos', '<span class="label">Face Sol/Sombra:</span> ' . $this->facesol_sombra);
            if($imovel_tipo->area_de_lazer)
                $tpl->setVariable('AreaDeLazer', '<span class="label">Área de Lazer:</span> ' . $this->area_de_lazer);
            if($imovel_tipo->piscina)
                $tpl->setVariable('Piscina', '<span class="label">Piscina:</span> ' . $this->piscina);
            if($imovel_tipo->salao_de_festas)
                $tpl->setVariable('SalaoDeFestas', '<span class="label">Salão de Festas:</span> ' . $this->salao_de_festas);
            if($imovel_tipo->capacidade_de_uso)
                $tpl->setVariable('CapacidadeDeUso', '<span class="label">Classe de Capacidade de Uso:</span> ' . $this->capacidade_de_uso);
            if($imovel_tipo->situacao_circulacao)
                $tpl->setVariable('SituacaoCirculacao', '<span class="label">Classe de Situação e Viabilidade de Circulação:</span> ' . $this->situacao_circulacao);
        }        
        
        $imobiliaria = new Imobiliarias();
        $imobiliaria->get($this->imobiliaria_id);
        $tpl->setVariable(array(
            'Informante' => $this->fonte_de_informacao,
            'Telefone' => ($this->fonte_de_informacao_telefone!='')?$this->fonte_de_informacao_telefone:$imobiliaria->telefone,
            'Imobiliaria' => $imobiliaria->id?'<span class="label">Imobiliária:</span> ' . $imobiliaria->nome:'',
            'ImobiliariaRef' => $this->imobiliaria_ref?'<span class="label">Cod. Referência:</span> ' . $this->imobiliaria_ref:''
        ));

        return $tpl->get();
    }
    
    function ExportarXLS($filename){
        $dados = array();
        $linha = 2;
              
        $labels = array();
        $labels['id'] = 'ID';
        $labels['codigo_referencia'] = 'Cod. Referência';
        $labels['imovel_tipo_id'] = 'Tipo';
        $labels['municipio_id'] = 'Município';
        $labels['logradouro'] = 'Logradouro';
        $labels['numero'] = 'Número';
        $labels['complemento'] = 'Complemento';
        $labels['nome_local'] = 'Nome do Edifício/Condomínio';
        $labels['bairro'] = 'Bairro';
        $labels['setor'] = 'Setor';
        $labels['quadra'] = 'Quadra';
        $labels['zona_id'] = 'Zona';
        $labels['lat'] = 'Latitude';
        $labels['lng'] = 'Longitude';
        $labels['indice_fiscal'] = 'Índice Fiscal';
        $labels['indice_fiscal_2'] = 'Índice Fiscal 2ª Frente';
        $labels['modalidade'] = 'Modalidade';
        $labels['natureza'] = 'Natureza';
        $labels['valor'] = 'Valor de Oferta/Opinião';
        $labels['data_do_evento'] = 'Data de Oferta/Opinião';
        $labels['valor_da_transacao'] = 'Valor de Transação';
        $labels['data_da_transacao'] = 'Data de Transação';
        $labels['area'] = 'Área';
        $labels['testada'] = 'Testada';
        $labels['testada2'] = 'Testada 2ª Frente';
        $labels['regular'] = 'Regular';
        $labels['topografia_id'] = 'Topografia';
        $labels['melhoramentos'] = 'Melhoramentos';
        $labels['frentes_multiplas'] = 'Frentes Múltiplas';
        $labels['area_construcao_principal'] = 'Área de Construção Principal';
        $labels['padrao_construcao_principal_codigo'] = 'Padrão de Construção Principal - Código';
        $labels['padrao_construcao_principal_descricao'] = 'Padrão de Construção Principal - Descrição';
        $labels['idade'] = 'Idade';
        $labels['estados_de_conservacao_id'] = 'Estado de Conservação';
        $labels['vagas_de_garagem'] = 'Vagas de Garagem';
        $labels['wcs'] = 'WCs';
        $labels['dormitorios'] = 'Dormitórios';
        $labels['suites'] = 'Suítes';
        $labels['armarios'] = 'Armários';
        $labels['reformas'] = 'Reformas';
        $labels['andar'] = 'Andar';
        $labels['frente_fundos'] = 'Frente/Fundos';
        $labels['facesol_sombra'] = 'Face Sol/Sombra';
        $labels['elevadores'] = 'Elevadores';
        $labels['area_de_lazer'] = 'Área de Lazer';
        $labels['piscina'] = 'Piscina';
        $labels['salao_de_festas'] = 'Salão de Festas';
        $labels['capacidade_de_uso'] = 'Classe de Capacidade de Uso';
        $labels['situacao_circulacao'] = 'Classe de Situação e Viabilidade de Circulação';
        $labels['fonte_de_informacao'] = 'Fonte de Informação';
        $labels['fonte_de_informacao_telefone'] = 'Telefone';
        $labels['imobiliaria_id'] = 'Imobiliária';
        $labels['imobiliaria_ref'] = 'Cod. Ref. Imobiliária';
        $labels['observacoes'] = 'Observações';
        
        foreach ($labels as $campo => $label)
            $dados[1][$campo] = $labels[$campo];

        while ($this->fetch()){
            $dados[$linha]['id'] = $this->id;
            $dados[$linha]['codigo_referencia'] = $this->codigo_referencia;
            $dados[$linha]['imovel_tipo_id'] = Imovel_tipos::getDescricao($this->imovel_tipo_id);
            $dados[$linha]['municipio_id'] = Municipios::getNome($this->municipio_id);
            $dados[$linha]['logradouro'] = $this->logradouro;
            $dados[$linha]['numero'] = $this->numero;
            $dados[$linha]['complemento'] = $this->complemento; 
            $dados[$linha]['nome_local'] = $this->nome_local;
            $dados[$linha]['bairro'] = $this->bairro;
            $dados[$linha]['setor'] = $this->setor;
            $dados[$linha]['quadra'] = $this->quadra;
            $dados[$linha]['zona_id'] = Zonas::getDescricao($this->zona_id);
            $dados[$linha]['lat'] = str_replace(".", ",", $this->lat);
            $dados[$linha]['lng'] = str_replace(".", ",", $this->lng);
            $dados[$linha]['indice_fiscal'] = $this->indice_fiscal . '/' . $this->indice_fiscal_ano;
            $dados[$linha]['indice_fiscal_2'] = $this->indice_fiscal_2 . '/' . $this->indice_fiscal_2_ano;
            $dados[$linha]['modalidade'] = $this->modalidade;
            $dados[$linha]['natureza'] = $this->natureza;
            $dados[$linha]['valor'] = number_format($this->valor, 2, ',', '');
            $dados[$linha]['data_do_evento'] = ($this->data_do_evento) ? Utils::reTransformDate($this->data_do_evento) : date("d/m/Y");
            $dados[$linha]['valor_da_transacao'] = number_format($this->valor_da_transacao, 2, ',', '');
            $dados[$linha]['data_da_transacao'] = ($this->data_da_transacao) ? Utils::reTransformDate($this->data_da_transacao) : date("d/m/Y");
            $dados[$linha]['area'] = (($this->area_unidade == 'h') ? number_format($this->area, 4, ',', '.') : number_format($this->area, 2, ',', '.'));
            $dados[$linha]['testada'] = number_format($this->testada, 2, ',', '.');
            $dados[$linha]['testada2'] = number_format($this->testada2, 2, ',', '.');
            $dados[$linha]['regular'] = $this->regular?'Sim':'Não';
            $dados[$linha]['topografia_id'] = Topografias::getDescricao($this->topografia_id);
            
            $melhoramento = new Melhoramentos();
            $melhoramento->find();
            $melhoramentos = array();
            while ($melhoramento->fetch()){
                if(Imoveis_melhoramentos::exist($this->id, $melhoramento->id))
                    array_push($melhoramentos, $melhoramento->descricao);
            }
            $dados[$linha]['melhoramentos'] = implode(', ', $melhoramentos);
            
            $dados[$linha]['frentes_multiplas'] = $this->frentes_multiplas?'Sim':'Não';
            
            $dados[$linha]['area_construcao_principal'] = str_replace('.', ',', $this->area_construcao_principal);
            
            $padrao = new Padroes();
            $padrao->get($this->padrao_construcao_principal_id);
            $dados[$linha]['padrao_construcao_principal_codigo'] = $padrao->codigo;
            $dados[$linha]['padrao_construcao_principal_descricao'] = $padrao->descricao;

            $dados[$linha]['idade'] = $this->idade;
            $dados[$linha]['estados_de_conservacao_id'] = Estados_de_conservacoes::getDescricao($this->estados_de_conservacao_id);
            $dados[$linha]['vagas_de_garagem'] = $this->vagas_de_garagem;
            $dados[$linha]['wcs'] = $this->wcs;
            $dados[$linha]['dormitorios'] = $this->dormitorios;
            $dados[$linha]['suites'] = $this->suites;
            $dados[$linha]['armarios'] = $this->armarios?'Sim':'Não';
            $dados[$linha]['reformas'] = $this->reformas?'Sim':'Não';
            $dados[$linha]['andar'] = $this->andar;
            $dados[$linha]['frente_fundos'] = $this->frente_fundos;
            $dados[$linha]['facesol_sombra'] = $this->facesol_sombra;
            $dados[$linha]['elevadores'] = $this->elevadores?'Sim':'Não';
            $dados[$linha]['area_de_lazer'] = $this->area_de_lazer?'Sim':'Não';
            $dados[$linha]['piscina'] = $this->piscina?'Sim':'Não';
            $dados[$linha]['salao_de_festas'] = $this->salao_de_festas?'Sim':'Não';
            $dados[$linha]['capacidade_de_uso'] = $this->capacidade_de_uso;
            $dados[$linha]['situacao_circulacao'] = $this->situacao_circulacao;
            $dados[$linha]['fonte_de_informacao'] = $this->fonte_de_informacao;
            
            $imobiliaria = new Imobiliarias();
            $imobiliaria->get($this->imobiliaria_id);
            
            $dados[$linha]['fonte_de_informacao_telefone'] = 
                $this->fonte_de_informacao_telefone?$this->fonte_de_informacao_telefone:$imobiliaria->fone;
            $dados[$linha]['imobiliaria_id'] = $imobiliaria->nome;
            $dados[$linha]['imobiliaria_ref'] = $this->imobiliaria_ref;
            $dados[$linha]['observacoes'] = $this->observacoes;

            
            $as = new Areas_secundarias();
            $as->imovel_id = $this->id;
            $totalAreasSecundarias = $as->count();
            $contadorAreasSecundarias = 0;
            if ($as->find()) {
                
                while ($as->fetch()) {
                    $contadorAreasSecundarias++;
                    $dados[1]['area_construcao_secundaria_' . $contadorAreasSecundarias] = 'Área de Construção Secundária';
                    $dados[1]['padrao_construcao_secundaria_' . $contadorAreasSecundarias] = 'Padrão de Área de Construção Secundária';
                    $dados[$linha]['area_construcao_secundaria_' . $contadorAreasSecundarias] = str_replace('.', ',', $as->area_construcao);

                    $padrao = new Padroes();
                    $padrao->get($as->padrao_construcao_id);
                    $dados[$linha]['padrao_construcao_secundaria_codigo_' . $contadorAreasSecundarias] = $padrao->codigo;
                    $dados[$linha]['padrao_construcao_secundaria_descricao_' . $contadorAreasSecundarias] = $padrao->descricao;
                }
            }

            $linha++;
        }     
        
        $xls = new ExportarXLS_PHPExcel();
        $xls->GerarXLS($dados, $filename);
    }
}
