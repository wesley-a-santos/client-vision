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

/**
 * Description of UnidadeCaixa
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */

class Acessos {

    public static function contarAcessosSubordinadas(array $Lista, int $UnidadeCaixaID)
    {
        $Quantidade = 0;

        foreach ($Lista as $value) {
            
            if(($UnidadeCaixaID !== (int)$value['UnidadeCaixaID']) && (self::verificarUnidadeSubordinada($value['Hierarquia'], $UnidadeCaixaID))){
                $Quantidade += $value['Acessos'];
            }
        }

        return $Quantidade;
    }

    private static function verificarUnidadeSubordinada(string $Hierarquia, int $UnidadeCaixaID): bool
    {

        $Unidades = explode(';', $Hierarquia);

        foreach ($Unidades as $value) {
            if ((int) $value === (int) $UnidadeCaixaID) {
                return true;
            }
        }

        return false;
    }

}
