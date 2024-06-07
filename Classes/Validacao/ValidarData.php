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

use \Datetime;
/**
 * Description of ValidarData
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class ValidarData {
    
     /**
     * Verifica se o valor informado é uma data válida.
     *
     * @param type $Data <p>Data a ser verificada</p>
     * @param type $Formato <p>Formato de <b>$Data</b>, padrão PHP.</p>
     * @return bool <p>True para data válida, false para inválida.</p>
     */
    public static function validarData(?string $Data, string $Formato = 'Y-m-d H:i:s.u'): bool {
        $ValidarData = DateTime::createFromFormat($Formato, $Data);
        return $ValidarData && $ValidarData->format($Formato) == $Data;
    }
    
}
