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

namespace Classes\Validacao;

/**
 * Description of ValidarArray
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class ValidarArray {

    /**
     * Remover o campo informado de um array.
     *
     * @param string $Campo <p>Campo a ser removido</p>
     * @param array $Parametros <p>array a ser alterado</p>
     * @return void
     */
    public static function removerCampoArray(string $Campo, array &$Array): void
    {
        $ValorCampo = null;
        if (array_key_exists($Campo, $Array)) {
            $ValorCampo = $Array[$Campo];
            unset($Array[$Campo]);
        }
    }

    /**
     * Verifica um array. Retorna o proprio array caso seja um array válido. Retorna [] em caso de array nulo.
     * @param array $Array <p>Array a ser validado.</p>
     * @return array
     */
    public static function retornarArray(?array $Array): array
    {
        if (!self::validarArray($Array)) {
            $Array = [];
        }
        return $Array;
    }

    /**
     * Varifica se um array informado é válido
     * @param type $Array <p>Array a ser validado.</p>
     * @return bool <p>Retorna true para array válido, caso contrário retorna false.</p>
     */
    public static function validarArray(?array $Array): bool
    {
        return (!is_null($Array)) && (is_array($Array)) && (sizeof($Array) > 0);
    }

    public static function trocarValoresVaziosPorNulos(array $Array): array
    {
        foreach ($Array as $key => $value) {
            if ((!is_object($value)) && (trim($value) === '')) {
                $Array[$key] = null;
            }
        }

        return $Array;
    }

}
