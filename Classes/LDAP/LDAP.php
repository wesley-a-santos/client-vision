<?php

/*
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
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

namespace Classes\LDAP;

use ErrorException;

/**
 * Description of LDAP
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 */
class LDAP {

    public function conectar() {

        try {
            $LDAPServer = ldap_connect("ldap://ldapcluster");
            ldap_set_option($LDAPServer, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($LDAPServer, LDAP_OPT_REFERRALS, 0);
        } catch (ErrorException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        return $LDAPServer;
    }

    public function desconectar(LDAP $LDAPServer) {
        ldap_close($LDAPServer);
    }

    public function validarRetorno($LDAPGetEntries): bool {

        if ($LDAPGetEntries["count"] === 0) { // USUÁRIO NÃO LOCALIZADO -> RETORNARÁ ERRO 400.
            return false;
        } else { // Usuário Localizado -> Captura os dados nas variáveis
            return true;
        }
    }

}
