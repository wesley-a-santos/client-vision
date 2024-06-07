<?php
/*
 * **************************************************************************
 * Modo de depuração - colocar false para produção
 * ***************************************************************************
 */
define('DEBUG_MODE', false);
/*
 * **************************************************************************
 * URL's e caminhos de servidor web
 * ***************************************************************************
 */

define('SITE_URL', (filter_input(INPUT_SERVER, 'HTTPS') === 'on' ? "https://" : "http://") . filter_input(INPUT_SERVER, 'HTTP_HOST')); //Produção

//Homologação
//define('SITE', 'http://www.cemob.hom.sp.caixa');

//Produção
define('SITE', 'http://www.cemob.sp.caixa');

/*
 * **************************************************************************
 * URL's e caminhos da aplicação
 * ***************************************************************************
 */

define('API_URL', SITE . '/api');

define('SISTEMA_RAIZ', "/sistemas/VisaoCliente");

define('SISTEMA_URL', SITE_URL . SISTEMA_RAIZ);


//Servidor Mapeado
// define('UPLOAD_PATH', "\\\\sp7017sr001\\Upload$");

// Servidor Local
define('UPLOAD_PATH', filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . str_replace('/', DIRECTORY_SEPARATOR, SISTEMA_RAIZ) . DIRECTORY_SEPARATOR . 'Upload');


/*
 * **************************************************************************
 * Informações sobre o sistema
 * ***************************************************************************
 */
define('SISTEMA_SIGLA', 'Visão do Cliente');
define('SISTEMA_NOME', 'Visão do Cliente');
define('SISTEMA_VERSAO', '1.2.0');

/*
 * **************************************************************************
 * Informações sobre a unidade
 * ***************************************************************************
 */
define('AREA_SIGLA', 'CEMOB');
define('AREA_NOME', 'Centralizadora Nacional de Manutenção de Operações Bancárias');

/*
 * **************************************************************************
 * Banco de Dados da Aplicação
 * ***************************************************************************
 */
//desenvolvimento
define ('SERVIDOR', 'SERVER');
//define('SERVIDOR', 'localhost');


define ('BASE', 'VisaoCliente');
define ('USUARIO', 'user');
define ('SENHA', 'password');

/*
 * **************************************************************************
 * Exibe erros
 * ***************************************************************************
 */
if (DEBUG_MODE === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// EOF
