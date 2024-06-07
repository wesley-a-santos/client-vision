<?php

header('Content-Type: application/json; charset=utf-8');

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

require_once __DIR__ . '/../../vendor/autoload.php';

use Classes\BancoDeDados\Select;
use Classes\Validacao\ValidarInput;
use Classes\Calculos\Acessos;

$UnidadeCaixaID = 6901; //Presidencia
//$UnidadeCaixaID = 4754; //Suban

if ((ValidarInput::validarInputGet('UnidadeCaixaID')) && (trim(filter_input(INPUT_GET, 'UnidadeCaixaID')) !== '#')) {
    $UnidadeCaixaID = (int) trim(filter_input(INPUT_GET, 'UnidadeCaixaID'));
}


$Select = new Select();

$Query = "SELECT COUNT(A.AcessoID) AS Acessos, U.UnidadeCaixaID, U.Codigo, U.TipoUnidade, U.Nome, U.Sigla, H.Hierarquia, U.UnidadeSubordinacaoID
    FROM Acessos AS A RIGHT OUTER JOIN
    UnidadesCaixa AS U ON A.UnidadeCaixaID = U.UnidadeCaixaID INNER JOIN
    (SELECT UnidadeCaixaID, Hierarquia, Nivel
    FROM dbo.HierarquiaUnidades
    WHERE (Hierarquia LIKE :UnidadeCaixaID)) AS H ON H.UnidadeCaixaID = U.UnidadeCaixaID
GROUP BY U.UnidadeCaixaID, U.Codigo, U.TipoUnidade, U.Nome, U.Sigla, H.Nivel, H.Hierarquia, U.UnidadeSubordinacaoID, H.Nivel
ORDER BY H.Nivel ";


$Select->setQuery($Query);
$Select->setParametros('UnidadeCaixaID', "%{$UnidadeCaixaID}%", "varchar");
$Select->executar();

$Array = [];

if ($Select->getRetorno() !== 0) {
    $Lista = $Select->getLista();

    foreach ($Lista as $key => $value) {
        $AcessosSubordinadas = Acessos::contarAcessosSubordinadas($Lista, $value['UnidadeCaixaID']);


        $id = $value['UnidadeCaixaID'];

        if ((int) $value['UnidadeCaixaID'] === $UnidadeCaixaID) {
            $parent = '#';
        } else {
            $parent = $value['UnidadeSubordinacaoID'];
        }

        $text = ''
                . "<span class='d-flex flex-row d-inline-flex' style='width: 90%;'>"
                . "<span class='flex-fill'  style='width: 40%;'>{$value['TipoUnidade']} {$value['Nome']}</span>"
                . "<span class='flex-fill text-right'  style='width: 30%;'>Acessos na unidade: {$value['Acessos']}</span> "
                . "<span class='flex-fill text-right'  style='width: 30%;'>Acessos nas subordinadas: {$AcessosSubordinadas}</span> "
//                . "<span class='flex-fill text-right'  style='width: 30%;'>Acessos na unidade: <span class='badge badge-info'>{$value['Acessos']}</span></span> "
//                . "<span class='flex-fill text-right'  style='width: 30%;'>Acessos nas subordinadas: <span class='badge badge-info'>{$AcessosSubordinadas}</span></span> "
                . "</span>";


//https://github.com/jonmiles/bootstrap-treeview
//https://www.jstree.com/docs/config/


        $Array[] = [
            'id' => $id, // required
            'parent' => $parent, // required
            'text' => $text, // node text
            'icon' => 'fas fa-university', // string for custom
//                'state' => [
//                    'opened' => false, // is the node open
//                    'disabled' => false, // is the node disabled
//                    'selected' => false, // is the node selected
//                ],
//                'li_attr' => ['class' => 'row w-100'], // attributes for the generated LI node
            'a_attr' => ['class' => 'w-100'], // attributes for the generated A node
        ];
    }
}





echo json_encode($Array);

