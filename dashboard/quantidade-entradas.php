<?php

use Classes\BancoDeDados\Select;
use Classes\Formatacao\FormatarValor;
use Classes\LDAP\Usuario;
use Classes\Validacao\ValidarData;
use Classes\Validacao\ValidarInput;

require_once __DIR__ . '/../vendor/autoload.php';


$Usuario = new Usuario();

$Query = 'SELECT COUNT(DemandaID) AS Quantidade, DataInclusao FROM dbo.Demandas
WHERE (DataInclusao BETWEEN :DataInicio AND :DataFim) GROUP BY DataInclusao
ORDER BY DataInclusao DESC';

$Entradas = [];

$DataAtual = new DateTimeImmutable();
$DataFim = $DataAtual->format('Y-m-d');
//Data Inicial = 15 dias atrás
$DataInicio = $DataAtual->sub(new DateInterval('P15D'))->format('Y-m-d');

if ((ValidarInput::validarInputPost('DataInicio')) && (ValidarInput::validarInputPost('DataFim'))) {
    if ((ValidarData::validarData(filter_input(INPUT_POST, 'DataInicio', FILTER_DEFAULT), 'd/m/Y')) && (ValidarData::validarData(filter_input(INPUT_POST, 'DataFim', FILTER_DEFAULT), 'd/m/Y'))) {
        $DataInicio = FormatarValor::formatarData('d/m/Y', 'Y-m-d', filter_input(INPUT_POST, 'DataInicio', FILTER_DEFAULT));
        $DataFim = FormatarValor::formatarData('d/m/Y', 'Y-m-d', filter_input(INPUT_POST, 'DataFim', FILTER_DEFAULT));
    }
}

$ListaEntradas = new Select();
$ListaEntradas->setQuery($Query);
$ListaEntradas->setParametros('DataInicio', $DataInicio, 'DATE');
$ListaEntradas->setParametros('DataFim', $DataFim, 'DATE');
$ListaEntradas->executar();

if ($ListaEntradas->getRetorno() !== 0) {
    $Entradas = $ListaEntradas->getLista();
}

require_once '__cabecalho.php';
?>

<style>

    .sim {
        color: green;
        font-weight: bold;
    }

    .nao {
        color: red;
        font-weight: bold;
    }
	
	.bg-db{
		background: #005CA9;
	}
	.btn-periodo{
		background: #D0E0E3;
		border-radius: 10px;
		box-shadow:2px 2px 5px #464646;
	}
	.btn-ok{
		background: #005CA9;
		border-radius: 15px;
		box-shadow:2px 2px 5px #464646;	
		color: #D0E0E3;
	}
	.btn-cancel{
		background: #D0E0E3;
		border-radius: 15px;
		box-shadow:2px 2px 5px #464646;	
	}
	.btn-periodo:hover, .btn-ok:hover, .btn-cancel:hover{
		background: #F39200;
	}
 	

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Quantidade de Demandas Importadas</h1>



                </div><!-- /.col -->
                <div class="col-sm-6">
                    <!--
                    <ol class="breadcrumb float-sm-right">
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

            <div class="card h-100">
                <div class="card-header text-white bg-db">Quantidade de Demandas Incluídas - 
                    <?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataInicio); ?>
                    a
                    <?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataFim); ?>

                    <button type="button" class="btn btn-sm btn-periodo float-right" data-toggle="modal" data-target="#ModalAlterarPeriodo">
                        Alterar Período
                    </button>


                </div>
                <div class="card-body">


                    <table class="table table-striped table-bordered table-hover datatables">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Quantidade</th>
                                <th data-orderable="false">Listar</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($Entradas as $Entrada): ?>

                                <tr>
                                    <td class="text-center align-middle">
                                        <?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $Entrada['DataInclusao']); ?>
                                    </td>
                                    <td class="text-center align-middle" data-order="<?php echo $Entrada['Quantidade']; ?>"><?php echo $Entrada['Quantidade']; ?></td>
                                    <td class="text-center align-middle">
                                        <a href="#"
                                           class="analitico"
                                           data-data="<?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $Entrada['DataInclusao']); ?>">
                                            <i class="fas fa-list fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Modal -->
<form action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" method="post" class="needs-validation" novalidate="novalidate" id="frm-pesquisa">
    <div class="modal fade" id="ModalAlterarPeriodo" tabindex="-1" role="dialog" aria-labelledby="ModalAlterarPeriodoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAlterarPeriodoTitle">Período</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-row mb-4">

                        <div class="col">
                            <label for="DataInicio">Data Inicial:</label>
                            <input type="text" class="form-control" id="DataInicio" name="DataInicio"
                                   aria-describedby="informar data inicial da pesquisa" placeholder="dd/mm/aaaa"
                                   maxlength="10" data-mask="00/00/0000" value="<?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataInicio) ?>">
                        </div>

                        <div class="col">
                            <label for="DataFim">Data Final:</label>
                            <input type="text" class="form-control" id="DataFim" name="DataFim"
                                   aria-describedby="informar data inicial da pesquisa" placeholder="dd/mm/aaaa"
                                   maxlength="10" data-mask="00/00/0000" value="<?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataFim) ?>">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-ok" id="btn-pesquisar">Pesquisar</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</form>


<form action="quantidade-entradas-analitico.php" method="post" id="frm-Analitico">
    <input type="hidden" id="Pagina" name="Pagina" value="1">
    <input type="hidden" id="Data" name="Data" value="">
    <input type="hidden" id="DataInicio" name="DataInicio" value="<?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataInicio) ?>">
    <input type="hidden" id="DataFim" name="DataFim" value="<?php echo FormatarValor::formatarData('Y-m-d', 'd/m/Y', $DataFim) ?>">
</form>


<?php
require_once '__rodape.php';
?>

<script type='text/javascript' src='/node_modules/datatables.net/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'></script>
<script type='text/javascript' src='/node_modules/jquery-mask-plugin/dist/jquery.mask.min.js'></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip({html: true});


        $('.datatables').DataTable({
            "language": {
                "decimal": ",",
                "thousands": ".",
                "url": "/js/Datatables-Portuguese-Brasil.lang.json"
            }
        });


        $("a.analitico").on("click", function (event) {
            event.preventDefault();
            $("#Data").val($(this).attr('data-data'));
            $('#frm-Analitico').submit();
            ;
        });
    });
</script>