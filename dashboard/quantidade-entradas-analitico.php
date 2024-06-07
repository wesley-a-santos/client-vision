<?php

use Classes\Entity\Demanda;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\LDAP\Usuario;
use Classes\Validacao\ValidarData;
use Classes\Validacao\ValidarInput;

require_once __DIR__ . '/../vendor/autoload.php';

$Usuario = new Usuario();

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$Data = new \DateTime();

if ((ValidarInput::validarInputPost('Data')) && (ValidarData::validarData(filter_input(INPUT_POST, 'Data', FILTER_DEFAULT), 'd/m/Y'))) {
    $Data = DateTime::createFromFormat('d/m/Y', filter_input(INPUT_POST, 'Data', FILTER_DEFAULT));
}

$PaginaAtual = 1;
$MaxResults = 100;

if (!is_null(filter_input(INPUT_POST, 'Pagina', FILTER_VALIDATE_INT))) {
    $PaginaAtual = filter_input(INPUT_POST, 'Pagina', FILTER_VALIDATE_INT);
}

$ListarEntradas = $EntityManager->getRepository(Demanda::class)->listarEntradas(
        $Data,
        $PaginaAtual,
        $MaxResults
);

$TotalItems = count($ListarEntradas);
$PagesCount = ceil($TotalItems / $MaxResults);

$RegistroInicial = (($MaxResults * ($PaginaAtual - 1)) + 1);
$RegistroFinal = ((($RegistroInicial + $MaxResults) - 1) > $TotalItems ? $TotalItems : (($RegistroInicial + $MaxResults) - 1));

$PaginaAnterior = $PaginaAtual - 1;
$ProximaPagina = $PaginaAtual + 1;

$AriaDisabledPaginaAnterior = ($PaginaAnterior <= 0 ? ' tabindex="-1" aria-disabled="true" ' : '');
$PaginaAnteriorDisabled = ($PaginaAnterior <= 0 ? ' disabled ' : '');

$AriaDisabledProximaPagina = ($ProximaPagina > $PagesCount ? ' tabindex="-1" aria-disabled="true" ' : '');
$ProximaPaginaDisabled = ($ProximaPagina > $PagesCount ? ' disabled ' : '');

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

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Demandas Importadas</h1>
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
                <div class="card-header text-white bg-primary">
                    Lista de Demandas Incluídas - <?php echo $Data->format('d/m/Y'); ?>
                    <button type="button" class="btn btn-sm btn-secondary float-right" id="VoltarSintetico">
                        Retornar Sintético
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm text-center">
                            <?php echo "Exibindo item {$RegistroInicial} a {$RegistroFinal} de {$TotalItems} registros"; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm text-center">
                            <nav aria-label="paginacao">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php echo $PaginaAnteriorDisabled; ?>">
                                        <a class="page-link" href="#"
                                        <?php echo $AriaDisabledPaginaAnterior; ?>
                                           data-pagina="1">Primeiro</a>
                                    </li>
                                    <li class="page-item <?php echo $PaginaAnteriorDisabled; ?>">
                                        <a class="page-link" href="#"
                                        <?php echo $AriaDisabledPaginaAnterior; ?>
                                           data-pagina="<?php echo $PaginaAnterior ?>">Anterior</a>
                                    </li>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">
                                            <?php echo "Página {$PaginaAtual} de {$PagesCount} " ?>
                                        </a>
                                    </li>
                                    <li class="page-item <?php echo $ProximaPaginaDisabled; ?>">
                                        <a
                                            class="page-link" href="#"
                                            <?php echo $AriaDisabledProximaPagina; ?>
                                            data-pagina="<?php echo $ProximaPagina; ?>">Próximo</a>
                                    </li>
                                    <li class="page-item <?php echo $ProximaPaginaDisabled; ?>">
                                        <a class="page-link" href="#"
                                        <?php echo $AriaDisabledProximaPagina; ?>
                                           data-pagina="<?php echo $PagesCount; ?>">Último</a>
                                    </li>

                                </ul>
                            </nav>

                        </div>

                    </div>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>

                                <th>Cliente</th>
                                <th>Contrato</th>
                                <th>Serviço</th>
                                <!--<th>Data Inclusão</th>-->
                                <th>Sistema Origem</th>
                                <th>Visualizar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ListarEntradas as $Entrada) : ?>


                                <?php
//                                $Entrada = new Demanda();
                                ?>
                                <tr>

                                    <td><?php
                                        echo FormatarValor::formatarDocumento(
                                                $Entrada->getCliente()->getDocumento(),
                                                $Entrada->getCliente()->getTipo()
                                        )
                                        . '<br>'
                                        . $Entrada->getCliente()->getNome();
                                        ?></td>

                                    <td><?php
                                        echo FormatarValor::formatarValor(
                                                $Entrada->getContrato()->getNumero(),
                                                $Entrada->getContrato()
                                                        ->getProduto()
                                                        ->getSistema()
                                                        ->getMascara())
                                        . '<br>'
                                        . $Entrada->getContrato()->getProduto()->getCodigo()
                                        . ' - '
                                        . $Entrada->getContrato()->getProduto()->getNome();
                                        ?></td>
                                    <td><?php echo $Entrada->getTipoServico()->getTipo(); ?></td>
                                    <!-- <td><?php // echo $Entrada->getDataInclusao()->format('d/m/Y'); ?></td>-->
                                    <td><?php echo $Entrada->getSistemaOrigem()->getSistema(); ?></td>
                                    <td><i class="far fa-eye"></i></td>

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
<form action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" method="post" id="frm-Paginas">
    <input type="hidden" id="Pagina" name="Pagina" value="">
    <input type="hidden" id="Data" name="Data" value="<?php echo $Data->format('d/m/Y'); ?>">
</form>
<form action="quantidade-entradas.php" method="post" id="frm-Voltar">
    <input type="hidden" id="DataInicio" name="DataInicio" value="<?php echo filter_input(INPUT_POST, 'DataInicio', FILTER_DEFAULT); ?>">
    <input type="hidden" id="DataFim" name="DataFim" value="<?php echo filter_input(INPUT_POST, 'DataFim', FILTER_DEFAULT); ?>">
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

        $("a.page-link").on("click", function (event) {
            event.preventDefault();
            $("#Pagina").val($(this).attr('data-pagina'));
            $('#frm-Paginas').submit();
            ;
        });
        $("#VoltarSintetico").on("click", function (event) {
            event.preventDefault();
            $('#frm-Voltar').submit();
            ;
        });
        
        

        $('.datatables').DataTable({
            "language": {
                "decimal": ",",
                "thousands": ".",
                "url": "/js/Datatables-Portuguese-Brasil.lang.json"
            }
        });
    }
    );
</script>