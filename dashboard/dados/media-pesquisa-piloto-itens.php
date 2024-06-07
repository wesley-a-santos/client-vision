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

require_once __DIR__ . '/../../vendor/autoload.php';

use Classes\BancoDeDados\Select;
use Classes\Formatacao\FormatarValor;
use Classes\Validacao\ValidarInput;

$Retorno = null;

$DataAtual = new DateTime();

//Data Final = Hoje
$DataFim = $DataAtual->format('Y-m-d');

//Data Inicial = 6 meses atrás
$DataInicio = $DataAtual->sub(new DateInterval('P6M'))->format('Y-m-d');

if (ValidarInput::validarInputPost('DataInicio')) {
    $DataInicio = FormatarValor::formatarData('d/m/Y', 'Y-m-d', filter_input(INPUT_POST, 'DataInicio'));
}

if (ValidarInput::validarInputPost('DataFim')) {
    $DataFim = FormatarValor::formatarData('d/m/Y', 'Y-m-d', filter_input(INPUT_POST, 'DataFim'));
}

$Dados = new Select();

$Query = "SELECT COUNT(PesquisaID) AS Quantidade, "
        . "AVG(Informacoes) AS MediaInformacoes, "
        . "AVG(Layout) AS MediaLayout, "
        . "AVG(Desempenho) AS MediaDesempenho "
        . "FROM dbo.PesquisaSatisfacaoPiloto "
        . "WHERE CONVERT(DATE, DataResposta) BETWEEN CONVERT(DATE, :DataInicio) and CONVERT(DATE, :DataFim) ";

$Dados->setQuery($Query);
$Dados->setParametros('DataInicio', $DataInicio, 'date');
$Dados->setParametros('DataFim', $DataFim, 'date');
$Dados->executar();

if ($Dados->getRetorno() !== 0) {
    $Valores = $Dados->getDados();
//    $Retorno[] = ['Dados' => $Valores['Quantidade'], 'Rotulos' => 'Total de Consultas' ];
    $Retorno[] = ['Dados' => $Valores['MediaInformacoes'], 'Rotulos' => 'Qualidade das Informações' ];
    $Retorno[] = ['Dados' => $Valores['MediaLayout'], 'Rotulos' => 'Layout do Painel' ];
    $Retorno[] = ['Dados' => $Valores['MediaDesempenho'], 'Rotulos' => 'Desempenho do Painel' ];
    
}

echo json_encode($Retorno);
