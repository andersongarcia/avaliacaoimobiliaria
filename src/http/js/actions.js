/* Autor: Erick Previato */

// Esta função instancia o objeto XMLHttpRequest
function openAjax() {
	var ajax;
	try {
		ajax = new XMLHttpRequest();
	} catch(ee) {
		try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(E) {
				ajax = false;
			}
		}
	}
	return ajax;
}

function getDados(id, url, item) {
    var ajax = openAjax();
    var recipiente = gE(id);
    ajax.open('GET', url, true);
    ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                    loading(true);
            }
            if (ajax.readyState == 4) {
                    if (ajax.status == 200) {
                            loading(false);
                            recipiente.innerHTML = ajax.responseText;
                            btnOkBtnCancelar();
                            focusAjax('descricao');
                            ativarBotaoCadastro(item);
                    }
            }
    }
    ajax.send(null);
    return false;
}

function getDataPost(id) {
    var dataPost = '&class=' + id;
    if (id == 'imovel_tipo') {
        dataPost += '&descricao=' + gE('descricao').value;
        dataPost += '&forma=' + gE('forma').value;
        dataPost += '&vagas_de_garagem=' + gE('vg').value;
        dataPost += '&wcs=' + gE('wc').value;
        dataPost += '&dormitorios=' + gE('do').value;
        dataPost += '&suites=' + gE('su').value;
    }
    return dataPost;
}

function setDados(id) {
    // Valida os dados informado, a função retornará¡ false se houver erro, e true se estiver tudo ok.
    var validacao = true; // validarForm();
    // Verifica o retorno da função
    if (validacao == true) {
            var ajax = openAjax();
            ajax.open('POST', 'actions.php?ajax=true', true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4) {
                            if (ajax.status == 200) {
                                    // Atualiza o relatÃ³rio com os contatos cadastrados
                                    //atualizaRelatorio();
                            } // status ->200
                    } // readyState->4
            } // ajax->onreadystatechange
            // Criaremos uma variável que armazenará os dados do formulário
            // Será um cadastro ou edição?
            var tipoAcao = gE('action').value;
            // Se for cadastro ...
            if (tipoAcao == 'cadastrar') {
                    var dataPost = 'action=cadastrar';
            } else if (tipoAcao == 'editar') {
                    var dataPost = 'action=editar&ID=' + gE('ID').value;
            }
            dataPost += getDataPost(id);
            alert(dataPost);
            ajax.send(dataPost);
    } // validacao == true
    // Evita que o form seja enviado e dÃª o reload na página
    return false;
}

function ativarBotaoCadastro(id) {
        // Se não houver o botão/link aborta a função
	if (!gE('enviar')) return false;
	// Ao clicar no botão será realizada uma ação
        alert('aki '+id);
	gE('enviar').onclick = function() {
            setDados(id);
        }
}

// Chama a função loadFunctions ao carregar a página
window.onload = loadFunctions;
document.onclick = mouse;
var flag = false;
var posY = 0;

function mouse(e) {
        if (navigator.appName == 'Netscape'){
                xcurs = e.pageX;
                ycurs = e.pageY;  
        } else {
                xcurs = event.clientX;
                ycurs = event.clientY;
        }
        if (flag) {
            posY = ycurs - 40;
            flag = false;
        }
        //alert("x:"+xcurs+" y:"+ycurs);
}

function changeValue(id) {
    gE(id).value = (gE(id).value == 1) ? 0 : 1;
}

function add(item) {
    ShowHide('test');
    window.scrollTo(0,0);
    var top = 'event.pageY';
    //alert(window.innerHeight+' - '+window.scrollMaxY+' - '+document.body.scrollWidth+' - '+document.body.offsetHeight+' - Y:'+top);
    getTitle(item);
    getDados('form_ajax_content', 'actions.php?show=true&class='+item, item);
    flag = true;
}

function sair() {
    ShowHide('test');
    window.scrollTo(0, posY);
}

// Função que chama outras funcoees
function loadFunctions() {
	focusNome();
	ativarBtnCadastro();
	ativarBtnEditarBtnExcluir();
}

// Utilizado para evitar de digitar: document.getElementById toda hora, tornando o processo mais prático
function gE(ID) {
	return document.getElementById(ID);
}

// Utilizado para evitar de digitar: document.getElementsByTagName toda hora, tornando o processo mais prático
function gEs(tag) {
	return document.getElementsByTagName(tag);
}

function getTitle(tag) {
    if (!gE('form_ajax_title')) return false;
    
    switch (tag) {
        case 'imovel_tipo':
            gE('form_ajax_title').innerHTML = 'Cadastro de Tipo de Im&oacute;vel';
            break;
        case 'municipio' :
            gE('form_ajax_title').innerHTML = 'Cadastro de Munic&iacute;pio';
            break;
        default: break;
    }
}

// Esta função Ã© utilizada para exibir o formulário quando o link/botão Cadastrar novo contato Ã© clicado
function ativarBtnCadastro() {
	// Se não houver o botão/link aborta a função
	if (!gE('btnNovoCadastro')) return false;
	// Ao clicar no botão será realizada uma ação
	gE('btnNovoCadastro').onclick = function() {
		// Executa a função que cria o fundo sobre página
		exibirBgBody();
		// Cria um div - definida como boxCad - que armazenará o formulário de cadastro
		boxCad();
		// Inicia o Ajax, atravÃ©s da variável Ajax
		var ajax = openAjax();
		// A tag bgBody conterá o formulário de cadastro
		var recipiente = gE('boxCad');
		// Informamos o mÃ©todo e a página que será requisitada
		ajax.open('GET', 'formulario.php?ajax=true', true); 
		// bla
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 1) {
				// Cria o efeito de loading
				loading(true);	
			} // if->readyState->1
			if (ajax.readyState == 4) {
				if (ajax.status == 200) {
					// Remove o efeito de loading
					loading(false);
					// Pega o conteÃºdo - HTML - da página requisitada: formulario.php?ajax=true e coloca dentra da div definida na variável recipiente
					recipiente.innerHTML = ajax.responseText;
					// Chama a função que trabalha sobre os botÃµes de Ok e Cancelar
					btnOkBtnCancelar();
					// Seta o focus no campo nome do cadastro
					focusNome();
				} // if-status->200
			} // if->readyState->4
		} // ajax->onreadystatechange
		// Envia a requisição
		ajax.send(null);
		// Evita o reload da página
		return false;
	}
}

// Utilizado para criar o fundo sobre a página (wiewport), body.
function exibirBgBody() {
	// Seleciona a tag body. item(0) por que sÃ³ existe uma tag body
	var tagBody = gEs('body').item(0);
	// Pega os tamanhos atuais da página, como largura, altura, ...
	var sizesPage = getPageSize();
	// Vamos criar uma tag div
	var bgBody = document.createElement('div');
	// Setar o atributo ID a div criada
	bgBody.setAttribute('id','bgBody');
	// Essa div terá o tamanho exato da página
	bgBody.style.height = arrayPageSize[1] + 'px';
	// Essa div terá a largura exata da página
	bgBody.style.width = arrayPageSize[0] + 'px';
	// Evita criar a div novamente
	if (!gE('bgBody')) {
		tagBody.insertBefore(bgBody, tagBody.firstChild);
	}	
}

// Cria a div denominada como boxCad, a qual conterá o formulário de cadastro
function boxCad() {
	// Cria um 'container' que comportará o formulário de cadastro.
	var objBody = gEs('body').item(0);
	var sizesPage = getPageSize();
	var boxCad = document.createElement('div');
	boxCad.setAttribute('id','boxCad');
	var wPage = arrayPageSize[0]; // Largura total da página
	var hPage = arrayPageSize[1]; // tamanho total da página
	/*boxCad.style.width = (wPage / 2) + 'px'; // metade da largura da página*/
	boxCad.style.height = (wPage / 2) + 'px'; // metada da altura da página
	boxCad.style.marginTop = -(wPage / 4) + 'px'; // 1 quarto da largura
	//boxCad.style.marginLeft = -(wPage / 4) + 'px'; // 1 quarto da altura
	objBody.insertBefore(boxCad, objBody.lastChild);
}

// Utilizado para criar o efeito de loading
function loading(opt) {
	if (opt == true) {
		// A tag que receberá a img de loading
		var refer = gE('bgBody');
		// O tamanho da referida tag
		var referHeight = refer.offsetHeight;
		// Dizemos que os elementos dentro dela será alinhado ao centro
		refer.style.textAlign = 'center';
		// Criamos uma imagem, img.
		var img = document.createElement('img');
		// Informamos o caminho da img
		img.setAttribute('src','img/imgLoading.gif');
		// Setamos um atributo ID na img criada
		img.setAttribute('id','loading');
		// Definimos seu tamanho
		img.setAttribute('width','126');
		// Dizemos que o margin-top será a metada do tamanho da div
		img.style.marginTop = (referHeight /2) + 'px';
		// Evita que seja criada duas ou mais img de loading
		if (!document.getElementById('loading')) {
			// Insere a img na tag informada na variável refer
			refer.insertBefore(img, refer.firstChild);
		}
	} else if (opt == false) {
		// Referenciamos a img de login atravÃ©s de seu ID
		var imgLoading = gE('loading');
		// Removemos a img de loading
		if (imgLoading) {
			imgLoading.parentNode.removeChild(imgLoading);
		}
	}
}

// FunÃ§Ãµes que será vinculadas ao botão Ok e Cancelar do formulário
function btnOkBtnCancelar() {
	// Se não houver os botÃµes aborta a função
	if (!gE('btnOk')) return false;
	if (!gE('btnCancelar')) return false;

	gE('btnOk').onclick = function() {
		// Valida os dados informado, a função retornará false se houver erro, e true se estiver tudo ok.
		var validacao = validarForm();
		// Verifica o retorno da função
		if (validacao == true) {
			var ajax = openAjax();
			ajax.open('POST', 'actions.php?ajax=true', true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					if (ajax.status == 200) {
						// Atualiza o relatÃ³rio com os contatos cadastrados
						atualizaRelatorio();
					} // status ->200
				} // readyState->4
			} // ajax->onreadystatechange
			// Criaremos uma variável que armazenará os dados do formulário
			// Será um cadastro ou edição?
			var tipoAcao = gE('action').value;
			// Se for cadastro ...
			if (tipoAcao == 'cadastrar') {
				var dataPost = 'action=cadastrar';
			} else if (tipoAcao == 'editar') {
				var dataPost = 'action=editar&ID=' + gE('ID').value;
			}
			dataPost += '&nome=' + gE('nome').value;
			dataPost += '&obs=' + gE('obs').value;
			dataPost += '&ddd=' + gE('ddd').value;
			dataPost += '&tel=' + gE('tel').value;
			dataPost += '&cel=' + gE('cel').value;
			dataPost += '&email=' + gE('email').value;
			dataPost += '&blog=' + gE('blog').value;
			dataPost += '&msn=' + gE('msn').value;
			dataPost += '&gtalk=' + gE('gtalk').value;
			dataPost += '&skype=' + gE('skype').value;
			alert(dataPost);
			ajax.send(dataPost);
		} // validacao == true
		// Evita que o form seja enviado e dÃª o reload na página
		return false;
	}
	
	gE('btnCancelar').onclick = function() {
		// Elimina o fundo criado para o body e a div - boxCad - que contÃ©m o formulário de cadastro.
		removerDivs();
		// Cancela a função do botão de 'limpar' os dados preenchidos.
		return false;
	}
}


// Esta função seta o focus ao campo nome do formulário
function focusNome() {
	// Se hão houver o campo nome aborta a função
	if (!gE('nome')) return false;
	// Concede o focus ao campo nome do cadastro
	gE('nome').focus();
}

function focusAjax(id) {
	// Se hão houver o campo nome aborta a função
	if (!gE(id)) return false;
	// Concede o focus ao campo nome do cadastro
	gE(id).focus();
}

// Esta função valida os dados do formulário de preenchimento obrigatÃ³rio
function validarForm() {
	// Se não houver o formulário com o ID frmCad aborta a função
	if (!gE('frmCad')) return false;
	// Relação dos campos que devem ser preenchidos
	var nome = gE('nome');
	var ddd = gE('ddd');
	var tel = gE('tel');
	var email = gE('email');
	// Valida o campo nome, ou seja, ele não pode ficar em branco
	if (nome.value == '' || nome.value == null) {
		// Informa ao usuário o erro ocorrido
		alert('Ops! Informe o seu nome.');
		// Seta o focus no campo com erro
		nome.focus();
		// Retorna false, para a outra saber que algo está errado e não liberar o cadastro
		return false;
	}
	// Valida o DDD e em seguida o telefone
	if (ddd.value == '' || ddd.value == null) {
		alert('Ops! Informe o seu DDD.');
		ddd.focus();
		return false;
	}
	if (tel.value == '' || tel.value == null) {
		alert('Ops! Informe o seu telefone.');
		tel.focus();
		return false;
	}
	// Verifica o e-mail informado, retornando false se ele for inválido e true se for válido
	var verificaEmail = validaEmail(email.value);
	// Se for inválido exibe o erro
	if (verificaEmail == false) {
		alert('Ops! O e-mail informado, ' + email.value + ', Ã© inválido; verifique-o.');
		email.focus();
		return false;
	}
	return true;
}

// Função que valida o e-mail informado
function validaEmail(email){
	return email.search(/(\w[\w\.\+]+)@(.+)\.(\w+)$/)==0;
}

// Esta função Ã© utilizada para atualizar o relatÃ³rio com os registrados da agenda
function atualizaRelatorio() {
	var ajax = openAjax();
	ajax.open('GET', 'relatorio.php?ajax=true', true);
	ajax.onreadystatechange = function() {
		var conteudo = gE('conteudo');
		if (ajax.readyState == 1) {
			loading(true);
		}
		if (ajax.readyState == 4) {
			if (ajax.status == 200) {
				loading(false);
				removerDivs();
				conteudo.innerHTML = ajax.responseText;
				ativarBtnEditarBtnExcluir();
			} 
		}
	}
	ajax.send(null);
}

// Função que ativa o botão de edição e exclusão dos contatos
function ativarBtnEditarBtnExcluir() {
	// Seleciona todas as tags a, os links.
	var linksBtn = gEs('a');
	// Faz um loop por todos (links)
	for (var x = 0; x < linksBtn.length; x++) {
		// Cada link em si
		var linkBtn = linksBtn[x];
		// Cria uma vairável - atributoRel - com o valor do atributo rel do link
		var atributoRel = new String(linkBtn.getAttribute('rel'));
		// Verifico se o link será para edição dos dados
		if (atributoRel.substring(0,9) == 'btnEditar') {
			linkBtn.onclick = function() {
				// Pego o ID do registro, que coloquei no atributo rel
				// Se fazer sem a palavra-chave this, o script sempre pegará o último da lista
				// O this neste caso é IMPORTANTISSIMO
				var ID = this.getAttribute('rel').split('-')[1];
				// Executa a função que cria o fundo sobre página
				exibirBgBody();
				// Cria um div - definida como boxCad - que armazenará o formulário de cadastro
				boxCad();
				// Inicia o Ajax, atravÃ©s da variável Ajax
				var ajax = openAjax();
				// A tag bgBody conterá o formulário de cadastro
				var recipiente = gE('boxCad');
				// Informamos o mÃ©todo e a página que será requisitada
				ajax.open('GET', 'formulario.php?ajax=true&editar=true&ID=' + ID, true); 
				// bla
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 1) {
						// Cria o efeito de loading
						loading(true);	
					} // if->readyState->1
					if (ajax.readyState == 4) {
						if (ajax.status == 200) {
							// Remove o efeito de loading
							loading(false);
							// Pega o conteÃºdo - HTML - da página requisitada: formulario.php?ajax=true e coloca dentra da div definida na variável recipiente
							recipiente.innerHTML = ajax.responseText;
							// Chama a função que trabalha sobre os botÃµes de Ok e Cancelar
							btnOkBtnCancelar();
							// Seta o focus no campo nome do cadastro
							focusNome();
						} // if-status->200
					} // if->readyState->4
				} // ajax->onreadystatechange
				// Envia a requisição
				ajax.send(null);
				// Evita o reload da página
				return false;
			} // linkBtn.onclick
		} // if->btnEditar
		// Verifico se o link será para exclusão de registro
		if (atributoRel.substring(0,10) == 'btnExcluir') {
			linkBtn.onclick = function() {
				// Pego o ID do registro, que coloquei no atributo rel
				var ID = this.getAttribute('rel').split('-')[1];
				// Confirma a exclusão antes de executá-la.
				var confirma = confirm('Você realmente deseja excluir este contato?');
				// Se realmente quiser excluir
				if (confirma == true) {
					// Inicia o Ajax, atravÃ©s da variável Ajax
					var ajax = openAjax();
					// Informamos o mÃ©todo e a página que será requisitada
					ajax.open('GET', 'actions.php?ajax=true&excluir=true&ID=' + ID, true); 
					// bla
					ajax.onreadystatechange = function() {
						if (ajax.readyState == 4) {
							if (ajax.status == 200) {
								// Executa a função que cria o fundo sobre página
								exibirBgBody();
								// Atualiza a tabela de raltÃ³rio, agora sem o registro excluÃ­do
								atualizaRelatorio();
							} // if-status->200
						} // if->readyState->4
					} // ajax->onreadystatechange
					// Envia a requisição
					ajax.send(null);
				} // if->confirma->true
				// Evita o reload da página
				return false;
			} // linkBtn.onclick
		} // if->btnExcluir
	} // for
}

// Esta função elimina da página o fundo criado sobre o body e o boxCad;
function removerDivs() {
	var bgBody = gE('bgBody');
	var boxCad = gE('boxCad');
	bgBody.parentNode.removeChild(bgBody);
	if (boxCad) { // Por que ao clicar X (para deletar um registro) cria-se somente o encobridor e não o boxCad	
		boxCad.parentNode.removeChild(boxCad);
	}
}

/* FunÃ§Ãµes de terceiros */
// getPageSize()
// Returns array with page width, height and window width, height
// Core code from - quirksmode.org
// Edit for Firefox by pHaez
//
function getPageSize(){
	
	var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}

	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 

}