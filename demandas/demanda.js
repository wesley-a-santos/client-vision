/* global returnType, ClassicEditor */

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    ClassicEditor.create(document.querySelector('#Detalhamento'))
            .catch(error => {
                console.error(error);
            });

//    $('#btn-cadastrar').on('click', function () {
//
//        $("#form_CadastrarDemanda").submit();
//
//    });

    $("#form_CadastrarDemanda").submit(function (event) {

        $('#ModalMensagemConfirmacao').modal('hide');

        if ($("#form_CadastrarDemanda")[0].checkValidity() === false) {
            $(this).addClass('was-validated');
            return false;
        }

        $(this).removeClass('was-validated');

        event.preventDefault();

        $.ajax({
            type: "POST",
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Dados) {

                var MensagemRetorno = '<div class="alert alert-warning" role="alert"><h4 class="alert-heading">Aviso!</h4><p>Ocorreu um erro no processamento da informação!</p></div>';

                if (Dados.Retorno !== null) {
                    MensagemRetorno = '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Sucesso!</h4><p>Informação cadastrada com sucesso!</p></div>';
                }

                $("#ExibeMensagemRetorno").html(MensagemRetorno);
                $('#ModalMensagemRetorno').modal('show');
            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        }); //$.ajax

    }); //$("form").on('submit', function (event) {

    var options = {
        onKeyPress: function (cpf, e, field, options) {
            var masks = ['000.000.000-009', '00.000.000/0000-00'];
            var mask = (cpf.length > 14) ? masks[1] : masks[0];
            $('#Documento').mask(mask, options);
        }};

    $('#Documento').mask('00.000.000/0000-00', options);


    $('#Documento').cpfcnpj({
        mask: true,
        validate: 'cpfcnpj',
        event: 'blur',
        handler: '#Documento',
        ifValid: function (input) {

            $("#Documento").removeClass('invalid-input-documento');
            $("#invalid-documento").css("display", "none");
            pesquisaDocumento();
            $('#btn-cadastrar').prop('disabled', false);

            if (returnType === 'cpf') {
                $("#Tipo").val('F');
            } else {
                $("#Tipo").val('J');
            }

        },
        ifInvalid: function (input) {
            $('#form_CadastrarDemanda').find('#Nome').focus();
            $("#Documento").addClass('invalid-input-documento');
            $("#invalid-documento").css("display", "block");
            $('#btn-cadastrar').prop('disabled', true);
        }

    });

    function pesquisaDocumento() {

        var Documento = $.trim($("#Documento").val());
        var TipoPesquisa = $.trim($("#TipoPesquisa").val());

        $('.btn_Proximo').css("display", "none");
        //$('.load').css("display","none");

        if ($.trim(Documento) === '') {
            return false;
        }

        $.ajax({
            type: "POST",
            cache: false,
            dataType: "json",
            url: "../dados/clientes.php",
            data: $.param({
                Documento: Documento,
                TipoPesquisa: TipoPesquisa
            }),
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Dados) {

                if (Dados.ClienteID !== null) {
                    $("#ClienteID").val(Dados.ClienteID);
                    $("#Nome").val(Dados.Nome);
                    $('#form_CadastrarDemanda').find('#Contrato').focus();
                } else {
                    $('#form_CadastrarDemanda').find('#Nome').focus();
                }


            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        });

    }




    $('#Contrato').on('blur', function () {

        var Contrato = $.trim($("#Contrato").val());
        $('#Contrato').mask('0#');
        $("#Contrato").prop('maxlength', 19);

        $('#CodigoProduto').attr('required', false);

        if (Contrato === '') {
            return false;
        }

        $('#CodigoProduto').attr('required', true);

        $.ajax({
            type: "POST",
            cache: false,
            dataType: "json",
            url: "../dados/contratos.php",
            data: $.param({
                Contrato: Contrato
            }),
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Dados) {

                if (Dados.ContratoID !== null) {
                    $("#CodigoProduto").val(Dados.CodigoProduto);
                    $("#Produto").val(Dados.Produto);
                    $('#Contrato').mask(Dados.Mascara);
                    $('#form_CadastrarDemanda').find('#CodigoUnidade').focus();
                } else {
                    $('#form_CadastrarDemanda').find('#CodigoProduto').focus();
                }

            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        });

    });

    $('#CodigoProduto').on('blur', function () {

        var CodigoProduto = $.trim($("#CodigoProduto").val());
        $('#Contrato').mask('0#');
        $("#Contrato").prop('maxlength', 19);

        if (CodigoProduto === '') {
            return false;
        }

        $.ajax({
            type: "POST",
            cache: false,
            dataType: "json",
            url: "../dados/produtos.php",
            data: $.param({
                CodigoProduto: CodigoProduto
            }),
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Dados) {

                $("#Produto").val(Dados.Nome);
                $('#Contrato').mask(Dados.Mascara);
                $('#form_CadastrarDemanda').find('#CodigoUnidade').focus();
                if (Dados.ProdutoID === null) {
                    $("#Produto").val('Produto Inválido');
                    $('#CodigoProduto').val('');
                    $('#form_CadastrarDemanda').find('#CodigoProduto').focus();
                }



            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        });

    });


    $('#CodigoUnidade').on('blur', function () {

        var CodigoUnidade = $.trim($("#CodigoUnidade").val());


        if (CodigoUnidade === '') {
            return false;
        }

        $.ajax({
            type: "POST",
            cache: false,
            dataType: "json",
            url: "../dados/unidades.php",
            data: $.param({
                CodigoUnidade: CodigoUnidade
            }),
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Unidade) {

                if (Unidade.CodigoUnidadeID !== null) {
                    $("#NomeUnidade").val(Unidade.Nome);
                }
                $('#form_CadastrarDemanda').find('#TipoServicoID').focus();
            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        });

    });


});