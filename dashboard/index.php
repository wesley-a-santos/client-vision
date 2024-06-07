<?php
require_once __DIR__ . '/../vendor/autoload.php';

$Usuario = new Classes\LDAP\Usuario();

require_once '__cabecalho.php';
?>



<!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Quadro Geral</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
<!--                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Starter Page</li>
                                </ol>-->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">



<div class="row">

    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Quantidade Mensal de Acessos (Quantidade de Usuários)</div>
            <div class="card-body">
                <canvas id="QuantidadeAcessos"></canvas>
            </div>
        </div>
    </div>

    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Diferença em relação ao mês anterior (%)</div>
            <div class="card-body">
                <canvas id="QuantidadeAcessosPercentual"></canvas>
            </div>
        </div>
    </div>
</div>




<div class="row">

    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Pesquisa de Satisfação (Média Aritmética)</div>
            <div class="card-body">
                <canvas id="PesquisaSatisfacaoMedia"></canvas>
            </div>
        </div>
    </div>

    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Desoneração de Atendimentos (Quantidade)</div>
            <div class="card-body">
                <canvas id="PesquisaSatisfacaoDesoneracao"></canvas>
            </div>
        </div>
    </div>

</div>


<!--<div class="row">
    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Featured</div>
            <div class="card-body">

            </div>
        </div>
    </div>

    <div class="col mb-4">
        <div class="card h-100">

            <div class="card-header text-white bg-primary">Featured</div>
            <div class="card-body">

            </div>
        </div>
    </div>

</div>-->




                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->






<?php require_once '__rodape.php'; ?>

<script>
    $(document).ready(function () {


        (function () {

            /**
             * Ajuste decimal de um número.
             *
             * @param	{String}	type	O tipo de arredondamento.
             * @param	{Number}	value	O número a arredondar.
             * @param	{Integer}	exp		O expoente (o logaritmo decimal da base pretendida).
             * @returns	{Number}			O valor depois de ajustado.
             */
            function decimalAdjust(type, value, exp) {
                // Se exp é indefinido ou zero...
                if (typeof exp === 'undefined' || +exp === 0) {
                    return Math[type](value);
                }
                value = +value;
                exp = +exp;
                // Se o valor não é um número ou o exp não é inteiro...
                if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                    return NaN;
                }
                // Transformando para string
                value = value.toString().split('e');
                value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
                // Transformando de volta
                value = value.toString().split('e');
                return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
            }

            // Arredondamento decimal
            if (!Math.round10) {
                Math.round10 = function (value, exp) {
                    return decimalAdjust('round', value, exp);
                };
            }
            // Decimal arredondado para baixo
            if (!Math.floor10) {
                Math.floor10 = function (value, exp) {
                    return decimalAdjust('floor', value, exp);
                };
            }
            // Decimal arredondado para cima
            if (!Math.ceil10) {
                Math.ceil10 = function (value, exp) {
                    return decimalAdjust('ceil', value, exp);
                };
            }

        })();


        var BackgroundColor = [
            'rgba(26, 188, 156,0.2)',
            'rgba(111, 30, 81,0.2)',
            'rgba(52, 152, 219,0.2)',
            'rgba(155, 89, 182,0.2)',
            'rgba(52, 73, 94,0.2)',
            'rgba(241, 196, 15,0.2)',
            'rgba(230, 126, 34,0.2)',
            'rgba(231, 76, 60,0.2)',
            'rgba(153, 128, 250,0.2)',
            'rgba(149, 165, 166,0.2)',
            'rgba(22, 160, 133,0.2)',
            'rgba(39, 174, 96,0.2)',
            'rgba(41, 128, 185,0.2)',
            'rgba(142, 68, 173,0.2)',
            'rgba(44, 62, 80,0.2)',
            'rgba(243, 156, 18,0.2)',
            'rgba(211, 84, 0,0.2)',
            'rgba(192, 57, 43,0.2)',
            'rgba(27, 20, 100,0.2)',
            'rgba(127, 140, 141,0.2)'
        ];

        var BorderColor = [
            'rgba(26, 188, 156,1.0)',
            'rgba(111, 30, 81,1.0)',
            'rgba(52, 152, 219,1.0)',
            'rgba(155, 89, 182,1.0)',
            'rgba(52, 73, 94,1.0)',
            'rgba(241, 196, 15,1.0)',
            'rgba(230, 126, 34,1.0)',
            'rgba(231, 76, 60,1.0)',
            'rgba(153, 128, 250,1.0)',
            'rgba(149, 165, 166,1.0)',
            'rgba(22, 160, 133,1.0)',
            'rgba(39, 174, 96,1.0)',
            'rgba(41, 128, 185,1.0)',
            'rgba(142, 68, 173,1.0)',
            'rgba(44, 62, 80,1.0)',
            'rgba(243, 156, 18,1.0)',
            'rgba(211, 84, 0,1.0)',
            'rgba(192, 57, 43,1.0)',
            'rgba(27, 20, 100,1.0)',
            'rgba(127, 140, 141,1.0)'
        ];


        var OrigemDados = 'dados/acessos-mensal.php';


        var RotuloDataset = 'Quantidade de Acessos';
        var DataInicio = '';
        var DataFim = '';
        var Canvas = $('#QuantidadeAcessos');
        var Grafico = 'line';

        gerarGrafico(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor[0], BorderColor[0]);


        var Canvas = $('#QuantidadeAcessosPercentual');
        var Grafico = 'line';
        var RotuloDataset = 'Diferença em relação ao mês anterior (%)';
        var DataInicio = '';
        var DataFim = '';

        gerarGrafico(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor[0], BorderColor[0]);




        var OrigemDados = 'dados/media-pesquisa-piloto-itens.php';

        var RotuloDataset = 'Média';
        var DataInicio = '';
        var DataFim = '';
        var Canvas = $('#PesquisaSatisfacaoMedia');
        var Grafico = 'bar';

        gerarGrafico(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor, BorderColor);


        var OrigemDados = 'dados/media-pesquisa-piloto-desoneracao.php';

        var RotuloDataset = 'Quantidade';
        var DataInicio = '';
        var DataFim = '';
        var Canvas = $('#PesquisaSatisfacaoDesoneracao');
        var Grafico = 'bar';

        gerarGrafico(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor, BorderColor);




        function gerarGrafico(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor, BorderColor) {



            var Dados = {
                DataInicio: DataInicio,
                DataFim: DataFim
            };


            $.ajax({
                type: "POST",
                cache: false,
                url: OrigemDados,
                data: $.param(Dados),
                dataType: "json",
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (Retorno) {

                    var RotulosDados = [];
                    var Dados = [];

                    $.each(Retorno, function (key, value) {
                        RotulosDados.push(value.Rotulos);
                        Dados.push(value.Dados);
                    });

                    montarGrafico(Canvas, Grafico, RotuloDataset, RotulosDados, Dados, BackgroundColor, BorderColor);

                },
                error: function (txt) {
                    $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                    $('#ModalMensagemRetorno').modal('show');
                }

            }); //$.ajax

        }

        function gerarGraficoPercentual(OrigemDados, Canvas, Grafico, RotuloDataset, DataInicio, DataFim, BackgroundColor, BorderColor) {


            var Dados = {
                DataInicio: DataInicio,
                DataFim: DataFim
            };

            $.ajax({
                type: "POST",
                cache: false,
                url: OrigemDados,
                data: $.param(Dados),
                dataType: "json",
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (Retorno) {

                    var ValorPadrao = -1;
//                            var ValorPadrao = 0;
                    var ValorAtual = 0;
                    var DiferencaMes = 0;

                    var RotulosDados = [];
                    var Dados = [];

                    $.each(Retorno, function (key, value) {

                        ValorAtual = value.Dados;

                        if (ValorPadrao < 1) {
                            ValorPadrao = ValorAtual;
                        }

                        DiferencaMes = (((ValorAtual / ValorPadrao) * 100) - 100);

                        Dados.push(Math.round10(DiferencaMes, -2));

                        ValorPadrao = value.Dados;

                        RotulosDados.push(value.Rotulos);

                    });

                    montarGrafico(Canvas, Grafico, RotuloDataset, RotulosDados, Dados, BackgroundColor, BorderColor);

                },
                error: function (txt) {
                    $("#ExibeMensagemRetorno").html('<div class="alert alert-danger" role="alert">Ocorreu um erro no processamento de sua solicitação. Tente novamente e, caso o problema persista, contate o suporte.</div>');
                    $('#ModalMensagemRetorno').modal('show');
                }

            }); //$.ajax

        }

        function montarGrafico(Canvas, Grafico, RotuloDataset, RotulosDados, Dados, BackgroundColor, BorderColor) {
            var ctx = Canvas;

            var myChart = new Chart(ctx, {
                type: Grafico,
                data: {
                    labels: RotulosDados,
                    datasets: [{
                            label: RotuloDataset,
                            data: Dados,
                            backgroundColor: BackgroundColor,
                            borderColor: BorderColor,
                            borderWidth: 1
                        }]
                },
                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
                }
            });


        }



//            var ctx = $('#myChart');
//
//          
//
//
//            var ctx2 = $('#myChart2');
//            var myLineChart = new Chart(ctx2, {
//                type: 'line',
//                data: {
//                    labels: [
//                        'Fev/2018',
//                        'Fev/2019',
//                        'Mai/2019',
//                        'Out/2019',
//                        'Jan/2020',
//                        'Fev/2020',
//                        'Mar/2020',
//                        'Mai/2020',
//                        'Jul/2020',
//                        'Dez/2020'
//                    ],
//                    datasets: [{
//                            label: 'Quantidade de acessos',
//                            data: [
//                                640,
//                                700,
//                                87,
//                                50,
//                                30,
//                                87,
//                                1110,
//                                70,
//                                87,
//                                50
//                            ],
//                            backgroundColor: ['rgba(255, 99, 132, 0.2)'],
//                            borderColor: ['rgba(255, 99, 132, 1)']
//
//                        }
//                    ]
//                }
////                options: options
//            });

    });

</script>