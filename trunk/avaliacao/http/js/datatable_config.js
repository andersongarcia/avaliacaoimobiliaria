$(function() {
    $('.listagem').dataTable({
        "bJQueryUI": true,
        "oLanguage": {
            "sUrl": "language/pt_BR.txt"
        },
        "bStateSave": true,
        "bSortClasses": false,
        "sPaginationType": "full_numbers"
    });
});
