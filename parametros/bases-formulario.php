<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Informacao;
use Classes\Entity\TipoInformacao;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;
use Classes\Validacao\ValidarInput;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Layout::getHead();

Layout::getMenu();

$GrupoBaseID = '';
$Grupo = '';

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

if (ValidarInput::validarInputGet('GrupoBaseID')) {

    $Base = $EntityManager->getRepository(\Classes\Entity\GrupoBase::class)->find(filter_input(INPUT_GET, 'GrupoBaseID', FILTER_VALIDATE_INT));

    if (!is_null($Base)) {

        $GrupoBaseID = $Base->getID();
        $Grupo = $Base->getGrupo();
    }
}
?>

<main role="main" class="flex-shrink-0">
    <div class="container pt-5 pb-5">

        <h3 class="mb-5 text-center">Grupos de Bases</h3>

        <form action="base.php" method="post" class="needs-validation" novalidate="novalidate" id="form_CadastrarInformacao">

            <input type="hidden" name="GrupoBaseID" id="GrupoBaseID" value="<?php echo $GrupoBaseID; ?>">

            <div class="form-row">

                <div class="form-group col">
                    <label for="Grupo">Grupo <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Grupo" id="Grupo" maxlength="75" required="required" value="<?php echo $Grupo; ?>">
                    <div class="invalid-feedback">Favor preencher o nome do cliente.</div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary" id="btn-cadastrar">Gravar</button>
            <button type="reset" class="btn btn-primary">Limpar</button>
        </form>

    </div>
</main>

<!-- Modal -> Processando -->
<div class="modal" id="ModalProcessando" tabindex="-1" role="dialog" aria-labelledby="ModalProcessandoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalProcessandoTitle">Processando. Por favor, aguarde.</h5>
            </div>
            <div class="modal-body text-center">
                <img alt="Processando..." src="/img/icones/loading-gears-animation-10.gif">
            </div>
        </div>
    </div>
</div>


<!-- Modal - Mensagem Retorno -->
<div class="modal fade" id="ModalMensagemRetorno" tabindex="-1" role="dialog" aria-labelledby="ModalMensagemRetornoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalMensagemRetornoTitle">Cadastrar Informação do Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ExibeMensagemRetorno"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php Layout::getFoot(); ?>
<script type="text/javascript" src="base.js"></script>

<?php Layout::getFim(); ?>