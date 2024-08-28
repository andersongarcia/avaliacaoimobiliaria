<?php
include("../includes/config.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo LANG ?>" lang="<?php echo LANG ?>" >
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Avalia&ccedil;&atilde;o</title>
<link href="<?= IMAGE_URL ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link href="<?= CSS_URL ?>/reset.css" rel="stylesheet" type="text/css" />
<link href="<?= CSS_URL ?>/960.css" rel="stylesheet" type="text/css" />
<link href="<?= CSS_URL ?>/text.css" rel="stylesheet" type="text/css" />
<link href="<?= CSS_URL ?>/newStyle.css" rel="stylesheet" type="text/css" />
<link href="<?= JS_URL?>/css/ui-darkness/jquery-ui-1.8.13.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= JS_URL?>/jquery.js"></script>
<script type="text/javascript" src="<?= JS_URL?>/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= JS_URL?>/showhide.js"></script>
<script type="text/javascript" src="<?= JS_URL?>/actions.js"></script>
<script type="text/javascript" src="<?= JS_URL?>/scripts.js"></script>
</head>

<body>
    
        <div id="dialog" title="Cadastro">
            <form id="dialog-form">
                <div id="dialog-conteudo">Conteudo</div>
            </form>
        </div>
    
        <div id="sucesso" title="Cadastro">
            <p>Dados enviados com sucesso!</p>
        </div>
    
  <div id="wrapper">
      <div id="container">
      <div id="test" style="display: none;">
          <table width="100%"><tr><td> &nbsp; </td><td width="700">
              <div id="form_ajax" style="">
                
                <div id="X"><a href="javascript:;" onclick="sair()"><img src="images/cancelar.png"></a></div>
                <form id="ajax_form" method="post" action="actions.php">
                <div id="form_ajax_title" class="ajax_space titulo"></div>
                <div id="form_ajax_content" class="ajax_space">
                    TEST X
                </div>
                <!--<div id="form_ajax_actions" class="ajax_space">
                    
                    <input type="button" onClick="ShowHide('test')" value="Cancelar"> &nbsp;&nbsp;
                    <input type="submit" id="btnOk" value="Gravar">
                    
                </div>-->
                </form>
              
            </div>
          </td><td> &nbsp </td></tr></table>
      </div>
<!-- Header -->
<div class="topo">
    Cabeçalho
</div>
<!-- End Header -->

<!-- Menu -->
<div class="menu">
    <ul class="topnav">
        <li><a>Início</a></li>
        <li><a href="imoveis.php">Imóveis</a></li>
        <li><a>Avaliações</a></li>
        <li><a>Relatórios</a></li>
        <li class="drop"><a>Cadastros</a>  
            <ul class="subnav">  
                <li><a href="estados_conservacoes.php">Estados de Conservações</a></li> 
                <li><a href="imobiliarias.php">Imobiliárias</a></li> 
                <li><a href="melhoramentos.php">Melhoramentos</a></li> 
                <li><a href="municipios.php">Municipios</a></li> 
                <li><a href="padroes.php">Padrões</a></li> 
                <li><a href="tipos_imoveis.php">Tipos de Imóveis</a></li>
                <li><a href="topografias.php">Topografias</a></li> 
                <li><a href="zonas.php">Zonas</a></li>  
            </ul>  
        </li>  
    </ul> 
</div>
<!-- End Menu -->

<!-- Middle -->
<div class="conteudo">
    Coteudo aki
</div>

</body>
</html>