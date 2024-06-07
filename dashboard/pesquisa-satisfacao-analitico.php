<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\PesquisaSatisfacaoPiloto;
use Classes\Helper\EntityManagerFactory;
use Classes\LDAP\Usuario;

$Usuario = new Usuario();

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$DataAtual = new \DateTimeImmutable();

$DataFim = $DataAtual;

//Data Inicial = 6 meses atrás
$DataInicio = $DataAtual->sub(new DateInterval('P6M'));

$ListarPesquisas = $EntityManager->getRepository(PesquisaSatisfacaoPiloto::class)->listar($DataInicio, $DataFim);

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
                    <h1 class="m-0 text-dark">Pesquisa de Satisfação</h1>
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
                <div class="card-header text-white bg-primary">Pesquisa de Satisfação - Analitico - Ultimos 6 meses</div>
                <div class="card-body">
                    
                    <div class="row mb-3">
                        
                        <div class="col"><a href="excel/pesquisa-satisfacao-analitico.php" alt="Exportar"><img src="/img/icones/excel.gif" alt="Exportar Excel"></a></div>
                        
                    </div>

                    <table class="table table-striped table-bordered table-hover datatables">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Unidade</th>
                                <th>Data</th>
                                <th>Qualidade das informações</th>
                                <th>Layout do sistema</th>
                                <th>Desempenho do sistema</th>
                                <th>Desoneração</th>
                                <th>Visualiza Criticas/Sugestões</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($ListarPesquisas as $Pesquisa): ?>

                                <tr>
                                    <td><?php echo $Pesquisa['Usuario']['Nome'] . '<br>' . $Pesquisa['Usuario']['CodigoUsuario']; ?></td>
                                    <td><?php echo $Pesquisa['Usuario']['UnidadeCaixa']['TipoUnidade'] . ' ' . $Pesquisa['Usuario']['UnidadeCaixa']['Nome'] . '<br>' . $Pesquisa['Usuario']['UnidadeCaixa']['Codigo']; ?></td>
                                    <td class="text-center align-middle"><?php echo $Pesquisa['DataResposta']->format('d/m/Y'); ?></td>
                                    <td class="text-center align-middle"><?php echo $Pesquisa['Informacoes']; ?></td>
                                    <td class="text-center align-middle"><?php echo $Pesquisa['Layout']; ?></td>
                                    <td class="text-center align-middle"><?php echo $Pesquisa['Desempenho']; ?></td>
                                    <td class="text-center align-middle"><?php echo (($Pesquisa['Desoneracao'] === true ) ? '<span class="sim">Sim</span>' : '<span class="nao">Não</span>'); ?></td>
                                    <td class="text-center align-middle">

                                        <?php if ((trim($Pesquisa['Sugestoes']) === '')): ?>
                                            <i class="fas fa-comment-slash fa-lg"></i>
                                        <?php else: ?>

                                            <a class="Sugestoes" href="" data-alvo="Sugestoes_<?php echo $Pesquisa['Id']; ?>"><i class="far fa-comment-dots fa-lg"></i></a>
                                            <div class="Sugestoes d-none" id="Sugestoes_<?php echo $Pesquisa['Id']; ?>">
                                                <?php echo $Pesquisa['Sugestoes']; ?>
                                            </div>

                                        <?php endif; ?>

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

<!-- Modal Exibe Sugestão -->
<div class="modal fade" id="ModalSugestoes" tabindex="-1" role="dialog" aria-labelledby="ModalSugestoesTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalSugestoesTitle">Criticas / Sugestões</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ExibirSugestoes">

            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-primary">Tratada</button>-->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

            </div>
        </div>
    </div>
</div>

<?php
require_once '__rodape.php';
?>

<script type='text/javascript' src='/node_modules/datatables.net/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip({html: true});

        $('a.Sugestoes').on('click', function (event) {
            event.preventDefault();
            $("#ExibirSugestoes").html($('#' + $(this).attr('data-alvo')).html());
            $('#ModalSugestoes').modal('show');

        });

        $('.datatables').DataTable({
            "language": {
                "decimal": ",",
                "thousands": ".",
                "url": "/js/Datatables-Portuguese-Brasil.lang.json"
            }
        });
    });
</script>