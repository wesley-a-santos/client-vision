<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\GrupoBase;
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
$EntityGrupo = $EntityManager->getRepository(GrupoBase::class)->find($Dados['GrupoBaseID']);


if (is_null($EntityGrupo)) {
    $EntityGrupo = new GrupoBase();
}

$EntityGrupo->setGrupo($Dados['Grupo']);
$EntityManager->persist($EntityGrupo);
$EntityManager->flush();

if (!is_null($EntityGrupo)) {
    $Retorno = ['Retorno' => $EntityGrupo->getId()];
}

echo json_encode($Retorno);
