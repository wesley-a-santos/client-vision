<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Cliente;
use Classes\Entity\Contrato;
use Classes\Entity\Demanda;
use Classes\Entity\Funcao;
use Classes\Entity\GrauSigilo;
use Classes\Entity\SistemaOrigem;
use Classes\Entity\StatusDemanda;
use Classes\Entity\TipoServico;
use Classes\Entity\UnidadeCaixa;
use Classes\Entity\Usuario;
use Classes\Helper\EntityManagerFactory;
use Classes\LDAP\Usuario as UsuarioLDAP;

$Retorno = ['Retorno' => null];

/*
 * ************************************************************************* 
 * Dados do Formulário
 * ************************************************************************* 
 */
$Dados = filter_input_array(INPUT_POST);

/*
 * ************************************************************************* 
 * Dados de Usuário
 * ************************************************************************* 
 */

$Usuario = new UsuarioLDAP($Dados['CodigoUsuario']);



//Captura os dados do usuário logado na estação.
$CodigoUsuario = $Usuario->getCodigoUsuario();
$Nome = $Usuario->getNome();
$Email = $Usuario->getEmail();
$Funcao = $Usuario->getFuncao();
$CodigoUnidade = $Usuario->getCodigoUnidade();
$Unidade = $Usuario->getNomeUnidade();
$Sigla = $Usuario->getSiglaUnidade();

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

//Verifica se a função já existe, cadastra caso não exista.
$EntityFuncao = $EntityManager->getRepository(Funcao::class)->cadastrarFuncao($Funcao);

//Verifica se a função já existe, cadastra caso não exista.
$EntitySistemaOrigem = $EntityManager->getRepository(SistemaOrigem::class)->cadastrarSistemaOrigem($Dados['SistemaOrigem']);

//Verifica se a função já existe, cadastra caso não exista.
$EntityUnidade = $EntityManager->getRepository(UnidadeCaixa::class)->cadastrarUnidadeCaixa([
    'Codigo' => $CodigoUnidade,
    'Digito' => 0,
    'Nome' => $Unidade,
    'Sigla' => $Sigla,
        ]);

//Verifica se o Usuário já existe, cadastra caso não exista.
$EntityUsuario = $EntityManager->getRepository(Usuario::class)->cadastrarUsuario([
    'CodigoUsuario' => $CodigoUsuario,
    'Nome' => $Nome,
    'Email' => $Email,
    'Funcao' => $EntityFuncao,
    'UnidadeCaixa' => $EntityUnidade
        ]);

$EntityContrato = $EntityManager->getRepository(Contrato::class)->cadastrarContrato([
    'Numero' => $Dados['Contrato'],
    'Codigo' => $Dados['CodigoProduto'],
    'UnidadeCaixa' => $EntityUnidade,
        ]);

//Verifica se o Cliente já existe, cadastra caso não exista.
$EntityCliente = $EntityManager->getRepository(Cliente::class)->cadastrarCliente([
    'Documento' => $Dados['Documento'],
    'Nome' => $Dados['Nome'],
    'Tipo' => $Dados['Tipo'],
    'Email' => null,
    'InscricaoSocial' => null,
    'Contrato' => $EntityContrato,
        ]);

$EntityTipoServico = $EntityManager->getRepository(TipoServico::class)->find($Dados['TipoServicoID']);
$EntityStatusDemanda = $EntityManager->getRepository(StatusDemanda::class)->find($Dados['StatusDemandaID']);
$EntityGrauSigilo = $EntityManager->getRepository(GrauSigilo::class)->find($Dados['GrauSigiloID']);

//https://ckeditor.com/docs/ckeditor5/latest/features/word-count.html

$EntityDemanda = $EntityManager->getRepository(Demanda::class)->cadastrarDemanda([
    'Cliente' => $EntityCliente,
    'Contrato' => $EntityContrato,
    'Usuario' => $EntityUsuario,
    'UnidadeCaixa' => $EntityUnidade,
    'TipoServico' => $EntityTipoServico,
    'StatusDemanda' => $EntityStatusDemanda,
    'SistemaOrigem' => $EntitySistemaOrigem,
    'GrauSigilo' => $EntityGrauSigilo,
    'Detalhamento' => $Dados['Detalhamento'],
        ]);

if (!is_null($EntityDemanda)) {
    $Retorno = ['Retorno' => $EntityDemanda->getId()];
}

echo json_encode($Retorno);
