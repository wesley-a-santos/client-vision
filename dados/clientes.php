<?php

require_once '../vendor/autoload.php';

use Classes\Entity\Cliente;
use Classes\Entity\Contrato;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Validacao\ValidarInput;

$Dados = [
    "ClienteID" => null,
    "ContratoID" => null,
];


if (ValidarInput::validarInputPost('TipoPesquisa')) {

    $TipoPesquisa = filter_input(INPUT_POST, 'TipoPesquisa', FILTER_SANITIZE_STRING);

    $EntityManagerFactory = new EntityManagerFactory();
    $EntityManager = $EntityManagerFactory->getEntityManager();

    $Cliente = null;
    $Contrato = null;

    if ($TipoPesquisa === 'CLI') {
        $Cliente = $EntityManager->getRepository(Cliente::class)->findOneBy(['Documento' => FormatarValor::retornarSomenteNumeros(filter_input(INPUT_POST, 'Documento', FILTER_SANITIZE_NUMBER_INT))]);
    } else {
        $Contrato = $EntityManager->getRepository(Contrato::class)->findOneBy(['Numero' => FormatarValor::retornarSomenteNumeros(filter_input(INPUT_POST, 'Contrato', FILTER_SANITIZE_NUMBER_INT))]);
    }

    if (!is_null($Cliente)) {
        $Dados["ClienteID"] = $Cliente->getId();
        $Dados["Nome"] = $Cliente->getNome();
    }
    if (!is_null($Contrato)) {
        $Dados["ContratoID"] = $Contrato->getId();
    }
    
}

echo json_encode($Dados);
