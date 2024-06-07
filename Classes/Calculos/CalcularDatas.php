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

namespace Classes\Calculos;

use Classes\Validacao\ValidarData;
use DateTime;

/**
 * Description of CalcularDatas
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class CalcularDatas {
        /**
     * Calcula a quantidade de dias entre duas datas informadas.
     *
     * @param string $FormatoDataInicio <p>Formato de <b>$DataInicio</b>, padrão PHP.</p>
     * @param string $DataInicio <p>Data inicial do período a ser calculado.</p>
     * @param string $FormatoDataFim <p>Formato de <b>$DataFim</b>, padrão PHP.</p>
     * @param string $DataFim <p>Data final do período a ser calculado.</p>
     * @return int
     */
    public static function calcularDiferencaDatas(string $FormatoDataInicio, string $DataInicio, string $FormatoDataFim, string $DataFim): ?int {
        $Dias = null;
        if ((ValidarData::validarData($DataInicio, $FormatoDataInicio)) && (ValidarData::validarData($DataFim, $FormatoDataFim))) {
            $DataInicio = DateTime::createFromFormat($FormatoDataInicio, $DataInicio);
            $DataFim = DateTime::createFromFormat($FormatoDataFim, $DataFim);
            $Diferenca = date_diff($DataInicio, $DataFim);
            if ($Diferenca->invert === 1) {
                $Dias = $Diferenca->days * - 1;
            } else {
                $Dias = $Diferenca->days;
            }
        }
        return $Dias;
    }
}
