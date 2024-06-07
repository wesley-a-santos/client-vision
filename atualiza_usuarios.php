<?php

use Classes\BancoDeDados\Select;
use Classes\Entity\Funcao;
use Classes\Entity\UnidadeCaixa;
use Classes\Entity\Usuario as EUsuario;
use Classes\Helper\EntityManagerFactory;
use Classes\LDAP\Usuario;

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

require_once __DIR__ . '/vendor/autoload.php';
//
//
//$Usu = new \Classes\LDAP\Usuario('c083613');
//
//exit();
$Dados = new Select();

$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();


$Query = "SELECT TOP 200 UsuarioID, CodigoUsuario, Nome, Email, UnidadeCaixaID, FuncaoID
FROM dbo.Usuarios
WHERE (Nome = 'Verificar') AND (CodigoUsuario <> 'C000000') ";

$Dados->setQuery($Query);

$Dados->executar();

foreach ($Dados->getLista() as $value) {

    $Usuario = new Usuario($value['CodigoUsuario']);

    $UFuncao = $Usuario->getFuncao();

    if (is_null($UFuncao)) {
        $UFuncao = 'TECNICO BANCARIO NOVO';
    }
    
    echo $value['CodigoUsuario'] . ' - ' . $UFuncao . "<br>";

    $Funcao = $EntityManager->getRepository(Funcao::class)->cadastrarFuncao($UFuncao);

    $CodigoUnidade = $Usuario->getCodigoUnidade();
    $NomeUnidade = $Usuario->getNomeUnidade();
    $Sigla = $Usuario->getSiglaUnidade();
    $CodigoUsuario = $value['CodigoUsuario'];
    $Nome = $Usuario->getNome();
    $Email = $Usuario->getEmail();

    if (is_null($Nome)) {
        $CodigoUnidade = '0001';
        $NomeUnidade = 'Matriz';
        $Sigla = null;
        $Nome = 'Não Identificado';
        $Email = 'Não Identificado';
    }

    
    $Unidade = $EntityManager->getRepository(UnidadeCaixa::class)->cadastrarUnidadeCaixa([
        'Codigo' => $CodigoUnidade,
        'Digito' => 0,
        'Nome' => $NomeUnidade,
        'Sigla' => $Sigla,
    ]);

    $EntityUsuario = $EntityManager->getRepository(EUsuario::class)->cadastrarUsuario([
        'CodigoUsuario' => $CodigoUsuario,
        'Nome' => $Nome,
        'Email' => $Email,
        'Funcao' => $Funcao,
        'UnidadeCaixa' => $Unidade
    ]);


}

