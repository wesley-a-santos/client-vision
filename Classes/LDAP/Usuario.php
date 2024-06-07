<?php

namespace Classes\LDAP;

use ErrorException;

/**
 * Description of CodigoUsuario
 *
 * @author c068442
 */
class Usuario {

    private $CodigoUsuario;
    private $Nome;
    private $Cargo;
    private $Funcao;
    private $CPF;
    private $Email;
    private $Dominio;
    private $Estacao;
    private $CodigoUnidade;
    private $NomeUnidade;
    private $SiglaUnidade;
    private $LDAP;
    private $LDAPGetEntries;

    public function __construct(string $CodigoUsuario = null, string $Dominio = 'DOMINIO') {

        $this->LDAP = new LDAP();

        $this->CodigoUsuario = $CodigoUsuario;
        $this->Dominio = $Dominio;

        $this->verificarUsuario();
        $this->pesquisar();

        $this->prepararDados();
    }

    private function verificarUsuario(): void {
        if (is_null($this->CodigoUsuario) || trim($this->CodigoUsuario === '')) {
            $this->capturarUsuarioLogado();
        }
    }

    private function capturarUsuarioLogado() {
        try {
            $UsuarioLogado = explode("\\", filter_input(INPUT_SERVER, 'AUTH_USER'));
            $this->Dominio = $UsuarioLogado[0];
            $this->CodigoUsuario = $UsuarioLogado[1];
        } catch (ErrorException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    private function pesquisar() {

        $LDAPServer = $this->LDAP->conectar();

        try {

            if ($LDAPServer) {
                $LdapSearch = ldap_search($LDAPServer, "ou=People,o=caixa", "(uid={$this->CodigoUsuario})");
                $this->LDAPGetEntries = ldap_get_entries($LDAPServer, $LdapSearch); // ldap_count_entries($LDAPServer, $LdapSearch) -> Numero de Entradas
            }
        } catch (ErrorException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    private function prepararDados(): void {

        if ($this->LDAP->validarRetorno($this->LDAPGetEntries)) {
            
            $this->Nome = $this->LDAPGetEntries[0]['no-usuario'][0];
            
             if(isset($this->LDAPGetEntries[0]['no-cargo'][0])){
                $this->Email = $this->LDAPGetEntries[0]['no-cargo'][0];
            }

            $this->CPF = $this->LDAPGetEntries[0]['nu-cpf'][0];
            
            if(isset($this->LDAPGetEntries[0]['mail'][0])){
                $this->Email = $this->LDAPGetEntries[0]['mail'][0];
            }
            
            $this->Estacao = strtoupper(str_ireplace('.corp.caixa.gov.br', '', gethostbyaddr(filter_input(INPUT_SERVER, 'REMOTE_ADDR'))));

            $this->CodigoUnidade = $this->LDAPGetEntries[0]['co-unidade'][0];
            $this->NomeUnidade = $this->LDAPGetEntries[0]['no-lotacaofisica'][0];
            $this->SiglaUnidade = $this->LDAPGetEntries[0]['sg-unidade'][0];

            if (isset($this->LDAPGetEntries[0]['no-funcao'][0])) {
                $this->Funcao = $this->LDAPGetEntries[0]['no-funcao'][0];
            } else {
                $this->Funcao = $this->Cargo;
            }
        }
    }

    public function getCodigoUsuario() {
        return $this->CodigoUsuario;
//        return 'C068442';

    }

    public function getNome() {
        return $this->Nome;
//        return 'Wesley';
    }

    public function getCargo() {
        return $this->Cargo;
    }

    public function getFuncao() {
        return $this->Funcao;
//        return 'Assistente';
    }

    public function getCPF() {
        return $this->CPF;
    }

    public function getEmail() {
        return $this->Email;
//        return 'wesley.a.santos@caixa.gov.br';
    }

    public function getDominio() {
        return $this->Dominio;
    }

    public function getEstacao() {
        return $this->Estacao;
    }

    public function getCodigoUnidade() {
        return $this->CodigoUnidade;
//        return '7017';
    }

    public function getNomeUnidade() {
        return $this->NomeUnidade;
    }

    public function getSiglaUnidade() {
        return $this->SiglaUnidade;
    }

}
