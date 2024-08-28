<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo LANG ?>" lang="<?php echo LANG ?>" >

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Avalia&ccedil;&atilde;o</title>

<link href="<?= IMAGE_URL ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

<link href="<?= CSS_URL ?>/reset.css" rel="stylesheet" type="text/css" />

<!--<link href="<?= CSS_URL ?>/960.css" rel="stylesheet" type="text/css" />-->

<link href="<?= CSS_URL ?>/1200.css" rel="stylesheet" type="text/css" />

<link href="<?= CSS_URL ?>/text.css" rel="stylesheet" type="text/css" />

<link href="<?= JS_URL ?>/datatables/css/demo_table.css" rel="stylesheet" type="text/css" />

<link href="<?= CSS_URL ?>/datatable.css" rel="stylesheet" type="text/css" />

<!--<link href="<?= JS_URL?>/css/smoothness/jquery-ui-1.8.13.custom.css" rel="stylesheet" type="text/css" />-->

<link href="<?= JS_URL?>/css/flick/jquery-ui-1.8.15.custom.css" rel="stylesheet" type="text/css" />

<link href="<?= CSS_URL ?>/newStyle.css" rel="stylesheet" type="text/css" />

<!--<script type="text/javascript" src="<?= JS_URL?>/jquery.js"></script>-->

<script type="text/javascript" src="<?= JS_URL?>/jquery.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/plentz-jquery-maskmoney/jquery.maskMoney.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/jquery.maskedinput.min.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/alphanumeric/jquery.alphanumeric.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/jquery-validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/jquery.fileupload.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/datatables/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/showhide.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/actions.js"></script>

<script type="text/javascript" src="<?= JS_URL?>/scripts.js"></script>

</head>



<body>

    <div style="display: hidden;">

        <div id="dialog" title="Cadastro">

            <form id="dialog-form">

                <div id="dialog-conteudo"></div>

            </form>

        </div>

    </div>

<!--  <div id="wrapper">

      <div id="container">

      <div id="test" style="display: none;">

          <table width="100%"><tr><td>&nbsp;  </td><td width="700">

              <div id="form_ajax" style="">

                

                <div id="X"><a href="javascript:;" onClick="sair()"><img src="images/cancelar.png"></a></div>

                <form id="ajax_form" method="post" action="actions.php">

                <div id="form_ajax_title" class="ajax_space titulo"></div>

                <div id="form_ajax_content" class="ajax_space">

                    TEST X

                </div>

                <!--<div id="form_ajax_actions" class="ajax_space">

                    

                    <input type="button" onClick="ShowHide('test')" value="Cancelar"> &nbsp;&nbsp;

                    <input type="submit" id="btnOk" value="Gravar">

                    

                </div>--

                </form>

              

            </div>

          </td><td> &nbsp </td></tr></table>

      </div>-->

<center>

<div class="allHeader">

    <div class="allHeaderBack">

<!-- Header -->

<div class="topo">

    Sistema de Avalia&ccedil;&atilde;o de Im&oacute;veis

</div>

<!-- End Header -->



<!-- Menu -->

<div class="menu">

    <ul class="topnav">

        <li><a href="<?php echo HOME; ?>">In&iacute;cio</a></li>

        <li><a href="imoveis.php">Elementos de Pesquisa</a></li>

        <li><a href="avaliacoes.php">Avalia&ccedil;&otilde;es</a></li>

        <!--<li><a>Relat�rios</a></li>-->

        <?php if($usuarioLogado->hasPermission('Administrador')) : ?>

        <li class="drop"><a>Cadastros</a>  

            <ul class="subnav">  

                <li><a href="estados_conservacoes.php">Estados de Conserva&ccedil;&atilde;o</a></li> 

                <li><a href="imobiliarias.php">Imobili&aacute;rias</a></li> 

                <li><a href="melhoramentos.php">Melhoramentos</a></li> 

                <li><a href="municipios.php">Munic&iacute;pios</a></li> 

                <li><a href="padroes.php">Padr&otilde;es</a></li> 

                <li><a href="tipos_imoveis.php">Tipos de Im&oacute;veis</a></li>

                <li><a href="topografias.php">Topografias</a></li> 

                <li><a href="zonas.php">Zonas</a></li>

                <?php ?>

                <li><a href="usuarios.php">Usu&aacute;rios</a></li>

                <?php ?>

            </ul>  

        </li>  

        <?php endif; ?>

        <?php if($usuarioLogado->id) : ?>

        <li><a href="index.php?logout=1">Sair</a></li>

        <?php endif; ?>

    </ul> 

    <div id="usuario">

        <label><?php echo ($usuarioLogado->id)? 'Usu&aacute;rio: '.$usuarioLogado->nome.' - <a class="new_insert" href="usuarios.php?dadosPessoais=1&id='.$_SESSION['usuario_id'].'">Alterar Dados</a>':''; ?></label>

    </div>

</div>

<!-- End Menu -->

    </div>

</div>



<!-- Middle -->

  <div class="corpo">

    <div class="conteudo">