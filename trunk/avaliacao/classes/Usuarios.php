<?php

/**
 * Table Definition for usuarios
 */
require_once '../pear/DB/DataObject.php';

class Usuarios extends DB_DataObject {
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'usuarios';            // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $nome;                            // string(50)  not_null
    public $email;                           // string(50)  not_null unique_key
    public $senha;                           // string(50)  not_null
    public $permissao;                       // string(20)  not_null
    public $ticket;                          // string(32)  
    public $ativo;                           // int(11)    OBS: 1 - Ativo / 2 - NÃ£o Ativo  

    /* Static get */

    function staticGet($k, $v=NULL) {
        return DB_DataObject::staticGet('Usuarios', $k, $v);
    }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function showAll() {

        // Chama o construtor do template
        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/usuarios');

        // carrega o arquivo de template
        $tpl->loadTemplateFile('show_all.html');

        while ($this->fetch()) {
            
            if ($this->ativo == 1) {
                $ativo = '<a class="mudarAtivoN" href="usuarios.php?ativar=2&id='.$this->id.'" title="Desativar"><img src="images/ativoS.png" /></a>';
            } else {
                $ativo = '<a class="mudarAtivoS" href="usuarios.php?ativar=1&id='.$this->id.'" title="Ativar"><img src="images/ativoN.png" /></a>';
            }
            // atribui os dados da linha
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Nome' => $this->nome,
                'Email' => $this->email,
                'Permissao' => $this->permissao,
                'Ativo' => $ativo
            ));
            // processa o bloco
            $tpl->parse('table_row');
        }

        // imprime o html resultante
        $tpl->show();
    }

    function showForm() {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/usuarios');
        $tpl->loadTemplateFile('show_form.html');

        if ($this->id) {
            $tpl->setVariable(array(
                'ID' => $this->id,
                'Nome' => $this->nome,
                'Email' => $this->email,
                'Ativo' => $this->ativo,
                'Botao' => 'alterar',
                'Uri' => $_SERVER['REQUEST_URI'],
            ));
            $tpl->touchBlock("BLOCK_EXCLUIR");
        } else {
            $tpl->setVariable(array(
                'Botao' => 'enviar',
                'Uri' => $_SERVER['REQUEST_URI'],
            ));
        }
        if ($this->ativo == 1) {
            $tpl->setVariable('AtivoSelecionado', 'checked');
        } else {
            $tpl->setVariable('AtivoNSelecionado', 'checked');
        }
        switch ($this->permissao) {
            case 'Administrador':
                $tpl->setVariable('PermissaoAdministradorSelecionado', 'checked');
                break;
            case 'Cadastro':
                $tpl->setVariable('PermissaoCadastroSelecionado', 'checked');
                break;
            default:
                $tpl->setVariable('PermissaoConsultaSelecionado', 'checked');
                break;
        }
        $tpl->show();
    }
    
    function showUpdateForm() {

        $tpl = new HTML_Template_Sigma(TEMPLATE_DIR . '/usuarios');
        $tpl->loadTemplateFile('show_update.html');

        $tpl->setVariable(array(
            'ID' => $this->id,
            'Nome' => $this->nome,
            'Email' => $this->email,
        ));
            
        $tpl->show();
    }

    function checaAutenticacao() {
        if (!isset($_POST['efetuarLogin'])) {
            if (!isset($_SESSION['logado'])) {
                if (isset($_COOKIE['AvaliacaoUsuario'])) {
                    $this->email = $_COOKIE['AvaliacaoUsuario'];
                    $this->find();
                    if ($this->fetch()) {
                        if ($this->ticket == $_COOKIE['AvaliacaoTicket']) {
                            $this->gravarSessao();
                            $this->gravarCookie();
                            return true;
                        }
                    }
                }
                if (!strstr($_SERVER['PHP_SELF'], 'index.php'))
                    header("Location: index.php");
            }else {
                $this->get($_SESSION['usuario_id']);
                return true;
            }
        }
    }

    function autenticar($senha) {
        while ($this->fetch()) {
            if (md5($senha) == $this->senha) {
                $this->gravarSessao();
                $this->gravarCookie();

                header("Location: home.php");
                return '';
            }
            return 'Senha inválida!';
        }
        return 'Usuário não cadastrado';
    }
    
    function gravarSessao() {
        $_SESSION['logado'] = 1;
        $_SESSION['usuario_id'] = $this->id;
    }

    function gravarCookie() {
        $tempo = 60 * 60 * 24 * 7;
        $this->ticket = md5($this->email + time());
        $this->update();
        setcookie('AvaliacaoUsuario', $this->email, time() + $tempo);
        setcookie('AvaliacaoTicket', $this->ticket, time() + $tempo);
    }

    function hasPermission($role) {
        if (!$this->id)
            return false;

        switch ($this->permissao) {
            case 'Administrador':
                return true;
            case 'Cadastro':
                return ($role == 'Cadastro');
            case 'Consulta':
                return ($role == 'Consulta');
            default:
                return false;
        }
    }

    function hasTicket($cookie) {
        
    }

    function getLogado() {
        return $this->get($_SESSION['usuario_id']);
    }

}
