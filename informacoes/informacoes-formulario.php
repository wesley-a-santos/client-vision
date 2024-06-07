<?php
//header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Informacao;
use Classes\Entity\TipoInformacao;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;
use Classes\Validacao\ValidarInput;

Layout::getHead();

Layout::getMenu();

/*
if( ! in_array(self::$Usuario->getCodigoUsuario(), $UsuariosAutorizados)){
	header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
}
*/
$InformacaoID = '';
$ClienteID = '';
$Tipo = '';
$Documento = '';
$Nome = '';
$TipoInformacaoID = '';
$DataValidade = '';
$Permanente = '';
$Descricao = '';
$ReadOnly = '';
$ContratoID = '';
$Contrato = '';
$CodigoProduto = '';
$Produto = '';
$Mascara = '0#';
$Titulo = '';

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();


$ListarTiposInformacoes = $EntityManager->getRepository(TipoInformacao::class)->listar();

if (ValidarInput::validarInputGet('InformacaoID')) {

    $DadosInformacao = $EntityManager->getRepository(Informacao::class)->dadosInformacao(filter_input(INPUT_GET, 'InformacaoID', FILTER_VALIDATE_INT));

    if (!is_null($DadosInformacao)) {

        $InformacaoID = $DadosInformacao->getID();
        $ClienteID = $DadosInformacao->getCliente()->getID();
        $Tipo = $DadosInformacao->getCliente()->getTipo();
        $Documento = FormatarValor::formatarDocumento($DadosInformacao->getCliente()->getDocumento(), $Tipo);
        $Nome = $DadosInformacao->getCliente()->getNome();
        $TipoInformacaoID = $DadosInformacao->getTipoInformacao()->getID();
        $DataValidade = (is_null($DadosInformacao->getDataValidade()) ? '' : $DadosInformacao->getDataValidade()->format('d/m/Y'));
        $Permanente = $DadosInformacao->getPermanente();
        $Descricao = $DadosInformacao->getDescricao();
        $ReadOnly = ' readonly="readonly"';
        $Titulo = $DadosInformacao->getTitulo();

        if (!is_null($DadosInformacao->getContrato())) {
            $ContratoID = $DadosInformacao->getContrato()->getId();
            $Contrato = $DadosInformacao->getContrato()->getNumero();
            $CodigoProduto = $DadosInformacao->getContrato()->getProduto()->getCodigo();
            $Produto = $DadosInformacao->getContrato()->getProduto()->getNome();
            $Mascara = $DadosInformacao->getContrato()->getProduto()->getSistema()->getMascara();
        }
    }
}
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

        <h3 class="mb-5 text-center">Informações do Cliente</h3>


        <form action="informacao.php" method="post" class="needs-validation" novalidate="novalidate" id="form_CadastrarInformacao">

            <input type="hidden" name="InformacaoID" id="InformacaoID" value="<?php echo $InformacaoID; ?>">
            <input type="hidden" name="ClienteID" id="ClienteID" value="<?php echo $ClienteID; ?>">
            <input type="hidden" name="Tipo" id="Tipo" value="<?php echo $Tipo; ?>">
            <input type="hidden" name="TipoPesquisa" id="TipoPesquisa" value="CLI">
            <input type="hidden" name="CodigoUsuario" id="CodigoUsuario" value="<?php echo Layout::getCodigoUsuario(); ?>">



            <div class="form-row">
                <div class="form-group col-3">
                    <label for="Documento">CPF/CNPJ <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Documento" id="Documento<?php echo $Tipo; ?>" maxlength="18" required="required" <?php echo $ReadOnly; ?> value="<?php echo $Documento; ?>">
                    <div class="invalid-feedback">Favor preencher o CPF ou CNPJ do cliente.</div>
                    <div class="invalid-documento" id="invalid-documento">CPF/CNPJ digitado é inválido.</div>
                </div>

                <div class="form-group col">
                    <label for="Nome">Nome <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Nome" id="Nome" maxlength="100" required="required" value="<?php echo $Nome; ?>"<?php echo $ReadOnly; ?>>
                    <div class="invalid-feedback">Favor preencher o nome do cliente.</div>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-3">
                    <label for="Contrato">Contrato</label>
                    <input type="text" class="form-control" name="Contrato" id="Contrato" maxlength="19" value="<?php echo $Contrato; ?>" data-mask="<?php echo $Mascara; ?>">
                </div>

                <div class="form-group col-1">
                    <label for="CodigoProduto">Produto</label>
                    <input type="text" class="form-control" name="CodigoProduto" id="CodigoProduto" maxlength="3" value="<?php echo $CodigoProduto; ?>" data-mask="099">
                    <div class="invalid-feedback">Favor preencher o código do produto/operação.</div>
                </div>

                <div class="form-group col">
                    <label for="Produto">Produto</label>
                    <input type="text" class="form-control" name="Produto" id="Produto" maxlength="75" value="<?php echo $Produto; ?>" readonly="readonly">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col">
                    <label for="TipoInformacaoID">Tipo <span class="obrigatorio">*</span></label>
                    <select class="form-control" name="TipoInformacaoID" id="TipoInformacaoID" required="required">
                        <option value="">-- Selecione --</option>
                        <?php if (ValidarArray::validarArray($ListarTiposInformacoes)) : ?>
                            <?php foreach ($ListarTiposInformacoes as $TipoInformacao): ?>
                                <option value="<?php echo $TipoInformacao->getID(); ?>"<?php echo ($TipoInformacaoID === $TipoInformacao->getID() ? 'selected="selected"' : ''); ?>><?php echo $TipoInformacao->getTipo(); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">Favor selecionar o tipo da informação.</div>
                </div>


                <div class="form-group col">
                    <label for="Permanente">Informação Permanente <span class="obrigatorio">*</span></label>
                    <select class="form-control" name="Permanente" id="Permanente" required="required">
                        <option value="0"<?php echo ($Permanente === false ? ' selected="selected"' : ''); ?>>Não</option>
                        <option value="1"<?php echo ($Permanente === true ? ' selected="selected"' : ''); ?>>Sim</option>
                    </select>
                    <div class="invalid-feedback">Favor selecionar o tipo da informação.</div>
                </div>


                <div class="form-group col">
                    <label for="DataValidade">Validade da Informação <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" placeholder="00/00/0000" name="DataValidade" id="DataValidade"
                           data-mask="00/00/0000" maxlength="10" required="required" value="<?php echo $DataValidade; ?>">
                    <div class="invalid-feedback">Favor preencher a Data de Validade da informação.</div>
                </div>
            </div>

            
            
            <div class="form-group">
                    <label for="Nome">Titulo <span class="obrigatorio">*</span></label>
                    <input type="text" class="form-control" name="Titulo" id="Titulo" maxlength="50" required="required" value="<?php echo $Titulo; ?>">
                    <div class="invalid-feedback">Favor preencher o nome do cliente.</div>
                </div>
            
            
            <div class="form-group">

                <label for="Descricao">Detalhamento da Informação <span class="obrigatorio">*</span></label>
                <textarea class="form-control" name="Descricao" id="Descricao" rows="5" required="required" maxlength="500"><?php echo $Descricao; ?></textarea>
                <div class="invalid-feedback">Favor preencher o Detalhamento da Informação.</div>
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
<script src="/js/jQuery-CPF-CNPJ-Validator-plugin/jquery.cpfcnpj.min.js" type='text/javascript'></script>
<script type="text/javascript" src="informacoes.js"></script>

<?php Layout::getFim(); ?>