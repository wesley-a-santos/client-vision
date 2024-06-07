$(document).ready(function () {
 
    $('#ModalAtualizacaoBases').modal('show');
 
    $('[data-toggle="tooltip"]').tooltip();

    var Options = {
        onKeyPress: function (Documento, E, Field, Options) {
            var masks = ['000.000.000-009', '00.000.000/0000-00'];
            var mask = (Documento.length > 14) ? masks[1] : masks[0];
            $('#Documento').mask(mask, Options);
        }
    };

    $('#Documento').mask('00.000.000/0000-00', Options);
    $('#Contrato').mask('0#');

    $('#TipoPesquisa').on('change', function () {
        trocarTipoCampo();
    });

    $("#TipoPesquisa").each(function () {
        trocarTipoCampo();
    });


//    $(window).bind("pageshow", function() {
//  // update hidden input field
//});
 
    function trocarTipoCampo() {

        $('#Contrato').val('');
        $('#Documento').val('');

        if ($('#TipoPesquisa').val() === 'CLI') {
            $('#div_Documento').css({'display': 'block'});
            $('#div_Contrato').css({'display': 'none'});
            $('#Contrato').prop('required', false);
            $('#Documento').prop("required", true);
        } else {
            $('#div_Contrato').css({'display': 'block'});
            $('#div_Documento').css({'display': 'none'});
            $('#Documento').prop('required', false);
            $('#Contrato').prop("required", true);
        }
    }


    $('#Documento').cpfcnpj({
        mask: true,
        validate: 'cpfcnpj',
        event: 'click',
        handler: '#btn-pesquisar',
        ifValid: function (input) {

            input.removeClass("erro-validacao");

            $('#frm-pesquisa').removeClass('was-validated');

            $('.invalido').css("display", "none");

            processarPesquisa();

        },
        ifInvalid: function (input) {

            $('#frm-pesquisa').removeClass('was-validated');

            if ($('#TipoPesquisa').val() === 'CLI') {

                input.addClass("erro-validacao");
                $('.invalido').css("display", "block");
                return;

            }

            processarPesquisa();

        }
    });


    function processarPesquisa() {

        if ($("#frm-pesquisa")[0].checkValidity() === false) {
            $("#frm-pesquisa").addClass('was-validated');
            return false;
        }

        var Parametros = $("#frm-pesquisa").serialize();
        var Acao = 'dados/clientes.php';
        var Mensagem = '';
        var Retorno = '';

        $.ajax({
            type: "POST",
            cache: false,
            url: Acao,
            data: Parametros,
            dataType: "json",
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {

            },
            success: function (Dados) {

                var Contrato = $('#Contrato').val();
                var Documento = $('#Documento').val();

                if ($('#TipoPesquisa').val() === 'CLI') {
                    Mensagem = '<div class="alert alert-warning" role="alert">Nenhum registro encontrado para o CPF/CNPJ ' + Documento + '.</div>';
                } else {
                    Mensagem = '<div class="alert alert-warning" role="alert">Nenhum registro encontrado para o Contrato ' + Contrato + '.</div>';
                }

                if ((Dados.ClienteID === null) && (Dados.ContratoID === null)) {

                    $('#RetornoConsulta').html(Mensagem);
                    $('#ModalProcessando').modal('hide');

                } else {
                    $('#ClienteID').val(Dados.ClienteID);
                    $('#ContratoID').val(Dados.ContratoID);
                    $("#frm-pesquisa").submit();
                }

            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        }); //$.ajax

    }

});