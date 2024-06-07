<?php
require_once __DIR__ . '/../../vendor/autoload.php';

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




foreach ($ListarPesquisas as $Pesquisa) {

    $ArrayDados[] = [
        'CodigoUsuario' => $Pesquisa['Usuario']['CodigoUsuario'],
        'Nome' => $Pesquisa['Usuario']['Nome'],
        'CodigoUnidade' => $Pesquisa['Usuario']['UnidadeCaixa']['Codigo'],
        'Unidade' => $Pesquisa['Usuario']['UnidadeCaixa']['TipoUnidade'] . ' ' . $Pesquisa['Usuario']['UnidadeCaixa']['Nome'],
        'Data' => $Pesquisa['DataResposta']->format('d/m/Y'),
        'Informacoes' => $Pesquisa['Informacoes'],
        'Layout' => $Pesquisa['Layout'],
        'Desempenho' => $Pesquisa['Desempenho'],
        'Desoneracao' => (($Pesquisa['Desoneracao'] === true ) ? 'Sim' : 'Não'),
        'Sugestoes' => $Pesquisa['Sugestoes']
    ];
}

$ArrayCabecalho = [
    'Usuário',
    'Usuário',
    'Unidade',
    'Unidade',
    'Data',
    'Qualidade das informações',
    'Layout do sistema',
    'Desempenho do sistema',
    'Desoneração',
    'Criticas/Sugestões',
];

$Excel = new Classes\Excel\GeraExcel();

$Excel->setArquivo("Pesquisa_Satisfacao.xlsx");
$Excel->setPlanilha("Pesquisas");
//$Excel->setFormatoData("E:E");
$Excel->setCabecalho($ArrayCabecalho);
$Excel->setConteudo($ArrayDados);
$Excel->Download();
