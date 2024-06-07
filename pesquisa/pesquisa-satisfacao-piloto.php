<?php

/* 
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\Funcao;
use Classes\Entity\PesquisaSatisfacaoPiloto;
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


$EntityPesquisa = $EntityManager->getRepository(PesquisaSatisfacaoPiloto::class)->gravarPesquisa([
    'Desempenho' => $Dados['Desempenho'],
    'Desoneracao' => $Dados['Desoneracao'],
    'Informacoes' => $Dados['Informacoes'],
    'Layout' => $Dados['Layout'],
    'Sugestoes' => $Dados['Sugestoes'],

    'Usuario' => $EntityUsuario,
    'UnidadeCaixa' => $EntityUnidade,

        ]);

if(!is_null($EntityPesquisa)){
    $Retorno = ['Retorno' => $EntityPesquisa->getId()];
}

echo json_encode($Retorno);
