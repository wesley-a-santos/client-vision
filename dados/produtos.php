<?php

require_once '../vendor/autoload.php';

use Classes\Entity\Produto;
use Classes\Helper\EntityManagerFactory;
use Classes\Validacao\ValidarInput;
use Classes\Validacao\ValidarNumero;

$Dados = [
    'ProdutoID' => null,
    'Codigo' => null,
    'Nome' => null,
    'Mascara' => '0#'
];

if (ValidarInput::validarInputPost('CodigoProduto')) {

    $EntityManagerFactory = new EntityManagerFactory();
    $EntityManager = $EntityManagerFactory->getEntityManager();

    $Produto = $EntityManager->getRepository(Produto::class)
            ->pesquisar(ValidarNumero::validarNumeroInteiro(filter_input(INPUT_POST, 'CodigoProduto')));

    if (!is_null($Produto)) {
        $Dados = [
            'ProdutoID' => $Produto->getId(),
            'Codigo' => $Produto->getCodigo(),
            'Nome' => $Produto->getNome(),
            'Mascara' => $Produto->getSistema()->getMascara()
        ];
    }
}

echo json_encode($Dados);
