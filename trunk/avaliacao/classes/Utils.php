<?php

//include_once(SITE_DIR."/includes/arrays.php");
//include_once("Date.php");


class Utils {

    function Utils() {
        
    }

    function getData($chave, $campo, $valor = array(), $modo = '') {

        global $$chave;

        $tipo = ${$chave}["tipo"];

        if (!$campo) {
            return ${$chave}[$valor[0]];
        }

        switch ($tipo) {
            case "radio" :
                for ($i = 1; $i < sizeof(${$chave}); $i++) {
                    $checked = (in_array($i, $valor)) ? " checked" : "";

                    $opts .= "<input type='radio' name='$campo' value='$i' $checked $modo>&nbsp;" . ${$chave}[$i] . "&nbsp;&nbsp;\n";
                    if (sizeof(${$chave}) > 4) {
                        $opts.="<br>";
                    }
                }
                break;
            case "checkbox" :
                /*  	     
                  for($i = 1; $i< sizeof(${$chave}); $i++) {
                  $checked = (in_array($i, $valor)) ? " checked": "";
                  $opts .= "<input type='checkbox' name='{$campo}[]' value='$i' $checked $modo>&nbsp;".${$chave}[$i]."&nbsp;&nbsp;\n";

                  }
                 */
                foreach (${$chave} as $value => $exibir) {
                    if ($value == "tipo")
                        break;

                    $checked = (in_array($value, $valor)) ? " checked" : "";
                    $opts .= "<input type='checkbox' name='{$campo}[]' value='$value' $checked $modo>&nbsp;" . ${$chave}[$value] . "&nbsp;&nbsp;\n";
                }
                break;
            case "select" :
                $opts = "<option value='0'>Escolha</option>\n";
                for ($i = 1; $i < sizeof(${$chave}); $i++) {
                    $selected = (in_array($i, $valor)) ? " selected" : "";
                    $opts .="<option value='$i' $selected>" . ${$chave}[$i] . "</option>\n";
                }
                $opts = "<select name='$campo' $modo>$opts</select>";
                break;
        } //switch
        return $opts;
    }

    function chechCheckBox($valor, $campo) {
        
    }

    function mostraDado($chave, $valor) {
        global $$chave;
        if (${$chave}["tipo"] == "checkbox") {
            $opcoes = explode(',', $valor);
            for ($i = 0; $i < sizeof($opcoes); $i++) {
                $dado.= $ {$chave}[$opcoes[$i]];
            }
        } else {
            $dado = ${$chave}[$valor];
        }
        return $dado;
    }

    function mostraData($data_db) {
        if (empty($data_db)) {
            return null;
        } else {
            $data = new Date($data_db);
            return $data->day . "/" . $data->month . "/" . $data->year;
        }
    }

    function inverteData($data) {
        $separador = strpos($data, "/") ? "/" : "-";
        list($v1, $v2, $v3) = explode($separador, $data);
        return $v3 . $separador . $v2 . $separador . $v1;
    }

    function porcentagem($valor1, $valor2) {
        if ($valor1)
            return number_format((($valor2 * 100) / $valor1) - 100, 2, ',', ' ');
        else
            return 0;
    }

    function mostraDinheiro($dinheiro) {
        /*
          if (empty($dinheiro)) {
          return null;
          } else {
         */
        $dinheiro = number_format($dinheiro, 2, ",", null);
        return $dinheiro;
//		}
    }

    function mostraHora($hora, $ini) {
        if (empty($hora)) {
            return null;
        } else {
            $hora = explode(":", $hora);
            if ($ini == 1) {
                if ($hora[1] < TEMPO_CONSULTA) {
                    $hora[1] += TEMPO_CONSULTA;
                } else {
                    $hora[1] -= TEMPO_CONSULTA;
                    $hora[1] = ($hora[1] == 0) ? '00' : ($hora[1] < 10) ? '0' . $hora[1] : $hora[1];
                    $hora[0] += 1;
                    if (($hora[1] == 00) && ($hora[0] < 10))
                        $hora[0] = '0' . $hora[0];
                }
            }
            return $hora[0] . ":" . $hora[1];
        }
    }

    function date_diff($from, $to) {

        list($from_year, $from_month, $from_day) = explode("-", $from);
        list($to_year, $to_month, $to_day) = explode("-", $to);

        $from_date = mktime(0, 0, 0, $from_month, $from_day, $from_year);
        $to_date = mktime(0, 0, 0, $to_month, $to_day, $to_year);

        $days = ($to_date - $from_date) / 86400;

        return ceil($days);
    }

    function upload_image($post, $novo_arquivo, $diretorio, $config) {
        global $ERROS;
//		$config = $erro = array();
        // Prepara a variável do arquivo
        $arquivo = isset($_FILES[$post]) ? $_FILES[$post] : FALSE;

        // Tamanho máximo do arquivo (em bytes)
//		$config["tamanho"] = 106883;
        // Largura máxima (pixels)
//		$config["largura"] = 350;
        // Altura máxima (pixels)
//		$config["altura"]  = 180;
        // Formulário postado... executa as ações
        if ($arquivo) {

            // Verifica se o mime-type do arquivo é de imagem
            if (!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {
                //$erro[] = "Arquivo em formato inválido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo";
                $erro = 1;
                $ERROS[$post] = "Arquivo em formato inv�lido! A imagem deve ser jpg, jpeg, bmp, gif ou png.";
            } else {

                // Verifica tamanho do arquivo
                if ($arquivo["size"] > $config["tamanho"]) {
                    $ERROS[$post] = "Arquivo em tamanho muito grande! A imagem deve ser de no m�ximo " . $config["tamanho"] . " bytes.";
                    $erro = 1;
                }

                // Para verificar as dimensões da imagem
                $tamanhos = getimagesize($arquivo["tmp_name"]);

                // Verifica largura
                if ($tamanhos[0] > $config["largura"]) {
                    $ERROS[$post] = "Largura da imagem n�o deve ultrapassar " . $config["largura"] . " pixels";
                    $erro = 1;
                }

                // Verifica altura
                if ($tamanhos[1] > $config["altura"]) {
                    $ERROS[$post] = "Altura da imagem n�o deve ultrapassar " . $config["altura"] . " pixels";
                    $erro = 1;
                }
            }

            // Retorna as mensagens de erro
            if ($erro) {
                return array($post => NULL);
            }

            // Verificação de dados OK, nenhum erro ocorrido, executa então o upload...
            else {
                // Pega extensão do arquivo
                preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);

                // Gera um nome único para a imagem
                //$imagem_nome = md5(uniqid(time())) . "." . $ext[1];
                $imagem_nome = $novo_arquivo . "." . $ext[1];

                // Caminho de onde a imagem ficará
                $imagem_dir = $diretorio . $imagem_nome;

                $remove = Utils::busca_arquivo($diretorio . $novo_arquivo);
                if (file_exists($remove))
                    $erros = unlink($remove) ? 0 : 1;

                $erros = move_uploaded_file($arquivo["tmp_name"], $imagem_dir) ? $erros : 1;

                if ($erros) {
                    $ERROS[$post] = 'N�o foi poss�vel alterar imagem.';
                    return array($post => NULL);
                }
                else
                    return true;
            }
        }
    }

    function busca_arquivo($nome) {
        return exec("ls " . $nome . ".*");
    }

    function getEvento($tipo) {
        switch ($tipo) {
            case "1" :
                return Utils::getData("eventos_pd", "tipo");
                break;
            case "2" :
                return Utils::getData("eventos_mv", "tipo");
                break;
            case "3" :
                return Utils::getData("eventos_rp", "tipo");
                break;
            case "4" :
                if ($_GET["in_tipo"] == 2)
                    return Utils::getData("menu_eventos_sn", "tipo");
                else
                    return Utils::getData("eventos_al", "tipo");
                break;
            case "5" :
                return Utils::getData("eventos_cad", "tipo");
                break;
        }
    }

    function checkPerm($modulo=null, $acao=null) {

        if (!$_SESSION['med_id']) {
            header("location:http://clinicas.intra.virgos.com.br/");
            exit;
            die();
        }

        switch ($_SESSION['usuario']) {
            case "medico":
                return true;
                break;
            default:

                if ($_SESSION["modulo"][$modulo][$acao])
                    return true;
                else {

                    include_once(SITE_DIR . "/includes/header.php");
                    print "<br><p align='center'>Voc� n�o est� autorizado a visualizar esta p�gina</p><br>";
                    include_once("../includes/footer.php");
                    exit;
                    die();
                }
                break;
        }
    }

    function ValorComVirgula($valor) {
        $valor = str_replace(",", ".", $valor);
        return number_format($valor, 2, ",", null);
    }

    function ValorComPonto($valor) {
        $valor = str_replace(",", ".", $valor);
        return number_format($valor, 2, ".", null);
    }

    function mesExtenso($mes) {
        $meses = array("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
        return $mes > 12 ? $meses[11] : $meses[$mes - 1];
    }

    function semanaExtenso($dia) {
        $semana = array("Domingo", "Segunda-feira", "Ter&ccedil;a-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "S�bado");
        return $dia > 6 ? $semana[6] : $semana[$dia];
    }

    function hasAccess($page, $codigo) {
        include_once(SITE_DIR . "/classes/Usuario.php");
        $user = new Usuario;
        $user->get("usr_codigo", $codigo);

        $arrPages = array(1 => 'T�cnicos',
            2 => 'Produtores',
            4 => 'Propriedades',
            8 => 'Fazendas',
            16 => 'Locais',
            32 => 'Rebanho',
            64 => 'Insumos',
            128 => 'Eventos');


        foreach ($arrPages as $key => $value) {
            if ($value == $page)
                return ($key & $user->usr_acesso);
        }

        return false;
    }

    function isValidDate($mysqlDate) {
        sscanf($mysqlDate, "%d-%d-%d", $ano, $mes, $dia);
        if (($ano == 0) && ($mes == 0) && ($dia == 0))
            return false;
        else
            return true;
    }

    function isValidTime($mysqlTime) {
        @sscanf($mysqlTime, "%d:%d:%d", $hora, $minuto, $segundo);
        if (($hora == 0) && ($minuto == 0) && ($segundo == 0))
            return false;
        else
            return true;
    }

    function ingres_open($db, $user, $pwd) {
        return ingres_connect($db, $user, $pwd);
    }

    function convertText($text) {
        $text = nl2br($text);
        return $text;
    }

    function formatTime($time, $tipo=0) {
        sscanf($time, "%d:%d:%d", $hora, $min, $sec);
        if ($tipo == 0) {
            $ret = $hora . "h" . ($min < 10 ? "0" . $min : $min);
        } else {
            $ret = ($hora < 10 ? "0" . $hora : $hora) . ":" . ($min < 10 ? "0" . $min : $min);
        }
        return $ret;
    }
    
    /*
     * Formata um valor monetário para o formato de dinheiro
     * Parâmetros:
     *    $valor: valor a ser convertido
     *    $pSifrao: valor booleano, quando "true", retorna o símbolo 
     *              de moeda concatenado ao valor formatado
     * Retorno:
     *    Valor monetário formatado
     */

    function formataValorMonetario($valor, $pSifrao) {
        if (trim($valor) == '')
            return '';
        $valor = number_format($valor, 2, ',', '.');
        if ($pSifrao)
            return "R\$ $valor";
        else
            return "$valor";
    }    

    function desformataValorMonetario($valor, $pSifrao) {
        if (trim($valor) == '')
            return '';
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return $valor;
    }    

    function createFrontEndBox($head, $titulos, $inside) {
        $ret = '<table width="100%" cellpadding="2" cellspacing="2" class="style4"><tr bgcolor="#FFFFFF">' . "\n";
        $ret .= '<td valign="top" class="style1"><strong><font color="#000000">' . $head . "</font></strong></td></tr>\n";

        $ret .= '<tr bgcolor="#F5F5F5"><td valign="top">';
        $cnt = 0;
        while ((list($n, $tmp) = each($titulos)) && (list($k, $data) = each($inside))) {
            if ($tmp != "") {
                $ret .= "<font color='black'><strong>" . $tmp . "</strong>: " . $data . "</font><BR>\n";
            } else {
                $ret .= "<font color='black'>" . $data . "</font><BR>\n";
            }
        }
        $ret .= "</td></tr></table><BR>";

        return $ret;
    }

    function createFrontEndList($arrTitulos, $arrData, $width=false) {
        $size_t = sizeof($arrTitulos);
        $arb = $arrData[0];
        if ((sizeof($arb) % $size_t)) {
            die("Erro em frontEnd: sizeof diferentes.");
        }
        $len = sizeof($arrTitulos);
        $linhas = sizeof($arrData);

        $ret = '';

        $ret .= '<table width=100% border=0 cellpadding=2 cellspacing=2 class="style3"><tr bgcolor=#999999>';
        $i = 1;
//			while (list($n, $titulo) = each($arrTitulos)) {
        foreach ($arrTitulos as $titulo) {
            $ret .= '<td align=center><strong class="style1">' . $titulo . "</strong></td>";
            if ($i++ > $len) {
                break;
            }
        }
        $ret .= "</tr>";

        for ($i = 0; $i < $linhas; $i++) {
            $ret .= "<tr bgcolor=#F5F5F5>";
            for ($j = 0; $j < $len; $j++) {
                $ret .= "<td align=center ";
                if ($width !== false) {
                    $ret .= "width='" . $width[$j] . "'";
                }
                $ret .= ">" . $arrData[$i][$j] . "</td>";
            }
            $ret .= "</tr>";
        }

        $ret .= "</table>";
        return $ret;
    }

    // limita uma string
    function maxString($string, $maxchars, $pontos = true) {
        if (strlen($string) > $maxchars) {
            $ret = substr($string, 0, $maxchars);
            if ($pontos == true) {
                $ret .= "...";
            }
        } else {
            $ret = $string;
        }
        return $ret;
    }

    function montaLink($to, $text) {
        $ret = '<div align="right"><a href="' . $to . '"><strong>' . $text . '</strong></a></div>' . "\n";
        return $ret;
    }

    function showTheMessage($msgnum, $from = '') {
        GLOBAL $_SERVER;
        if (empty($from)) {
            $from = $_SERVER['PHP_SELF'];
        }

        header('Location: redireciona.php?url=' . $from . '&msg=' . $msgnum);
        die;
    }

    function uploadFile(&$FILE, $dir, $filename, $tipo = NULL, $size) {
        $erro = array();
        if ($tipo) {
            if (!eregi("^(" . $tipo . ")$", $arquivo["type"])) {
                $erro[] = "Arquivo em formato inv�lido! A imagem deve ser pdf. Envie outro arquivo";
            } else {
                if ($arquivo["size"] > $size) {
                    $erro[] = "Arquivo em tamanho muito grande! O calendario deve ser de no m�ximo " . $size . " bytes. Envie outro arquivo";
                }
            }
        }
        if (sizeof($erro)) {
            return $erro;
        } else {
            if (move_uploaded_file($arquivo["tmp_name"], $dir . $filename)) {
                return true;
            } elseif (copy($arquivo["tmp_name"], $dir . $filename)) {
                return true;
            } else {
                die("impossivel mover arquivo");
                return false;
            }
        }
    }

    function reTransformDate($mysql_format, $format = 0) {
        if (strstr($mysql_format, '0-00-0'))
            return '';

        sscanf($mysql_format, "%d-%d-%d", $ano, $mes, $dia);
        if ($format == 1)
            $retval = ($dia < 10 ? "0" . $dia : $dia) . "/" . ($mes < 10 ? "0" . $mes : $mes);
        else
            $retval = ($dia < 10 ? "0" . $dia : $dia) . "/" . ($mes < 10 ? "0" . $mes : $mes) . "/" . $ano;
        return $retval;
    }

    function toUnixTime($data) {
        sscanf($data, "%d-%d-%d %d:%d:%d", $ano, $mes, $dia, $hora, $minuto, $segundo);
        return mktime($hora, $minuto, $segundo, $mes, $dia, $ano);
    }

    function transformDate($brazilian_format) {
        sscanf($brazilian_format, "%d/%d/%d", $dia, $mes, $ano);
        return sprintf("%04d-%02d-%02d", $ano, $mes, $dia);
    }

    function getOpts($param, $name, $selected = 0) {
        if (defined($param . "_1")) {
            $i = 1;
            $opts = "<option value=\"0\">" . (CHOOSE) . "</option>";

            while (defined($param . "_" . $i))
                $opts .= "<option value=\"$i\"" . ($i == $selected ? " selected" : "") . ">" . constant($param . "_" . $i++) . "</option>\n";
        }

        $opts = "<select name=\"$name\" class=\"formstyle\">\n" . $opts . "</select>";

        return $opts;
    }

    /*
      // $arrErrors ?um array onde a primeira dimens? ?a p?ina onde foi gerado o erro
      //            e a segunda ?o t?ulo do erro
      function showErrors($arrErrors, $pageSource)
      {
      $pageSource = explode("/", $_SERVER["HTTP_REFERER"]);
      $pageSource = $pageSource[sizeof($pageSource)-1];

      echo $pagesource; exit;

      $errors = "";

      for($i=0; $i<sizeof($arrErrors); $i++)
      $errors .= "* ".$ERRORS[$pageSource][$arrErrors[$i]]."<br>";

      }
     */

    function showErrors($arrErrors) {  //print_r($arrErrors);
        global $ERRORS;

        $pageSource = explode("/", $_SERVER["HTTP_REFERER"]);

        if (strstr($pageSource[sizeof($pageSource) - 1], "?")) {
            $pageSource = explode("?", $pageSource[sizeof($pageSource) - 1]);
            $pageSource = $pageSource[0];
        }
        else
            $pageSource = $pageSource[sizeof($pageSource) - 1];

        $errors = "";

        if (is_array($arrErrors) && sizeof($arrErrors)) {
            foreach ($arrErrors as $key => $value) {
                if (!$value)
                    $errors .= "<li>" . ($ERRORS[$pageSource][$key]);
            }

            return '<table width="100%" align="center" class="error"><tr><td>' . $errors . "<br><br></td></tr></table>";
        }
    }

    function getOption($param, $opt) {
        return constant($param . "_" . $opt);
    }

    function showSuccess($msg, $goto="") {
        session_start();
        $_SESSION["msg"] = $msg;

        if (empty($goto)) {
// 		    header("Location: sucesso.php");
        } else {
            header("Location: $goto");
            exit;
        }
    }

    function showMessage($msg) {
        return '<br><table width="80%" align="center" class="message"><tr><td>' . $msg . "</td></tr></table><br>";
        $_SESSION['msg'] = '';
    }

    function showDate($timestamp, $mask=1) {
        if ($timestamp == '0000-00-00' || empty($timestamp))
            return null;

        if (strstr($timestamp, "-")) {
            if ($mask == 1)
                return substr($timestamp, 8, 2) . "/" . substr($timestamp, 5, 2) . "/" . substr($timestamp, 0, 4);
            elseif ($mask == 2)
                return substr($timestamp, 5, 2) . "/" . substr($timestamp, 0, 4); //mes/ano
            elseif ($mask == 3)
                return substr($timestamp, 8, 2) . "/" . substr($timestamp, 5, 2); //dia/mes
        } else {
            if ($mask == 1)
                return substr($timestamp, 6, 2) . "/" . substr($timestamp, 4, 2) . "/" . substr($timestamp, 0, 4);
        }
    }

    function showTime($timestamp) {
        return substr($timestamp, 8, 2) . ":" . substr($timestamp, 10, 2);
    }

    function showTimeOnly($time) {
        if ($time != "00:00:00" && !empty($time))
            return substr($time, 0, 2) . ":" . substr($time, 3, 2);
        else
            return null;
    }

    function showStatus($status) {
        switch ($status) {
            case OPT_STATUS_ABERTO:
                return "em aberto";
                break;
            case OPT_STATUS_EXCLUIDO:
                return "exclu�da";
                break;
            case OPT_STATUS_BLOQUEADO:
                return "bloqueada";
                break;
            case OPT_STATUS_FECHADO_SITE:
                return "fechada pelo site";
                break;
            case OPT_STATUS_FECHADO_OUTROS:
                return "fechada por outros meios";
                break;
            case OPT_STATUS_FECHADO_CANCELADO:
                return "cancelada";
                break;
        }
    }

    function showLogin() {
        ?>
        <div style="text-align: center;">
            <form name="login" action="<?= $_SEERVER['PHP_SELF'] ?>" method="post">
                <table id="login">
                    <tr>
                        <td>Login: </td>
                        <td><input type="text" name="login" value="<?= $_POST['login'] ?>" size="30"></td>
                    </tr>
                    <tr>
                        <td colspan="2"> &nbsp; </td>
                    </tr>
                    <tr>
                        <td>Senha: </td>
                        <td><input type="password" name="senha" value="<?= $_POST['senha'] ?>" size="30"></td>
                    </tr>
                    <tr>
                        <td colspan="2"> &nbsp; </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="enviar" value=" Entrar "></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php
    }

    function setComboAno($campo, $selecionado = '') {
        $tpl_select = new HTML_Template_Sigma(TEMPLATE_DIR . '/utils');
        $tpl_select->loadTemplateFile('select_ano.tpl.html');

        $tpl_select->setVariable('nome_do_campo', $campo);

        $ano_atual = date('Y');
        $ano_inicio = 2000;

        if ($selecionado == '')
            $selecionado = $ano_atual;

        for ($ano = $ano_atual; $ano >= $ano_inicio; $ano--) {
            $tpl_select->setVariable('Ano', $ano);
            $tpl_select->setVariable('AnoSelecionado', ($selecionado == $ano) ? 'selected' : '');
            $tpl_select->parse('block_sl_ano');
        }

        return $tpl_select->get();
    }
    
    function getConfiguracaoPadrao($tipo) {
        if($fd = @fopen(INCLUDE_DIR. "/padroes.txt", "r" )) {
            $count = 0;
            $tipo = ($tipo == 'zona') ? 1 : 0;
            while( !feof( $fd ) ) {
                $padroes = fgets( $fd, 1024 );
                if ($count == $tipo)
                    $ret = $padroes;
                $count++;
            }
            fclose($fd);
        }
        return $ret;
    }
    
    function setConfiguracaoPadrao($tipo, $valor){
        
        if ($tipo == 'zona') {
            $municipio = trim(Utils::getConfiguracaoPadrao('municipio'));
            $zona = $valor;
        } else {
            $zona = trim(Utils::getConfiguracaoPadrao('zona'));
            $municipio = $valor;
        }
        
        if($fd = fopen(INCLUDE_DIR. "/padroes.txt",w)) {
            fwrite($fd, $municipio."\n");
            fwrite($fd, $zona);
            fclose($fd);
        }
    }
    
    function prepareToSearch($str){
        $str = ereg_replace("[ÁÀÂÃ]","A",$var);
        $str = ereg_replace("[áàâãª]","a",$var);
        $str = ereg_replace("[ÉÈÊ]","E",$var);
        $str = ereg_replace("[éèê]","e",$var);
        $str = ereg_replace("[ÓÒÔÕ]","O",$var);
        $str = ereg_replace("[óòôõº]","o",$var);
        $str = ereg_replace("[ÚÙÛ]","U",$var);
        $str = ereg_replace("[úùû]","u",$var);
        $str = str_replace("Ç","C",$var);
        $str = str_replace("ç","c",$var);
        return strtoupper($str);
    }
    
    function array_prefix_sufix($arr, $prefix, $sufix){
        array_walk( $arr , "Utils::add_prefix_sufix", array($prefix, $sufix) );
        return $arr;
    }
    
    function add_prefix_sufix(&$value, $key, array $add){
        $value = $add[0].$value.$add[1];
    }
    
    function array_implode_prefix($outer_glue, $arr, $inner_glue, $prefix=false){
        array_walk( $arr , "Utils::prefix", array($inner_glue, $prefix) );
        return implode($outer_glue, $arr);
    }

    function prefix(&$value, $key, array $additional){
        $inner_glue = $additional[0];
        $prefix = isset($additional[1])? $additional[1] : false;
        if($prefix === false) $prefix = $key;

        $value = $prefix.$inner_glue.$value;
    }    
}

Class Upload extends Utils {

    var $maxupload_size;
    var $HTTP_POST_FILES;
    var $errors;

    function Upload($HTTP_POST_FILES) {
        $this->HTTP_POST_FILES = $HTTP_POST_FILES;
        $this->isPosted = false;
        $this->maxupload_size = (1024 * 1024 * 10);
    }

    function getValidName($formfield) {
        global $HTTP_POST_FILES;
        //if (!defined("SITE_UPLOAD_DIR")) define("SITE_UPLOAD_DIR", "/tmp");
        $num = 1;
        $file_name = $HTTP_POST_FILES[$formfield]['name'];

        while (file_exists(SITE_UPLOAD_DIR . "/" . $file_name)) {
            $fArray = explode(".", $HTTP_POST_FILES[$formfield]['name']);
            $file_name = "";
            while (count($fArray) > 1) {
                if ($file_name)
                    $file_name .= ".";
                $file_name .= array_shift($fArray);
            }
            $file_name .= "_" . $num++ . "." . array_shift($fArray);
            $uploadfile = SITE_UPLOAD_DIR . "/" . $file_name;
        }
        return $file_name;
    }

    function save($directory, $field, $overwrite, $mode=0777) {
        if (is_array($field)) {
            //print_r($field); die();
            $field_name = $this->HTTP_POST_FILES[$field[0]]['name'][$field[1]];
            $field_type = $this->HTTP_POST_FILES[$field[0]]['type'][$field[1]];
            $field_tmp_name = $this->HTTP_POST_FILES[$field[0]]['tmp_name'][$field[1]];
            $field_error = $this->HTTP_POST_FILES[$field[0]]['error'][$field[1]];
            $field_size = $this->HTTP_POST_FILES[$field[0]]['size'][$field[1]];
        } else {
            $field_name = $this->HTTP_POST_FILES[$field]['name'];
            $field_type = $this->HTTP_POST_FILES[$field]['type'];
            $field_tmp_name = $this->HTTP_POST_FILES[$field]['tmp_name'];
            $field_error = $this->HTTP_POST_FILES[$field]['error'];
            $field_size = $this->HTTP_POST_FILES[$field]['size'];
        }

        $this->isPosted = true;
        if ($field_size < $this->maxupload_size && $field_size > 0) {
            $noerrors = true;
            $this->isPosted = true;
            // Get names
            $tempName = $field_tmp_name;
            $file = $field_name;
            $all = $directory;  //.$file;
            // Copy to directory
            if (file_exists($all)) {
                if ($overwrite) {
                    @unlink($all) || $noerrors = false;
                    $this->errors = "Upload class save error: unable to overwrite " . $all . "<BR>";
                    @copy($tempName, $all) || $noerrors = false;
                    $this->errors .= "Upload class save error: unable to copy to " . $all . "<BR>";
                    @chmod($all, $mode) || $ernoerrorsrors = false;
                    $this->errors .= "Upload class save error: unable to change permissions for: " . $all . "<BR>";
                }
            } else {
                @copy($tempName, $all) || $noerrors = false;
                $this->errors = "Upload class save error: unable to copy to " . $all . "<BR>";
                @chmod($all, $mode) || $noerrors = false;
                $this->errors .= "Upload class save error: unable to change permissions for: " . $all . "<BR>";
            }
            return $noerrors;
        } elseif ($field_size > $this->maxupload_size) {
            $this->errors = "File size exceeds maximum file size of " . $this->maxuploadsize . " bytes";
            return false;
        } elseif ($field_size == 0) {
            $this->errors = "File size is 0 bytes";
            return false;
        }
    }

    function saveAs($filename, $directory, $field, $overwrite, $mode=0777) {
        $this->isPosted = true;
        if ($this->HTTP_POST_FILES[$field]['size'] < $this->maxupload_size
                && $this->HTTP_POST_FILES[$field]['size'] > 0) {

            $noerrors = true;

            // Get names
            $tempName = $this->HTTP_POST_FILES[$field]['tmp_name'];
            if (!preg_match('/\/$/i', $directory))
                $directory .= "/";
            $all = $directory . $filename;

            // Copy to directory
            if (file_exists($all)) {
                if ($overwrite) {
                    @unlink($all) || $noerrors = false;
                    $this->errors = "Upload class saveas error: unable to overwrite " . $all . "<BR>";
                    @copy($tempName, $all) || $noerrors = false;
                    $this->errors .= "Upload class saveas error: unable to copy to " . $all . "<BR>";
                    @chmod($all, $mode) || $noerrors = false;
                    $this->errors .= "Upload class saveas error: unable to copy to" . $all . "<BR>";
                }
            } else {
                @copy($tempName, $all) || $noerrors = false;
                $this->errors = "Upload class saveas error: unable to copy to " . $all . "<BR>";
                @chmod($all, $mode) || $noerrors = false;
                $this->errors .= "Upload class saveas error: unable to change permissions for: " . $all . "<BR>";
            }
            return $noerrors;
        } elseif ($this->HTTP_POST_FILES[$field]['size'] > $this->maxupload_size) {
            $this->errors = "File size exceeds maximum file size of " . $this->maxuploadsize . " bytes";
            return false;
        } elseif ($this->HTTP_POST_FILES[$field]['size'] == 0) {
            $this->errors = "File size is 0 bytes";
            return false;
        }
    }

    function getFilename($field) {
        return $this->HTTP_POST_FILES[$field]['name'];
    }

    function getFileMimeType($field) {
        return $this->HTTP_POST_FILES[$field]['type'];
    }

    function getFileSize($field) {
        return $this->HTTP_POST_FILES[$field]['size'];
    }

}
?>