<?php
//header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\TipoServico;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;

Layout::getHead();

Layout::getMenu();

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$ListarTiposServicos = $EntityManager->getRepository(TipoServico::class)->findAll();
$ListarStatusDemandas = $EntityManager->getRepository(Classes\Entity\StatusDemanda::class)->findAll();
$ListarGrausSigilos = $EntityManager->getRepository(Classes\Entity\GrauSigilo::class)->findAll();


?>

<style>
    .invalid-documento {
        display: none;
        width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: #dc3545;
    }

    .invalid-input-documento {
        border-color: #dc3545;
        padding-right: calc(1.5em + .75rem);
        background-repeat: no-repeat;
        background-position: center right calc(.375em + .1875rem);
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }
</style>

<main role="main" class="flex-shrink-0">
    <div class="container pt-5 pb-5">

        <h3 class="mb-5 text-center">Cadastro de Demanda</h3>

        <form action="demanda.php" method="post" class="needs-validation" novalidate="novalidate" id="form_CadastrarDemanda">

            <input type="hidden" name="Tipo" id="Tipo" value="<?php echo $Tipo; ?>">
            <input type="hidden" name="TipoPesquisa" id="TipoPesquisa" value="CLI">
            <input type="hidden" name="CodigoUsuario" id="CodigoUsuario" value="<?php echo Layout::getCodigoUsuario(); ?>">

            <div class="form-row">
                <div class="form-group col-3">
                    <label for="Documento">CPF/CNPJ <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Documento" id="Documento" maxlength="18" required="required">
                    <div class="invalid-feedback">Favor preencher o CPF ou CNPJ do cliente.</div>
                    <div class="invalid-documento" id="invalid-documento">CPF/CNPJ digitado é inválido.</div>
                </div>

                <div class="form-group col">
                    <label for="Nome">Nome <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Nome" id="Nome" maxlength="100" required="required">
                    <div class="invalid-feedback">Favor preencher o nome do cliente.</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-3">
                    <label for="Contrato">Contrato</label>
                    <input type="text" class="form-control" name="Contrato" id="Contrato" maxlength="19" data-mask="0#" required="required">
                </div>

                <div class="form-group col-1">
                    <label for="CodigoProduto">Produto</label>
                    <input type="text" class="form-control" name="CodigoProduto" id="CodigoProduto" maxlength="4" data-mask="0999" required="required">
                    <div class="invalid-feedback">Favor preencher o código do produto/operação.</div>
                </div>

                <div class="form-group col">
                    <label for="Produto">Produto</label>
                    <input type="text" class="form-control" name="Produto" id="Produto" maxlength="75" readonly="readonly">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-2">
                    <label for="CodigoUnidade">Código da Unidade <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="CodigoUnidade" id="CodigoUnidade" maxlength="4" required="required" data-mask="0999">
                    <div class="invalid-feedback">Favor preencher o código da unidade responsável pelo atendimento da demanda.</div>
                </div>

                <div class="form-group col">
                    <label for="NomeUnidade">Unidade de Contratação <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="NomeUnidade" id="NomeUnidade" maxlength="100" readonly="readonly">
                    <div class="invalid-feedback">Favor preencher o nome da unidade responsável pelo atendimento da demanda.</div>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="TipoServicoID">Tipo <span class="obrigatorio">*</span></label>
                    <select class="form-control" name="TipoServicoID" id="TipoServicoID" required="required">
                        <option value="">-- Selecione --</option>
                        <?php if (ValidarArray::validarArray($ListarTiposServicos)) : ?>
                            <?php foreach ($ListarTiposServicos as $TipoServico): ?>
                                <option value="<?php echo $TipoServico->getID(); ?>"><?php echo $TipoServico->getTipo(); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">Favor selecionar o tipo do atendimento prestado.</div>
                </div>

                <div class="form-group col">
                    <label for="StatusDemandaID">Situação da Demanda <span class="obrigatorio">*</span></label>
                    <select class="form-control" name="StatusDemandaID" id="StatusDemandaID" required="required">
                        <option value="">-- Selecione --</option>
                        <?php if (ValidarArray::validarArray($ListarTiposServicos)) : ?>
                            <?php foreach ($ListarStatusDemandas as $StatusDemanda): ?>
                                <option value="<?php echo $StatusDemanda->getID(); ?>"><?php echo $StatusDemanda->getStatus(); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">Favor selecionar a situação de atendimento da demanda.</div>
                </div>

                <div class="form-group col">
                    <label for="SistemaOrigem">Sistema de Origem da Demanda <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="SistemaOrigem" id="SistemaOrigem" maxlength="50" required="required">
                    <div class="invalid-feedback">Favor preencher o Sistema de Origem da Demanda.</div>

                </div>

                <div class="form-group col">
                    <label for="GrauSigiloID">Grau de Sigilo <span class="obrigatorio">*</span></label>
                    <select class="form-control" name="GrauSigiloID" id="GrauSigiloID" required="required">
                        <option value="">-- Selecione --</option>
                        <?php if (ValidarArray::validarArray($ListarTiposServicos)) : ?>
                            <?php foreach ($ListarGrausSigilos as $GrauSigilo): ?>
                                <option value="<?php echo $GrauSigilo->getID(); ?>"><?php echo $GrauSigilo->getGrauSigilo(); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">Favor selecionar o grau de sigilo da informação.</div>
                </div>
            </div>

            <div class="form-group">
                <label for="Detalhamento">Detalhamento da Demanda <span class="obrigatorio">*</span></label>
                <textarea class="form-control" name="Detalhamento" id="Detalhamento" rows="5" required="required" maxlength="500"></textarea>
                <div class="invalid-feedback">Favor preencher o Detalhamento da Demanda.</div>
            </div>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalMensagemConfirmacao">Gravar</button>
            <button type="reset" class="btn btn-primary">Limpar</button>

            <!-- Modal - Mensagem Confirmacao -->
            <div class="modal fade" id="ModalMensagemConfirmacao" tabindex="-1" role="dialog" aria-labelledby="ModalMensagemConfirmacaoTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalMensagemConfirmacaoTitle">Cadastrar Informação do Cliente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="alert alert-warning" role="alert">
                                <p>Confirma a inclusão das informaões?</p>
                                <p>Aviso!!! Após a inclusão elas não poderão ser alteradas.</p>
                            </div>

                        </div>
                        <div class="modal-footer text-center">
                            <button type="submit" class="btn btn-secondary" id="btn-cadastrar">Sim</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        </div>
                    </div>
                </div>
            </div>


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
<script src="/js/jQuery-CPF-CNPJ-Validator-plugin/jquery.cpfcnpj.min.js" type='text/javascript'></script>
<script type="text/javascript" src="demanda.js"></script>

<?php Layout::getFim(); ?>