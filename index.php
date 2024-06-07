<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//header("Location: MANUTENCAO.php");

require_once __DIR__ . '/vendor/autoload.php';

use Classes\Entity\AtualizacaoBase;
use Classes\Helper\EntityManagerFactory;
use Classes\Sistema\Layout;

Layout::getHead();

Layout::getMenu();

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$ListarAtualizacoes = $EntityManager->getRepository(AtualizacaoBase::class)->listar();


?>

<main role="main" class="flex-shrink-0 pb-5">
    <div class="container">

        <div class="alert alert-danger mt-3 p-1" style='font-size:14px'; role="alert">
            <b>Atenção!</b> Esta página possui classificação confidencial #INTERNO.CAIXA, portanto as informações são restritas aos empregados, dirigentes e aos conselheiros da CAIXA.
        </div>

        <h3 class="mt-5 text-center"><?php echo SISTEMA_NOME; ?></h3>

            <form action="visao.php" method="post" class="needs-validation" novalidate="novalidate" id="frm-pesquisa">
                <input type="hidden" name="ClienteID" id="ClienteID" value="">
                <input type="hidden" name="ContratoID" id="ContratoID" value="">
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

                <button type="button" class="btn btn-secondary" id="btn-pesquisar">Pesquisar</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalAtualizacaoBases">Bases Disponíveis</button>

            </form>


            <div class="row mt-3">

                <div class="col" id="RetornoConsulta"></div>

            </div>



    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="ModalAtualizacaoBases" tabindex="-1" role="dialog" aria-labelledby="ModalAtualizacaoBasesTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(to right, #005ca9 0%, #54bbab 109%);">
                <h5 class="modal-title text-white" id="ModalAtualizacaoBasesTitle">Bases Disponíveis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Bases Disponíveis</th>
                            <th>Última Atualização</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ListarAtualizacoes as $Atualizacao) : ?>
                            <tr>
                                <td><?php echo $Atualizacao->getGrupoBase()->getGrupo(); ?></td>
                                <td class="text-right"><?php echo $Atualizacao->getDataAtualizacao()->format('d/m/Y'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
 

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


<!-- Toast -> Bases -->
<div class="toast" id="myToast" style="position: fixed; bottom: 10px; right: 15px;">
    <div class="toast-header text-white" style="background-color: #F39200;">
        <strong class="mr-auto"><i class="fas fa-database"></i> Bases Disponíveis</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <a href="#" data-toggle="modal" data-target="#ModalAtualizacaoBases">Clique aqui para visualizar as bases disponiveis para consulta no Painel Visão do Cliente.</a></div>
</div>



<?php Layout::getFoot(); ?>


<script type="text/javascript" src="index.js"></script>


<?php
Layout::getFim();

//PHP EOF
