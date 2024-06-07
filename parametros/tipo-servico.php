<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\TipoServicoBase;
use Classes\Helper\EntityManagerFactory;

$Retorno = ['Retorno' => null];

/*
 * ************************************************************************* 
 * Dados do Formulário
 * ************************************************************************* 
 */
$Dados = filter_input_array(INPUT_POST);

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

//Verifica se a função já existe, cadastra caso não exista.
$EntityTipoServico = $EntityManager->getRepository(\Classes\Entity\TipoServico::class)->find($Dados['TipoServicoID']);

$EntityGrupo = $EntityManager->getRepository(\Classes\Entity\GrupoBase::class)->find($Dados['GrupoBaseID']);

if (is_null($EntityTipoServico)) {
    $EntityTipoServico = new \Classes\Entity\TipoServico();
}

$EntityTipoServico->setTipo($Dados['Tipo']);
$EntityTipoServico->setDescricao($Dados['Descricao']);
$EntityTipoServico->setGrupoBase($EntityGrupo);

$EntityManager->persist($EntityTipoServico);
$EntityManager->flush();

if (!is_null($EntityTipoServico)) {
    $Retorno = ['Retorno' => $EntityTipoServico->getId()];
}

echo json_encode($Retorno);
