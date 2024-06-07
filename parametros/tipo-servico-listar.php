<?php
//header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
require_once __DIR__ . '/../vendor/autoload.php';

//use Classes\Entity\TipoTipo;
use Classes\Entity\TipoServico;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;

Layout::getHead();

Layout::getMenu();

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$ListaTipos = $EntityManager->getRepository(Classes\Entity\TipoServico::class)->findAll();
?>

<main class="flex-shrink-0">
    <div class="container pt-5 pb-5">
        <h3 class="text-center">Tipos de Serviços</h3>
        <div class="text-center mt-3 mb-3">
        <a class="btn btn-primary" href="tipo-servico-formulario.php" role="button">Novo</a>
        </div>
        <?php if (ValidarArray::validarArray($ListaTipos)): ?>
            <table class="table table-striped table-bordered table-hover">
                <caption>Tabela: Lista de Informações incluídas</caption>
                <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Grupo</th>
                        <th scope="col" data-orderable="false">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ListaTipos as $Tipo): ?>
                        <tr>
                            <td><?php echo $Tipo->getTipo() ?></td>
                            <td><?php echo $Tipo->getGrupoBase()->getGrupo() ?></td>
                            <td class="text-center">
                                <a href="tipo-servico-formulario.php?TipoServicoID=<?php echo $Tipo->getId() ?>">
                                    <i class="far fa-edit fa-2x svg-azul"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="alert alert-info" role="alert">
                Não existem tipos cadastrados.
            </div>

        <?php endif; ?>

    </div>
</main>


<?php Layout::getFoot(); ?>

<script type="text/javascript">

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        $('table').DataTable({
            "language": {
                "decimal": ",",
                "thousands": ".",
                "url": "/js/Datatables-Portuguese-Brasil.lang.json"
            }
        });

    });
</script>

<?php Layout::getFim(); ?>