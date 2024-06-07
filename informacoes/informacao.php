<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Cliente;
use Classes\Entity\Contrato;
use Classes\Entity\Funcao;
use Classes\Entity\Informacao;
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
$EntityUnidade = $EntityManager->getRepository(UnidadeCaixa::class)->cadastrarUnidadeCaixa([
    'Codigo' => $CodigoUnidade,
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

//https://ckeditor.com/docs/ckeditor5/latest/features/word-count.html

$EntityInformacao = $EntityManager->getRepository(Informacao::class)->cadastrarInformacao([
    'InformacaoID' => $Dados['InformacaoID'],
    'DataValidade' => DateTimeImmutable::createFromFormat('d/m/Y', $Dados['DataValidade']),
    'Cliente' => $EntityCliente,
    'Contrato' => $EntityContrato,
    'Titulo' => $Dados['Titulo'],
    'Descricao' => $Dados['Descricao'],
    'Usuario' => $EntityUsuario,
    'UnidadeCaixa' => $EntityUnidade,
    'TipoInformacaoID' => $Dados['TipoInformacaoID'],
    'Permanente' => $Dados['Permanente'],
        ]);

if(!is_null($EntityInformacao)){
    $Retorno = ['Retorno' => $EntityInformacao->getId()];
}

echo json_encode($Retorno);
