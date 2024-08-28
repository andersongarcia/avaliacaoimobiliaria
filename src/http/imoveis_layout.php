<?php
include_once("../includes/config.php");
require_once CLASS_DIR."/Areas_secundarias.php";
require_once CLASS_DIR."/Avaliacoes.php";
require_once CLASS_DIR."/Imoveis.php";
require_once CLASS_DIR.'/Municipios.php';
require_once CLASS_DIR.'/Imovel_tipos.php';
require_once CLASS_DIR.'/Imoveis_melhoramentos.php';
require_once CLASS_DIR.'/Padroes.php';
require_once CLASS_DIR.'/Zonas.php';
require_once CLASS_DIR.'/Topografias.php';
require_once CLASS_DIR.'/Melhoramentos.php';
require_once CLASS_DIR.'/Imobiliarias.php';
require_once CLASS_DIR.'/Estados_de_conservacoes.php';

$zona = new Imoveis();
$im = new Imoveis_melhoramentos();

if (isset($_POST['enviar'])) {
    $zona->imovel_tipo_id                  = $_POST['imovel_tipo_id'];
    $zona->municipio_id                    = $_POST['municipio_id'];
    $zona->logradouro                      = $_POST['logradouro'];
    $zona->numero                          = $_POST['numero'];
    $zona->complemento                     = $_POST['complemento'];
    $zona->bairro                          = $_POST['bairro'];
    $zona->setor                           = $_POST['setor'];
    $zona->quadra                          = $_POST['quadra'];
    $zona->zona_id                         = $_POST['zona_id'];
    $zona->lat                             = $_POST['lat'];
    $zona->lng                             = $_POST['lng'];
    $zona->indice_fiscal                   = $_POST['indice_fiscal'];
    $zona->indice_fiscal_ano               = $_POST['indice_fiscal_ano'];
    $zona->indice_fiscal_2                 = $_POST['indice_fiscal_2'];
    $zona->indice_fiscal_2_ano             = $_POST['indice_fiscal_2_ano'];
    $zona->modalidade                      = $_POST['modalidade'];
    $zona->natureza                        = $_POST['natureza'];
    $zona->valor                           = $_POST['valor'];
    $zona->area                            = $_POST['area'];
    $zona->testada                         = $_POST['testada'];
    $zona->testada2                        = $_POST['testada2'];
    $zona->regular                         = $_POST['regular'];
    $zona->topografia_id                   = $_POST['topografia_id'];
    $zona->frentes_multiplas               = $_POST['frentes_multiplas'];
    $zona->area_construcao_principal       = $_POST['area_construcao_principal'];
    $zona->padrao_construcao_principal_id  = $_POST['padrao_construcao_principal_id'];
    $zona->area_construcao_secundaria      = $_POST['area_construcao_secundaria'];
    $zona->padrao_construcao_secundaria_id = $_POST['padrao_construcao_secundaria_id'];
    $zona->idade                           = $_POST['idade'];
    $zona->estados_de_conservacao_id       = $_POST['estados_de_conservacao_id'];
    $zona->vagas_de_garagem                = $_POST['vagas_de_garagem'];
    $zona->wcs                             = $_POST['wcs'];
    $zona->dormitorios                     = $_POST['dormitorios'];
    $zona->suites                          = $_POST['suites'];
    $zona->fonte_de_informacao             = $_POST['fonte_de_informacao'];
    $zona->imobiliaria_id                  = $_POST['imobiliaria_id'];
    $zona->data_do_evento                  = Utils::transformDate($_POST['data_do_evento']);
    $zona->observacoes                     = $_POST['observacoes'];
    $id = $zona->insert();
    if ($id) {
        
        foreach ($_POST['melhoramentos'] as $key => $value) {
            
            if ($value) {
                $im->imovel_id = $id;
                $im->melhoramento_id = $key;
                $im->insert();
            }
        }
        header("Location: imoveis.php?msg=25");
    }
} else if (isset($_POST['alterar'])) {
    $zona->get($_GET['id']);
    $zona->imovel_tipo_id                  = $_POST['imovel_tipo_id'];
    $zona->municipio_id                    = $_POST['municipio_id'];
    $zona->logradouro                      = $_POST['logradouro'];
    $zona->numero                          = $_POST['numero'];
    $zona->complemento                     = $_POST['complemento'];
    $zona->bairro                          = $_POST['bairro'];
    $zona->setor                           = $_POST['setor'];
    $zona->quadra                          = $_POST['quadra'];
    $zona->zona_id                         = $_POST['zona_id'];
    $zona->lat                             = $_POST['lat'];
    $zona->lng                             = $_POST['lng'];
    $zona->indice_fiscal                   = $_POST['indice_fiscal'];
    $zona->indice_fiscal_ano               = $_POST['indice_fiscal_ano'];
    $zona->indice_fiscal_2                 = $_POST['indice_fiscal_2'];
    $zona->indice_fiscal_2_ano             = $_POST['indice_fiscal_2_ano'];
    $zona->modalidade                      = $_POST['modalidade'];
    $zona->natureza                        = $_POST['natureza'];
    $zona->valor                           = $_POST['valor'];
    $zona->area                            = $_POST['area'];
    $zona->testada                         = $_POST['testada'];
    $zona->testada2                        = $_POST['testada2'];
    $zona->regular                         = $_POST['regular'];
    $zona->topografia_id                   = $_POST['topografia_id'];
    $zona->frentes_multiplas               = $_POST['frentes_multiplas'];
    $zona->area_construcao_principal       = $_POST['area_construcao_principal'];
    $zona->padrao_construcao_principal_id  = $_POST['padrao_construcao_principal_id'];
    $zona->area_construcao_secundaria      = $_POST['area_construcao_secundaria'];
    $zona->padrao_construcao_secundaria_id = $_POST['padrao_construcao_secundaria_id'];
    $zona->idade                           = $_POST['idade'];
    $zona->estados_de_conservacao_id       = $_POST['estados_de_conservacao_id'];
    $zona->vagas_de_garagem                = $_POST['vagas_de_garagem'];
    $zona->wcs                             = $_POST['wcs'];
    $zona->dormitorios                     = $_POST['dormitorios'];
    $zona->suites                          = $_POST['suites'];
    $zona->fonte_de_informacao             = $_POST['fonte_de_informacao'];
    $zona->imobiliaria_id                  = $_POST['imobiliaria_id'];
    $zona->data_do_evento                  = Utils::transformDate($_POST['data_do_evento']);
    $zona->observacoes                     = $_POST['observacoes'];
    if (($zona->update()) || ($_POST['alterou_m'])) {
        
        $im->imovel_id = $_GET['id'];
        $im->delete();
        foreach ($_POST['melhoramentos'] as $key => $value) {
            
            if ($value) {
                $im->imovel_id = $zona->id;
                $im->melhoramento_id = $key;
                $im->insert();
            }
        }
        header("Location: imoveis.php?msg=26");
        
    }
} else if (isset($_GET['excluir'])) {
    $zona->get($_GET['id']);
    if ($zona->delete())
        header("Location: imoveis.php?msg=27");
}

if (isset($_GET['id']))
    $zona->get($_GET['id']);
else
    $zona->find();

include_once INCLUDE_DIR.'/header.php';

?>
<div class="container_12 newForm" style="text-align: left;">
    <div class="grid_4">
        <img src="files/semImagem.png" width="100%" style="border: 1px solid black; margin-bottom: 5px;" />
        <label for="valor" style="display: inline;">Valor: </label>
        <input type="text" name="valor" value="{Valor}" size="34" />

    </div>
    <div class="grid_8">
        <label for="imovel_tipo_id">Tipo de Imóvel</label>
        <select name="imovel_tipo_id"><option value="1">Terreno &nbsp; </option><option value="2">Barracão Industrial &nbsp; </option><option value="5">Casa &nbsp; </option><option value="6">Apartamento &nbsp; </option><option value="7">teste3 &nbsp; </option><option value="11">Teste &nbsp; </option></select>
        Ver Detalhes
        <br />
        <div>
        <label for="municipio">Município</label>
        <select name="municipio_id"><option value="1">São Carlos &nbsp; </option><option value="2">Araraquara &nbsp; </option><option value="3">Ibaté &nbsp; </option><option value="4">Ribeirão Preto &nbsp; </option><option value="5">Rio Claro &nbsp; </option></select>
        <a href="javascript:;" id="new_insert" onclick="carregar('municipio')">+</a> &nbsp;&nbsp;
        </div>
        <label for="logradouro">Logradouro:</label>
        <input type="text" value="{Logradouro}" name="logradouro" size="50"> <b>N&ordm;</b> <input type="text" size="3" name="numero" value="{Número}">
        <div>
        <label for="complemento">Complemento:</label>
        <input type="text" value="{Complemento}" name="complemento" size="40"> &nbsp;&nbsp;
        </div>
        <label for="bairro">Bairro</label>
        <input type="text" value="{Bairro}" name="bairro" size="40">
        <div>
        <label for="setor">Setor</label>
        <input type="text" value="{Setor}" name="setor" size="15"> &nbsp;&nbsp;
        </div><div>
        <label for="setor">Quadra</label>
        <input type="text" value="{Quadra}" name="quadra" size="15"> &nbsp;&nbsp;
        </div><div>
        <label for="bairro">Zona</label>
        <select name="zona_id"><option value="1">ZMp - São Paulo &nbsp; </option><option value="2">ZAc - Goiás &nbsp; </option><option value="3" selected="">ZAr - Araraquara &nbsp; </option><option value="4">007 - James Bond &nbsp; </option></select>
        <a href="javascript:;" id="new_insert" onclick="carregar('zona')">+</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div>
        <label for="latitude">Latitude</label>
        <input type="text" value="{Latitude}" name="latitude" size="15" maxlength="10"> &nbsp;&nbsp;
        </div><div>
        <label for="longitude">Longitude</label>
        <input type="text" value="{Longitude}" name="longitude" size="15" maxlength="10"> &nbsp;&nbsp;
        </div><div>
        <label for="indice_fiscal">Índice Fiscal</label>
        <input type="text" name="indice_fiscal" value="{IndiceFiscal}" size="25" />
        <b>Ano</b>
        <input type="text" value="{IndiceFiscalAno}" name="indice_fiscal_ano" size="5">
        </div><div>
        <label for="modalidade">Modalidade</label>
        <input type="radio" name="modalidade" value="Locação" />&nbsp;Locação&nbsp;&nbsp;&nbsp; <input type="radio" name="modalidade" value="Venda" checked /> Venda &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div><div>
        <label for="natureza">Natureza</label>
        <input type="radio" name="natureza" value="Oferta" />&nbsp;Oferta&nbsp;&nbsp;&nbsp; <input type="radio" name="natureza" value="Locação" checked />&nbsp;Locação&nbsp;&nbsp;&nbsp; <input type="radio" name="natureza" value="Venda" />&nbsp;Venda
        </div>
    </div>

<hr />
    <div class="container_12 newForm" style="text-align: left;">
        <div class="grid_4">
            <div>
            <label for="area">Área:</label>
            <input type="text" value="{Area}" name="area" size="13"> m² &nbsp;
            </div><div>
            <label for="testada">Testada:</label>
            <input type="text" value="{Testada}" name="testada" size="13"> m 
            </div>
            <div class="clear">&nbsp;</div>
            <div>
            <input type="checkbox" name="frentes_multiplas" id="frentes_multiplas" value="1" />
            <label for="frentes_multiplas" class="inline">Frentes Múltiplas</label> &nbsp;&nbsp;
            </div><div>
            <input type="checkbox" name="regular" value="1" {RegularSelecionado} />
            <label for="regular" class="inline">Regular</label> &nbsp;&nbsp;&nbsp;
            </div><div id="FMHide" style="display: none;">
            <label for="testada2">Testada:</label>
            <input type="text" value="{Testada2}" name="testada2" size="11"> m &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div><div id="FMHide2" style="display: none;">
            <label for="indice_fiscal2">Índice Fiscal</label>
            <input type="text" name="indice_fiscal_2" value="{IndiceFiscal2}" size="20" /> &nbsp;
            <b>Ano</b>
            <input type="text" value="{IndiceFiscalAno2}" name="indice_fiscal_2_ano" size="5">
            </div>
        </div>
        <div class="grid_3">
            <div>
            <label for="testada">Topografia:</label>
            <select name="topografia_id"><option value="1">Declive > 20% &nbsp; </option><option value="2" selected>Declive de 10 a 20% &nbsp; </option><option value="4">Declive de 5 a 10% &nbsp; </option><option value="5">Plano &nbsp; </option><option value="6">Aclive < 10% &nbsp; </option><option value="7">Aclive de 10 a 20% &nbsp; </option><option value="8">Aclive > 20% &nbsp; </option></select></spam> &nbsp; <a href="javascript:;" id="new_insert" onclick="carregar('topografia')">+</a>
            </div><div>
            <label for="idade">Idade:</label>
            <input type="text" value="{Idade}" name="idade" size="27">
            </div><div>
            <label for="estados_de_conservacao_id">Estado de Conservação:</label>
            <select name="estados_de_conservacao_id" style="width: 195px;"><option value="1">N.A. &nbsp; </option><option value="2" selected>Nova &nbsp; </option><option value="5">Entre novo e regular &nbsp; </option><option value="8">Regular &nbsp; </option><option value="9">Entre regular e reparos simples &nbsp; </option><option value="10">Reparos simples &nbsp; </option><option value="11">Entre reparos simples e importantes &nbsp; </option><option value="12">Reparos importantes &nbsp; </option><option value="13">Entre reparos importantes e sem valor &nbsp; </option><option value="14">Sem valor &nbsp; </option></select></spam> &nbsp; <a href="javascript:;" id="new_insert" onclick="carregar('estado_conservacao')">+</a>
            </div>
        </div>
        <div class="grid_5">
            <label for="padrao_construcao_principal_id">Padrão de Construção Principal:</label>
            <select name="padrao_construcao_principal_id"><option value="1">inexistente &nbsp; </option><option value="2">Barracão Rústico &nbsp; </option><option value="3">Barracão Simples &nbsp; </option><option value="4">Galpão/Salão Econômico &nbsp; </option><option value="5" selected="">Galpão Médio &nbsp; </option><option value="6">Galpão Simpes &nbsp; </option><option value="7">Residencial Econômico &nbsp; </option><option value="8">Residencial Fino &nbsp; </option><option value="9">Residencial Luxo &nbsp; </option><option value="10">Residencial Médio &nbsp; </option><option value="11">Residencial Proletário &nbsp; </option><option value="12">Residencial Rústico &nbsp; </option><option value="13">Residencial Simples &nbsp; </option><option value="14">Residencial Superior &nbsp; </option></select>
            <b>Área</b> <input type="text" value="{AreaConstrucaoPrincipal}" name="area_construcao_principal" size="10"> m²
            <label for="melhoramentos[]">Melhoramentos</label>
            <input type="hidden" name="alterou_m" id="alterou_m" value="0"><table cellspacing="0" cellpadding="0" border="0"><tr><td><input onClick="document.getElementById('alterou_m').value=1" type="checkbox" name="melhoramentos[]" id="selectAll"  checked value="0"></td><td><b>Todos</b> &nbsp; </td><td><input type="hidden" name="melhoramentos[1]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[1]" value="1" checked></td><td>Luz</td><td><input type="hidden" name="melhoramentos[2]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[2]" value="1" checked></td><td>Água</td><td><input type="hidden" name="melhoramentos[3]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[3]" value="1" checked></td><td>Esgoto</td><td><input type="hidden" name="melhoramentos[4]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[4]" value="1" checked></td><td>Telefone</td><td><input type="hidden" name="melhoramentos[5]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[5]" value="1" checked></td><td>Iluminação</td></tr><tr><td><input type="hidden" name="melhoramentos[6]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[6]" value="1" checked></td><td>Pavimentação</td><td><input type="hidden" name="melhoramentos[7]" value="0"><input onClick="document.getElementById('alterou_m').value=1" class="melhor" type="checkbox" name="melhoramentos[7]" value="1" checked></td><td>Coleta de Lixo</td><td>*</td><td>*</td><td>*</td><td>*</td><td>*</td><td>*</td><td>*</td><td>*</td></tr></table>
        </div>
    </div>
    
    <hr />
    <div class="container_12 newForm" style="text-align: left;">
        <div class="grid_12">
            <div>
            <label for="data">Data:</label>
            <input type="text" value="{Data}" name="data" size="27"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div><div>
            <label for="fonte_de_informacao">Fonte de Informação:</label>
            <input type="text" value="{FonteDeInformacao}" name="fonte_de_infomacao" size="27"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div><div>
            <label for="fone_fonte_de_informacao">Telefone:</label>
            <input type="text" value="{FoneFonteDeInformacao}" name="fone_fonte_de_infomacao" size="27"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </div><div>
            <label for="imobiliaria_id">Imobiliária:</label>
            <select name="imobiliaria_id"><option value="1" selected>Cardinali &nbsp; </option><option value="2">Padrão Imóveis &nbsp; </option><option value="3">Valor &nbsp; </option></select></spam> &nbsp; <a href="javascript:;" id="new_insert" onclick="carregar('imobiliaria')">+</a>
            </div>
        </div>
    </div>    

    <hr />
    <div class="container_12 newForm" style="text-align: left;">
        <div class="grid_12">
            <label for="observacoes">Observações</label>
            <textarea rows="3" cols="100"></textarea>
        </div>
    </div>    
</div>

<br /><hr />
<?

include_once INCLUDE_DIR.'/footer.php';
?>