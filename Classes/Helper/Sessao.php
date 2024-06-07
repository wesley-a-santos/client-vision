<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Helper;

/**
 * Description of Session
 *
 * @author c068442
 */
class Sessao {

    const SESSAO_INICIADA = TRUE;
    const SESSAO_NAO_INICIADA = FALSE;

    // The state of the Session
    private $EstadoSessao = self::SESSAO_NAO_INICIADA;
    
    // THE only Instancia of the class
    private static $Instancia;
    
    private $SessaoID;

    private function __construct()
    {
        
    }

    /**
     *    Returns THE Instancia of 'Session'.
     *    The Session is automatically initialized if it wasn't.
     *    
     *    @return    object
     * */
    public static function getInstancia()
    {
        if (!isset(self::$Instancia)) {
            self::$Instancia = new self;
        }

        self::$Instancia->startSession();

        return self::$Instancia;
    }

    public function getSessaoID()
    {
        return Session_id();
    }

    /**
     *    (Re)starts the Session.
     *    
     *    @return    bool    TRUE if the Session has been initialized, else FALSE.
     * */
    public function startSession()
    {
        if ($this->EstadoSessao == self::SESSAO_NAO_INICIADA) {
            $this->EstadoSessao = Session_start();
            $this->SessaoID = Session_id();
        }

        return $this->EstadoSessao;
    }

    /**
     *    Stores datas in the Session.
     *    Example: $Instancia->foo = 'bar';
     *    
     *    @param    name    Name of the datas.
     *    @param    value    Your datas.
     *    @return    void
     * */
    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     *    Gets datas from the Session.
     *    Example: echo $Instancia->foo;
     *    
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in Session.
     * */
    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     *    Destroys the current Session.
     *    
     *    @return    bool    TRUE is Session has been deleted, else FALSE.
     * */
    public function destroy()
    {
        $Retorno = false;

        if ($this->EstadoSessao == self::SESSAO_INICIADA) {
            $this->EstadoSessao = !Session_destroy();
            $this->SessaoID = null;
            unset($_SESSION);
            $Retorno = !$this->EstadoSessao;
        }

        return $Retorno;
    }

}
