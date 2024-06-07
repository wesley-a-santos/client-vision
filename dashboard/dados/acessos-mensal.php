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

$Query = "SELECT COUNT(AcessoID) AS Dados, FORMAT(DataAcesso, N'MMMM/yyyy', 'pt-BR') AS Rotulos, "
        . "YEAR(DataAcesso) AS Ano, MONTH(DataAcesso) AS Mes "
        . "FROM dbo.Acessos "
        . "WHERE DataAcesso Between CONVERT(DATE, :DataInicio) and CONVERT(DATE, :DataFim) "
        . "GROUP BY FORMAT(DataAcesso, N'MMMM/yyyy', 'pt-BR'), YEAR(DataAcesso), MONTH(DataAcesso) "
        . "ORDER BY Ano, Mes ";

$Dados->setQuery($Query);
$Dados->setParametros('DataInicio', $DataInicio, 'date');
$Dados->setParametros('DataFim', $DataFim, 'date');
$Dados->executar();

if ($Dados->getRetorno() !== 0) {
    $Retorno = $Dados->getLista();
}

echo json_encode($Retorno);


/*
USE [VisaoCliente]
GO

SELECT        Acessos.AcessoID, Acessos.CodigoUsuario, U.Codigo AS CodigoUnidade, ISNULL(CONVERT(VArchar(50), U.Sigla), CONVERT(VArchar(50), U.TipoUnidade) + ' ' + U.Nome) AS NomeUnidade, 
                         Subordinante.Codigo AS CodigoSubordinante, ISNULL(CONVERT(VArchar(50), Subordinante.Sigla), CONVERT(VArchar(50), Subordinante.TipoUnidade) + ' ' + Subordinante.Nome) AS NomeSubordinante
FROM            Acessos INNER JOIN
                         UnidadesCaixa AS U ON Acessos.UnidadeCaixaID = U.UnidadeCaixaID INNER JOIN
                         UnidadesCaixa AS Subordinante ON U.UnidadeSubordinacaoID = Subordinante.UnidadeCaixaID
GO




SELECT        COUNT(Acessos.AcessoID), 
                         Subordinante.Codigo AS CodigoSubordinante, ISNULL(CONVERT(VArchar(50), Subordinante.Sigla), CONVERT(VArchar(50), Subordinante.TipoUnidade) + ' ' + Subordinante.Nome) AS NomeSubordinante
FROM            Acessos INNER JOIN
                         UnidadesCaixa AS U ON Acessos.UnidadeCaixaID = U.UnidadeCaixaID INNER JOIN
                         UnidadesCaixa AS Subordinante ON U.UnidadeSubordinacaoID = Subordinante.UnidadeCaixaID
 GROUP BY Subordinante.Codigo , ISNULL(CONVERT(VArchar(50), Subordinante.Sigla), CONVERT(VArchar(50), Subordinante.TipoUnidade) + ' ' + Subordinante.Nome) 
GO








SELECT        Acessos.AcessoID, Acessos.CodigoUsuario, U.Codigo AS CodigoUnidade, ISNULL(CONVERT(VArchar(50), U.Sigla), CONVERT(VArchar(50), U.TipoUnidade) + ' ' + U.Nome) AS NomeUnidade, 
                         Subordinante.Codigo AS CodigoSubordinante, ISNULL(CONVERT(VArchar(50), Subordinante.Sigla), CONVERT(VArchar(50), Subordinante.TipoUnidade) + ' ' + Subordinante.Nome) AS NomeSubordinante
FROM            Acessos INNER JOIN
                         UnidadesCaixa AS U ON Acessos.UnidadeCaixaID = U.UnidadeCaixaID INNER JOIN
                         UnidadesCaixa AS Subordinante ON U.UnidadeSubordinacaoID = Subordinante.UnidadeCaixaID
  */