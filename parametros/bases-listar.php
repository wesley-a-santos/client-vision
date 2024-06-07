<?php
//header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\GrupoBase;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

Layout::getHead();

Layout::getMenu();

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$ListaBases = $EntityManager->getRepository(GrupoBase::class)->findAll();
?>

<main class="flex-shrink-0">
    <div class="container pt-5 pb-5">
        <h3 class="text-center">Grupos Base</h3>
        <div class="text-center mt-3 mb-3">
        <a class="btn btn-primary" href="bases-formulario.php" role="button">Novo</a>
        </div>
        <?php if (ValidarArray::validarArray($ListaBases)): ?>
            <table class="table table-striped table-bordered table-hover">
                <caption>Tabela: Lista de Informações incluídas</caption>
                <thead>
                    <tr>
                        <th scope="col">Grupo</th>
                        <th scope="col" data-orderable="false">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ListaBases as $Base): ?>
                        <tr>
                            <td><?php echo $Base->getGrupo() ?></td>
                            <td class="text-center">
                                <a href="bases-formulario.php?GrupoBaseID=<?php echo $Base->getId() ?>">
                                    <i class="far fa-edit fa-2x svg-azul"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="alert alert-info" role="alert">
                Não existem bases cadastradas.
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