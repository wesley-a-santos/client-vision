/* global returnType, ClassicEditor */

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $("form").submit(function (event) {

        if ($(this)[0].checkValidity() === false) {
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

});