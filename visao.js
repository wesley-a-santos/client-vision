$(document).ready(function () {

	// Quando a página estiver totalmente carregada, oculta a div
	$('#processamento').css("display", "none");  
		
	// Quando houver um submit, oculta a div
	$("#frmExcelImport").submit(function() {
		if ($("#file").val() == ''){
			alert("Nenhum arquivo selecionado!");
			return false;
		}	
		$("#processamento").css("display", "block");
		$('#response').css("display", "none");
	});

	
    $('[data-toggle="tooltip"]').tooltip({
        html: true
    });

    $("#myToast").toast({autohide: false});
    $("#myToast").toast('show');

    $("#RespondePesquisa").on('click', function (event) {
        event.preventDefault();
        $("#ModalPesquisa").modal('show');
    });

    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function () {
        $(this).prev(".card-header").find(".fa-plus").addClass("fa-minus").removeClass("fa-plus");
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse").on('show.bs.collapse', function () {
        $(this).prev(".card-header").find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
        $(this).prev(".card-header").find(".fa-plus-square").removeClass("fa-plus-square").addClass("fa-minus-square");
    }).on('hide.bs.collapse', function () {
        $(this).prev(".card-header").find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
        $(this).prev(".card-header").find(".fa-minus-square").removeClass("fa-minus-square").addClass("fa-plus-square");
    });

    $("#frm-feedback").submit(function (event) {

        if ($(this)[0].checkValidity() === false) {
            $(this).addClass('was-validated');
            return false;
        }

        $(this).removeClass('was-validated');

        event.preventDefault();

        var Parametros = {
            CodigoUsuario: $("#CodigoUsuario").val(),
            Informacoes: $("input[name='Informacoes']:checked").val(),
            Layout: $("input[name='Layout']:checked").val(),
            Desempenho: $("input[name='Desempenho']:checked").val(),
            Desoneracao: $("input[name='Desoneracao']:checked").val(),
            Sugestoes: $("#Sugestoes").val()
        };

        var Processando = '<div class="text-center">';
        Processando += '<h5>Processando, por favor, aguarde<\/h5>';
        Processando += '<img alt="Processando..." src="/img/icones/loading-gears-animation-10.gif">';
        Processando += '<\/div>';

        $("#modal-body-feedback").html(Processando);

        $("#btn-gravar").attr('disabled', true);

        $.ajax({
            type: "POST",
            cache: false,
            url: $(this).attr('action'),
            data: $.param(Parametros),
            dataType: "json",
            beforeSend: function () {
                $('#ModalProcessando').modal('show');
            },
            complete: function () {
                $('#ModalProcessando').modal('hide');
            },
            success: function (Dados) {

                if (Dados.Retorno !== null) {

                    $("#modal-body-feedback").html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Obrigado pelo feedback!<\/h4><\/div>');

                    $("#myToast").toast('hide');

                    setTimeout(function () {
                        $('#ModalPesquisa').modal('hide');
                    }, 1500);
                } else {
                    $("#modal-body-feedback").html('<div class="alert alert-warning" role="alert"><h4 class="alert-heading">Aviso!<\/h4><p>Ocorreu um erro no processamento da informação!<\/p><\/div>');
                }
            },
            error: function (txt) {
                $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.<\/div>');
                $('#ModalMensagemRetorno').modal('show');
            }

        }); //$.ajax

    }); //$("form").on('submit', function (event) {


});