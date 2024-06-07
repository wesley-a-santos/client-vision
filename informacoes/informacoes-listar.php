<?php
//header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Informacao;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Validacao\ValidarArray;
use Classes\Sistema\Layout;
 
Layout::getHead();

Layout::getMenu();

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$ListaInformacoes = $EntityManager->getRepository(Informacao::class)->listarInformacoesUsuario(Layout::getCodigoUsuario());
?>

<main class="flex-shrink-0">
    <div class="container-fluid pt-5 pb-5">
        <h3 class="text-center"><?php echo SISTEMA_NOME; ?></h3>

        <?php if (ValidarArray::validarArray($ListaInformacoes)): ?>
            <table class="table table-striped table-bordered table-hover">
                <caption>Tabela: Lista de Informações incluídas</caption>
                <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Contrato</th>
                        <th scope="col">Data Inclusão</th>
                        <th scope="col">Data Alteração</th>
                        <th scope="col">Data Validade</th>
                        <th scope="col" data-orderable="false">Editar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($ListaInformacoes as $Informacao):

                        $InformacaoID = $Informacao->getID();
                        $Titulo = $Informacao->getTitulo();
                        $TipoInformacao = $Informacao->getTipoInformacao()->getTipo();
                        $Cliente = $Informacao->getCliente()->getNome();
                        $Documento = $Informacao->getCliente()->getDocumento();
                        $TipoCliente = $Informacao->getCliente()->getTipo();
                        $Contrato = null;
                        $Mascara = null;
                        if(!is_null($Informacao->getContrato())){
                            $Contrato = $Informacao->getContrato()->getNumero();
                            $Mascara = $Informacao->getContrato()->getProduto()->getSistema()->getMascara();
                        }
                        
                        $DataInclusao = $Informacao->getDataInclusao()->format('d/m/Y');
                        $DataAlteracao = $Informacao->getDataAlteracao()->format('d/m/Y');
                        $DataValidade = (is_null($Informacao->getDataValidade()) ? 'Permanente' : $Informacao->getDataValidade()->format('d/m/Y') );
                        
                        $DataInclusaoOrder = $Informacao->getDataInclusao()->format('Y-m-d');
                        $DataAlteracaoOrder = $Informacao->getDataAlteracao()->format('Y-m-d');
                        $DataValidadeOrder = (is_null($Informacao->getDataValidade()) ? 'Permanente' : $Informacao->getDataValidade()->format('Y-m-d') );
                        
                        $Permanente = $Informacao->getPermanente();
                        ?>

                        <tr>
                            <td><?php echo $TipoInformacao ?></td>
                            <td><?php echo $Titulo ?></td>
                            <td><?php echo $Cliente . '<br>' . FormatarValor::formatarDocumento($Documento, $TipoCliente); ?></td>
                            <td><?php echo FormatarValor::formatarValor($Contrato, $Mascara); ?></td>
                            <td class="text-center" data-order="<?php echo $DataInclusaoOrder; ?>"><?php echo $DataInclusao ?></td>
                            <td class="text-center" data-order="<?php echo $DataAlteracaoOrder; ?>"><?php echo $DataAlteracao ?></td>
                            <td class="text-center" data-order="<?php echo $DataValidadeOrder; ?>"><?php echo $DataValidade ?></td>

                            <td class="text-center"><a href="informacoes-formulario.php?InformacaoID=<?php echo $InformacaoID ?>"><i class="far fa-edit fa-lg svg-azul"></i></a></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php else: ?>

            <div class="alert alert-info" role="alert">
                Você não possui informações cadastradas.
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