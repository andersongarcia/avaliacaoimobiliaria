$(document).ready(function(){  
    $( "input[type='submit'], input[type='button'], a, button", ".actions" ).button();
        
    /*$("#alterarFoto").fileUpload({
        'uploader': 'images/uploader.swf',
        'cancelImg': 'images/cancel_1.png',
        'folder': 'files',
        'script': 'upload.php',
        'buttonText': 'Alterar Foto',
        'scriptData' : {
            'imovel': $('#imovel_id').val()
            },
        'fileDesc': 'Image Files',
        'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
        'auto': true,
        'onComplete': function(event, queueID, fileObj, response, data){
            $("#alterarFoto").fileUploadClearQueue();
            atualizarFoto($('#imovel_id').val(), fileObj.type);
        }
    });*/
  
    $("ul.subnav").parent().append(""); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)  
  
    $("ul.topnav li.drop").click(function() { //When trigger is clicked...  
  
        //Following events are applied to the subnav itself (moving subnav up and down)  
        $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click  
  
        $(this).parent().hover(function() {  
            }, function(){  
                $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up  
            });  
  
    //Following events are applied to the trigger (Hover events for the trigger)  
    }).hover(function() {  
        $(this).addClass("subhover"); //On hover over, add class "subhover"  
    }, function(){  //On Hover Out  
        $(this).removeClass("subhover"); //On hover out, remove class "subhover"  
    });  
    
    $('#selectAll').click(function() {
        if(this.checked == true){
            $("input[type=checkbox].melhor").each(function() { 
                this.checked = true; 
            });
        } else {
            $("input[type=checkbox].melhor").each(function() { 
                this.checked = false; 
            });
        }
    });
    
    $('#frentes_multiplas').click(function() {
        if(this.checked == true) {
            $('#FMHide').show();
            $('#FMHide2').show();
        } else {
            $('#FMHide').hide();
            $('#FMHide2').hide();
        }
    });
    
    $('#areas_secundarias_add').click(function() {
        alterarM();
        $.post("actions.php",
        {
            area_secundaria_add: true
        },
        function(data) {
            $('#areas_secundarias_conteudo').append(data);
        },
        'html');
        return false;
    });
});

function atualizarFoto(name, type){
    var name2 = (name == 0) ? 'tmp'+type : name+type;
    $('#foto_url').val(name2);
    $('#picture').html('<img class="foto" src="files/'+name2+'?' + Math.random() * 1000 + '" width="100%" />');
}

function alterarM() {
    $('#alterou_m').val(1);
}

function carregar(tipo){
    var classe = tipo;
    $.post("actions.php",
    {
        classe: classe,
        show: true
    },
    function(data) {
        $('#dialog-conteudo').html(data);
        $( "#dialog" ).dialog( "open" );
    },
    'html');
    return false;
}

function openSearch(id, avaliacao) {
    var part = $('#busca2').val();
    var dc;
    var d;
    if (part == 2) {
        dc = '#dialog-conteudo3';
        d = '#dialog3';
    } else {
        dc = '#dialog-conteudo2';
        d = '#dialog2';
    }
    
    $.post("avaliacoes_actions.php",
    {
        parte: part,
        imovel: id,
        classe: 'Avaliacao',
        aval: avaliacao,
        show: true
    },
    function(data) {
        $(dc).html(data);
        $(d).dialog( "open" );
    },
    'html');
    return false;
}

function mudarSelecao(id) {
    var avaliacao = $("#avaliacao").val();
    classe = 'div.selecao'+id;
    ident  = '#selecao'+id;
    flag = $(ident).val();
    
    if (flag > 0) {
        $(classe+" td").css({
            color: "#000", 
            background: "#FFF"
        });
        $(ident).attr('value', 0);
        
        $.post("avaliacoes_actions.php",
        {
            avaliacao: avaliacao,
            imovel: id,
            acao: 'remover'
        },
        function(data) {
            $(id).html(data);
        },
        'html');
        return false;

    } else {
        $(classe+" td").css({
            color: "#FFF", 
            background: "#444"
        });
        $(ident).attr('value', 1);
        
        $.post("avaliacoes_actions.php",
        {
            avaliacao: avaliacao,
            imovel: id,
            acao: 'inserir'
        },
        function(data) {
            $(id).html(data);
        },
        'html');
        return false;

    }
}

function carregarSelect(tipo, last) {
    var id = 'span#'+tipo+'_select';
    $.post("actions.php",
    {
        classe: tipo,
        id: last,
        load: true
    },
    function(data) {
        $(id).html(data);
    },
    'html');
    return false;
}

$(function() {
    $( "#dialog" ).dialog({
        autoOpen: false,
        resizable: false,
        width: 400,
        modal: true,
        buttons: {
            "Enviar": function() {
                enviar();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog2" ).dialog({
        autoOpen: false,
        resizable: false,
        width: 960,
        modal: true,
        buttons: {
            "Filtro": function() {
                filtrar();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog3" ).dialog({
        autoOpen: false,
        resizable: false,
        width: 960,
        modal: true,
        buttons: {
            "Avaliar": function() {
                avaliar();
            },
            "Filtro": function() {
                filtrar();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#divExcluir" ).dialog({
        autoOpen: false,
        resizable: false,
        width: 400,
        height: 200,
        modal: true,
        buttons: {
            "Excluir": function() {
                excluir();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });

    $( "#sucesso" ).dialog({
        autoOpen: false,
        resizable: false,
        height: 200,
        width: 400,
        modal: true,
        buttons: {
            "Fechar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
});

function confirmaExlusao(id) {
    var tipo = $("#tipoDeObjeto").val();
    
    $.post("exclusoes.php",
    {
        tipo: tipo,
        id: id
    },
    function(data) {
        $('#divExcluir').html(data);
        $("#divExcluir").dialog( "open" );
    },
    'html');
    return false;
}

function excluir() {
    var tipo = $("#tipoDeObjeto").val();
    var id   = $("#idDoObjeto").val();
    window.location.href = 'avaliacoes.php?excluir=1&id='+id;
    return false;
}

function avaliar() {
    window.location.href = 'avaliacoes.php?show=1&imovel='+$("#imovel").val();
}

function filtrar() {
    var part = $('#busca2').val();
    var dial = (part == 2) ? '#dialog3' : '#dialog2';
    $(dial).dialog("close");
    
    $.post("avaliacoes_actions.php",
    {
        classe: 'filtro',
        parte: part
    },
    function(data) {
        $('#dialog-conteudo').html(data);
        $( "#dialog" ).dialog( "open" );
    },
    'html');
    return false;
}

function enviar(){
    var classe = $("input#classe").val();
    var dataString;
    var descricao;
    var codigo;
    var nome;
    
    if (classe == 'filtro') {
        var part = $('#busca2').val();
        var dc;
        var d;
        if (part == 2) {
            dc = '#dialog-conteudo3';
            d = '#dialog3';
        } else {
            dc = '#dialog-conteudo2';
            d = '#dialog2';
        }
        $( "#dialog" ).dialog( "close" );
        
        var imovel     = $("input#imovel").val();
        var municipio  = $("select#municipios").val();
        var setor      = $("input#setor").val();
        var modalidade = $("input#modalidade").val();
        var tipoImovel = $("select#imovel_tipo").val();
        var dataIni    = $("input#dataIni").val();
        var dataFim    = $("input#dataFim").val();
        
        $.post("avaliacoes_actions.php",
        {
            parte: part,
            classe: 'filtro',
            municipio: municipio,
            setor: setor,
            modalidade: modalidade,
            tipoImovel: tipoImovel,
            dataIni: dataIni,
            dataFim: dataFim,
            imovel: imovel,
            show: true
        },
        function(data) {
            $(dc).html(data);
            $(d).dialog( "open" );
        },
        'html');
        return false;
    }
        
    if (classe == 'imovel_tipo') {
        descricao       = $("input#descricao").val();
        var forma       = $("input#forma").val();
        var vg          = $("input#vagas_de_garagem").val();
        var wc          = $("input#wcs").val();
        var suites      = $("input#suites").val();
        var dormitorios = $("input#dormitorios").val();

        dataString = 'descricao='+ descricao + '&forma=' + forma + '&vagas_de_garagem=' + vg + '&wcs=' + wc + '&suites=' + suites + '&dormitorios=' + dormitorios + '&classe=' + classe + '&ajax=true';
    }
    if (classe == 'municipio') {
        nome = $("input#nome").val();

        dataString = 'nome='+ nome + '&classe=' + classe + '&ajax=true';
    }
    if (classe == 'zona') {
        codigo    = $("input#codigo").val();
        descricao = $("input#descricao").val();

        dataString = 'codigo='+ codigo + '&descricao='+ descricao + '&classe=' + classe + '&ajax=true';
    }
    if (classe == 'topografia') {
        descricao = $("input#descricao").val();

        dataString = 'descricao='+ descricao + '&classe=' + classe + '&ajax=true';
    }
    if ((classe == 'padrao1')||(classe == 'padrao2')) {
        codigo    = $("input#codigo").val();
        descricao = $("input#descricao").val();

        dataString = 'codigo='+ codigo + '&descricao='+ descricao + '&classe=' + classe + '&ajax=true';
    }
    if (classe == 'estado_conservacao') {
        var classificacao = $("input#classificacao").val();
        descricao         = $("input#descricao").val();

        dataString = 'classificacao='+ classificacao + '&descricao='+ descricao + '&classe=' + classe + '&ajax=true';
    }
    if (classe == 'imobiliaria') {
        nome         = $("input#nome").val();
        var endereco = $("input#endereco").val();
        var contato  = $("input#contato").val();
        var fone     = $("input#fone").val();

        dataString = 'nome='+ nome + '&endereco=' + endereco + '&contato=' + contato + '&fone=' + fone + '&classe=' + classe + '&ajax=true';
    }

    $.ajax({
        type: "POST",
        url: "actions.php",
        data: dataString,
        success: function(retorno) {
            $( "#dialog" ).dialog( "close" );
            $( "#sucesso" ).dialog( "open" );
            if (classe == 'padrao1') {
                carregarSelect(classe, retorno);
                carregarSelect('padrao2', 0);
            } else if (classe == 'padrao2') {
                carregarSelect(classe, retorno);
                carregarSelect('padrao1', 0);
            } else {
                carregarSelect(classe, retorno);
            }
        }
    });
};


function clearSelection() {
    var sel = window.getSelection ? window.getSelection() : document.selection;
    if (sel) {
        if (sel.removeAllRanges) {
            sel.removeAllRanges();
        } else if (sel.empty) {
            sel.empty();
        }
    }
}        
