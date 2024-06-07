<?php

require_once '../vendor/autoload.php';

use Classes\Entity\Contrato;
use Classes\Formatacao\FormatarValor;
use Classes\Helper\EntityManagerFactory;
use Classes\Validacao\ValidarInput;

$Dados = [
    "ContratoID" => null,
    "CodigoProduto" => null,
    "Produto" => null,
    "Mascara" => '0#',
];

if (ValidarInput::validarInputPost('Contrato')) {

    $EntityManagerFactory = new EntityManagerFactory();
    $EntityManager = $EntityManagerFactory->getEntityManager();

    $Contrato = $EntityManager->getRepository(Contrato::class)->findOneBy(['Numero' => FormatarValor::retornarSomenteNumeros(filter_input(INPUT_POST, 'Contrato'))]);

    if (!is_null($Contrato)) {
        $Dados["ContratoID"] = $Contrato->getId();
        $Dados["CodigoProduto"] = $Contrato->getProduto()->getCodigo();
        $Dados["Produto"] = $Contrato->getProduto()->getNome();
        $Dados["Mascara"] = $Contrato->getProduto()->getSistema()->getMascara();
    }
}

echo json_encode($Dados);
