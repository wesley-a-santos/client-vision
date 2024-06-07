<?php

require_once '../vendor/autoload.php';

use Classes\Entity\UnidadeCaixa;
use Classes\Helper\EntityManagerFactory;
use Classes\Validacao\ValidarInput;
use Classes\Validacao\ValidarNumero;

$Dados = [
    'UnidadeCaixaID' => null,
    'Codigo' => null,
    'Nome' => null,
    'Email' => '0#'
];

if (ValidarInput::validarInputPost('CodigoUnidade')) {

    $EntityManagerFactory = new EntityManagerFactory();
    $EntityManager = $EntityManagerFactory->getEntityManager();

    $Unidade = $EntityManager->getRepository(UnidadeCaixa::class)
            ->pesquisarUnidadeCaixa(ValidarNumero::validarNumeroInteiro(filter_input(INPUT_POST, 'CodigoUnidade')));

    if (!is_null($Unidade)) {
        $Dados = [
            'UnidadeID' => $Unidade->getId(),
            'Codigo' => $Unidade->getCodigo(),
            'Nome' => $Unidade->getNome(),
            'Email' => $Unidade->getEmail()
        ];
    }
}

echo json_encode($Dados);
