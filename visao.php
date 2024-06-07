<?php
require_once __DIR__ . '/vendor/autoload.php';

use Classes\Controller\DemandasController;
use Classes\Entity\Demanda;
use Classes\Entity\Informacao;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;
use Classes\Validacao\ValidarArray;
use Classes\Validacao\ValidarInput;
use Classes\Validacao\ValidarNumero;

Layout::getHead();

Layout::getMenu();

$Demandas = null;

$TipoPesquisa = '';

$DemandasContrato = null;
$Cliente = null;

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();


if (ValidarInput::validarInputPost('ClienteID')) {
    $Demandas = $EntityManager
            ->getRepository(Demanda::class)
            ->listarDemandasCliente(ValidarNumero::validarNumeroInteiro(filter_input(INPUT_POST, 'ClienteID')));

    if (!is_null($Demandas)) {
        $Niveis = DemandasController::getNiveisExibicaoClientes($Demandas);
        $TipoPesquisa = 'CLI';
    }
}

if (ValidarInput::validarInputPost('ContratoID')) {

    $Demandas = $EntityManager
            ->getRepository(Demanda::class)
            ->listarDemandasContrato(ValidarNumero::validarNumeroInteiro(filter_input(INPUT_POST, 'ContratoID')));

    if (!is_null($Demandas)) {
        $Niveis = DemandasController::getNiveisExibicaoContratos($Demandas);
        $TipoPesquisa = 'CON';
    }
}
?>

<main class="flex-shrink-0">
    <div class="container">



        <h3 class="mt-5 text-center"><?php echo SISTEMA_NOME; ?></h3>

        <?php if (($Demandas !== null) && (sizeof($Demandas) > 0)): ?>

            <?php
            if ($TipoPesquisa === "CLI") {
                $Cliente = $Demandas[0];
            } else {
                $Cliente = $Demandas[0]->getClientes()[0];
            }

            $ListarOportunidades = $EntityManager->getRepository(Informacao::class)->listarInformacoesCliente($Cliente->getId(), 1);

            $ListarAvisos = $EntityManager->getRepository(Informacao::class)->listarInformacoesCliente($Cliente->getId(), 2);
            ?>

            <div class="card mt-3 mb-3">
                <div class="card-body text-white"  style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%);" >
                    <div class="d-flex flex-row mb-0">

                        <p class="card-text flex-fill mb-0 text-center" style="font-size: 120%;">Cliente: <?php echo $Cliente->getNome(); ?></p>
                        <p class="card-text flex-fill mb-0 text-center" style="font-size: 120%;"><?php echo ($Cliente->getTipo() === 'F' ? 'CPF' : 'CNPJ' ) . ': ' . FormatarValor::formatarDocumento($Cliente->getDocumento(), $Cliente->getTipo()); ?></p>
                        <!--<?php if ($Cliente->getTipo() === 'F'): ?>
                                                                                                    <p class="card-text flex-fill mb-0 text-center" style="font-size: 120%;">NIS: <?php echo FormatarValor::formatarDocumento($Cliente->getInscricaoSocial(), 'N'); ?></p>
                        <?php endif; ?>-->

                    </div>
                </div>
            </div>

            <?php if (ValidarArray::validarArray($ListarAvisos)): ?>
                <div class="card-deck">

                    <?php $Contador = 1; ?>

                    <?php foreach ($ListarAvisos as $Aviso) : ?>
                        <div class="card  mb-3">
                            <div class="card-header text-white " style="background: linear-gradient(to right, #dc3545 0%,  #F9765E 109%);"><?php echo $Aviso->getTipoInformacao()->getTipo(); ?> </div>
                            <div class="card-body text-dark">
                                <h5><?php echo $Aviso->getTitulo(); ?></h5>
                                <p class="card-text"><?php echo $Aviso->getDescricao(); ?></p>
                            </div>
                        </div>

                        <?php if ((($Contador % 3) === 0) && ($Contador > 1)): ?>
                        </div>
                        <div class="card-deck">
                        <?php endif; ?>

                        <?php $Contador += 1; ?>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <?php if (ValidarArray::validarArray($ListarOportunidades)): ?>
                <div class="card-deck">

                    <?php $Contador = 1; ?>

                    <?php foreach ($ListarOportunidades as $Oportunidade) : ?>
                        <div class="card  mb-3">
                            <div class="card-header text-white" style="background: linear-gradient(to right, #009432 0%,  #A3CB38 109%);"><?php echo $Oportunidade->getTipoInformacao()->getTipo(); ?></div>
                            <div class="card-body text-dark">
                                <h5><?php echo $Oportunidade->getTitulo(); ?></h5>
                                <p class="card-text"><?php echo $Oportunidade->getDescricao(); ?></p>
                            </div>
                        </div>
                        <?php if ((($Contador % 3) === 0) && ($Contador > 1)): ?>
                        </div>
                        <div class="card-deck">
                        <?php endif; ?>

                        <?php $Contador += 1; ?>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="alert alert-warning" role="alert">Cliente não localizado.</div>

        <?php endif; ?>

        <?php if ((ValidarArray::validarArray($Demandas)) && (ValidarArray::validarArray($Niveis))): ?> 


            <div class="card mb-5  p-0" style="border: none;">
                <div class="card-header p-3 text-white" 
                     style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%); 
                     ">

                    Atendimentos
                </div>
                <div class="card-body p-0" style="border: none;">

                    <div class="accordion m-0" id="accordionExample">

                        <?php foreach ($Niveis as $KeyContrato => $NumeroContrato) : ?>

                            <div class="card">
                                <div class="card-header" style="background-color: #fff;" id="heading_c_<?php echo $KeyContrato; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapse_c_<?php echo $KeyContrato; ?>" aria-expanded="true" aria-controls="collapse_c_<?php echo $KeyContrato; ?>">
                                            <?php
                                            echo '<i class="fas fa-plus fa-sm"></i> <span class="font-weight-bolder">Contrato: ' . FormatarValor::formatarValor($NumeroContrato['Contrato'], $NumeroContrato['Mascara']) . '</span>';
                                            ?>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse_c_<?php echo $KeyContrato; ?>" class="collapse" aria-labelledby="heading_c_<?php echo $KeyContrato; ?>" data-parent="#accordionExample">
                                    <div class="card-body">

                                        <?php if (ValidarArray::validarArray($NumeroContrato['TiposServicos'])): ?> 

                                            <div class="accordion" id="accordionExample_c_<?php echo $KeyContrato; ?>">

                                                <?php foreach ($NumeroContrato['TiposServicos'] as $KeyServico => $TipoServico) : ?>

                                                    <div class="card">
                                                        <div class="card-header p-1" id="heading_c_<?php echo $KeyContrato; ?>_t_<?php echo $KeyServico; ?>">
                                                            <h2 class="mb-0">
                                                                <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapse_c_<?php echo $KeyContrato; ?>_t_<?php echo $KeyServico; ?>" aria-expanded="true" aria-controls="collapse_c_<?php echo $KeyContrato; ?>_t_<?php echo $KeyServico; ?>">
                                                                    <?php echo '<i class="far fa-plus-square fa-sm"></i> ' . $TipoServico; ?>
                                                                </button>
                                                            </h2>
                                                        </div>

                                                        <div id="collapse_c_<?php echo $KeyContrato; ?>_t_<?php echo $KeyServico; ?>" class="collapse <?php //echo ($KeyServico === 0 ? 'show' : '');                          ?>" aria-labelledby="heading_c_<?php echo $KeyContrato; ?>_t_<?php echo $KeyContrato; ?>" data-parent="#accordionExample_c_<?php echo $KeyContrato; ?>">
                                                            <div class="card-body">

                                                                <?php foreach ($Demandas as $Cliente) : ?>

                                                                    <?php foreach ($Cliente->getDemandas() as $Demanda) : ?>

                                                                        <?php if (($Demanda->getContrato()->getNumero() === $NumeroContrato['Contrato']) && ($Demanda->getTipoServico()->getTipo() === $TipoServico)) : ?>

                                                                            <div class="card border-exibe mb-3">
                                                                                <div class="card-header text-white bg-exibe d-flex">
                                                                                    <div class="flex-fill text-left font-weight-bold">
                                                                                        Contrato: <?php echo FormatarValor::formatarValor($NumeroContrato['Contrato'], $NumeroContrato['Mascara']); ?>
                                                                                    </div>
                                                                                    <div class="flex-fill text-center font-weight-bold">
                                                                                        Data: <?php echo $Demanda->getDataRegistro()->format('d/m/Y'); ?>
                                                                                    </div>
                                                                                    <div class="flex-fill text-right font-weight-bold">
                                                                                        Situação: <?php echo $Demanda->getStatusDemanda()->getStatus(); ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body text-dark"> 

                                                                                    <div class="card-text">
                                                                                        <strong>Detalhamento:</strong>
                                                                                        <?php echo $Demanda->getDetalhamento(); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        <?php endif; ?>

                                                                    <?php endforeach; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>

                                        <?php else : ?>

                                            <div class="alert alert-warning" role="alert">Não existem demandas para o contrato.</div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php else : ?>
                <div class="alert alert-warning" role="alert">Não existem demandas cadastradas. Você pode retornar ao menu inicial e tentar a pesquisa por contrato.</div>

            <?php endif; ?>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col"><a class="btn btn-primary mr-2" href="index.php" role="button">Voltar</a>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalNovaConsulta">
                        Nova Pesquisa
                    </button>
                </div>
            </div>

            <div class="mt-5"></div>
        </div>
</main>

<!-- Toast -> Pesquisa -->
<div class="toast" id="myToast" style="position: fixed; bottom: 50px; right: 15px;">
    <div class="toast-header text-white" style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%);">
        <strong class="mr-auto"><i class="far fa-comment-dots"></i> Feedback</strong>

        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <div>Ajude-nos a melhorar.<br><a href="#" id="RespondePesquisa">Clique aqui e responda a uma rápida pesquisa sobre o uso do Painel Visão do Cliente.</a></div>
    </div>
</div>

<!-- Modal - Pesquisa -->


<form action="inclusao/pesquisa-satisfacao-piloto.php" method="post" class="needs-validation" novalidate="novalidate" id="frm-feedback">

    <input type="hidden" name="CodigoUsuario" id="CodigoUsuario" value="<?php echo Layout::getCodigoUsuario(); ?>">

    <div class="modal fade" id="ModalPesquisa" tabindex="-1" role="dialog" aria-labelledby="ModalPesquisaTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%);">
                    <h5 class="modal-title  text-white" id="ModalPesquisaTitle"><i class="far fa-comment-dots"></i> Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id='modal-body-feedback'>

                    <p> Responda a breves perguntas sobre sua satisfação com o uso do Painel Visão do Cliente, sendo 1 para muito insatisfeito e 5 para muito satisfeito.</p> 

                    <?php
                    $Perguntas = [
                        'Qualidade das informações' => [
                            'Nome' => 'Informacoes',
                            'tooltip' => 'A descrição da informação estava clara? Encontrou a informação que procurava?'
                        ],
                        'Layout do sistema' => [
                            'Nome' => 'Layout',
                            'tooltip' => 'A navegação está agradável? As cores e a disposição das informações auxiliam em sua localização e leitura?'
                        ],
                        'Desempenho do sistema' => [
                            'Nome' => 'Desempenho',
                            'tooltip' => 'O tempo de pesquisa foi rápido?'
                        ],
//                        'Desoneração do processo' => [
//                            'Nome' => 'Desoneracao',
//                            'tooltip' => 'Após a utilização do Painel Visão do Cliente, para este atendimento, foi necessária a abertura de chamado por outros canais? (Ex: serviços.caixa/atender.caixa/atende.caixa)'
//                        ],
                    ];
                    $Opcoes = [1, 2, 3, 4, 5];
                    ?>

                    <?php foreach ($Perguntas as $Label => $Atributos) : ?>

                        <fieldset class="form-group">

                            <legend class="col-form-label col-sm pt-0 font-weight-bold"><?php echo $Label; ?> <span data-toggle="tooltip" data-placement="top" title="Campo Obrigatório">*</span><small class="form-text text-muted"><?php echo $Atributos['tooltip']; ?></small></legend>

                            <div class="ml-3">
                                <?php foreach ($Opcoes as $Opcao) : ?>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="<?php echo $Atributos['Nome']; ?>" id="<?php echo $Atributos['Nome'] . $Opcao; ?>" value="<?php echo $Opcao; ?>" required="true">
                                        <label class="form-check-label" for="<?php echo $Atributos['Nome'] . $Opcao; ?>"><?php echo $Opcao; ?></label>
                                    </div>

                                <?php endforeach; ?>

                            </div>
                        </fieldset>

                    <?php endforeach; ?>

                    <fieldset class="form-group">

                        <legend class="col-form-label col-sm pt-0 font-weight-bold">Desoneração <span data-toggle="tooltip" data-placement="top" title="Campo Obrigatório">*</span><small class="form-text text-muted">Após a utilização do Painel Visão do Cliente, para este atendimento, foi necessária a abertura de chamado por outros canais? (Ex: serviços.caixa/atender.caixa/atende.caixa)</small></legend>

                        <div class="ml-3">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Desoneracao" id="Desoneracao0" value="0"  required="true">
                                <label class="form-check-label" for="Desoneracao">Não</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Desoneracao" id="Desoneracao1" value="1"  required="true">
                                <label class="form-check-label" for="Desoneracao">Sim</label>
                            </div>



                        </div>
                    </fieldset>


                    <div class="form-group">
                        <label for="Sugestoes" class="col-sm col-form-label">Criticas / Sugestões</label>
                        <textarea name="Sugestoes" id="Sugestoes" class="form-control" rows="3"></textarea>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="btn-gravar">Gravar</button>
                </div>
            </div>
        </div>
    </div>

</form>




<form action="visao.php" method="post" class="needs-validation" novalidate="novalidate" id="frm-pesquisa">

    <input type="hidden" name="ClienteID" id="ClienteID" value="">
    <input type="hidden" name="ContratoID" id="ContratoID" value="">

    <div class="modal fade" id="ModalNovaConsulta" tabindex="-1" role="dialog" aria-labelledby="ModalNovaConsultaTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%);">
                    <h5 class="modal-title  text-white" id="ModalNovaConsultaTitle">Efetuar Nova Pesquisa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id='modal-body-feedback'>

                    <div class="form-row mb-4">
                        <div class="col">
                            <label for="TipoPesquisa">Pesquisar por:</label>
                            <select class="form-control" id="TipoPesquisa" name="TipoPesquisa">
                                <option value="CLI">Cliente</option>
                                <option value="CON">Contrato</option>
                            </select>
                        </div>

                        <div class="col" id="div_Documento">
                            <label for="Documento">CPF/CNPJ:</label>
                            <input type="text" class="form-control" id="Documento" name="Documento" aria-describedby="Numero de CPF ou CNPJ para pesquisa" placeholder="Informe o número do CPF/CNPJ" required="required">
                            <div class="invalid-feedback">Favor informar o CPF/CNPJ.</div>
                            <div class="invalid-feedback invalido">CPF/CNPJ inválido.</div>
                        </div>

                        <div class="col" style="display: none;" id="div_Contrato">
                            <label for="Contrato">Contrato:</label>
                            <input type="text" class="form-control" id="Contrato" name="Contrato" aria-describedby="Numero de Contrato para pesquisa" placeholder="Informe o número do Contrato" maxlength="25">
                            <div class="invalid-feedback">Favor informar o Contrato.</div>
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col" id="RetornoConsulta"></div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-pesquisar">Pesquisar</button>
                    <button type="submit" class="btn btn-secondary" id="btn-cancelar" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

</form>

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

<?php Layout::getFoot(); ?>

<script type="text/javascript" src="visao.js"></script>
<script type="text/javascript" src="index.js"></script>

<?php
Layout::getFim();
