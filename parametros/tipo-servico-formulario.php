<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Informacao;
use Classes\Entity\TipoInformacao;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;
use Classes\Validacao\ValidarInput;

    

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

Layout::getHead();

Layout::getMenu();

$TipoServicoID = '';
$TipoBaseID = '';
$Tipo = '';
$Descricao = '';

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$GruposBase = $EntityManager->getRepository(Classes\Entity\GrupoBase::class)->findAll();


if (ValidarInput::validarInputGet('TipoServicoID')) {

    $Base = $EntityManager->getRepository(\Classes\Entity\TipoServico::class)->find(filter_input(INPUT_GET, 'TipoServicoID', FILTER_VALIDATE_INT));

    if (!is_null($Base)) {

        $TipoServicoID = $Base->getID();
        $Tipo = $Base->getTipo();
        $TipoBaseID = $Base->getGrupoBase()->getId();
        $Descricao = $Base->getDescricao();
    }
}
?>

<main role="main" class="flex-shrink-0">
    <div class="container pt-5 pb-5">

        <h3 class="mb-5 text-center">Tipo de Serviço</h3>

        <form action="tipo-servico.php" method="post" class="needs-validation" novalidate="novalidate" id="form_CadastrarInformacao">

            <input type="hidden" name="TipoServicoID" id="TipoServicoID" value="<?php echo $TipoServicoID; ?>">

            <div class="form-group row">
                <label for="GrupoBaseID" class="col-sm-2 col-form-label">Grupo Base</label>
                <div class="col-sm-10">
                    <select class="form-control" name="GrupoBaseID" id="GrupoBaseID" required="true">
                        <option value=""> -- Selecione -- </option>
                        <?php foreach ($GruposBase as $Grupo):

						?>							
							<option value="<?php echo $Grupo->getId(); ?>"><?php echo $Grupo->getGrupo(); ?></option>
                        <?php						
						endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Favor selecionar o grupo de bases.</div>
                </div>
            </div>

            <div class="form-group row">
                <label for="Tipo" class="col-sm-2 col-form-label">Tipo *</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="Tipo" id="Tipo" maxlength="75" required="true" value="<?php echo $Tipo; ?>">
                    <div class="invalid-feedback">Favor informar o tipo de atendimento.</div>
                </div>
            </div>
            
            
            <div class="form-group row">
                <label for="Descricao" class="col-sm-2 col-form-label">Descrição</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="Descricao" id="Descricao" rows="3" maxlength="150"><?php echo $Descricao; ?></textarea>
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
<script type="text/javascript" src="tipo-servico.js"></script>

<?php Layout::getFim(); ?>