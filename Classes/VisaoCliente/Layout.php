<?php

namespace Classes\VisaoCliente;

use Classes\LDAP\Usuario;
use const AREA_NOME;
use const AREA_SIGLA;
use const SISTEMA_NOME;
use const SISTEMA_RAIZ;
use const SISTEMA_SIGLA;
use const SISTEMA_VERSAO;

/**
 *
 * @author c068442
 *
 */
class Layout {

    private static $Usuario;


    /**
     * Gera cabeçalho da págia, &lt;!DOCTYPE html&gt; Scripts de Estilo e inicializa o &lt;body&gt;
     *
     * @return string
     *
     */
    public static function getHead() {

        self::$Usuario = new Usuario();

        $head = "<!DOCTYPE html>" . PHP_EOL;
        $head .= "<html lang='pt-BR' class='h-100'>" . PHP_EOL;
        $head .= "<head>" . PHP_EOL;
        $head .= "<meta charset='UTF-8'>" . PHP_EOL;
        $head .= "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>" . PHP_EOL;
        $head .= "<meta name='description' content=''>" . PHP_EOL;
        $head .= "<meta name='author' content='Wesley Alves da Silva Santos | 068442-9'>" . PHP_EOL;
        $head .= "<meta name='theme-color' content='#317EFB'/>" . PHP_EOL;
        $head .= "<link rel='icon' href='/favicon.ico'>" . PHP_EOL;
        $head .= "<title>" . AREA_SIGLA . ' - ' . AREA_NOME . "</title>" . PHP_EOL;
        $head .= self::carregarEstilos() . "</head>" . PHP_EOL;
        $head .= "<body class='d-flex flex-column h-100'>" . PHP_EOL;

        echo $head;
    }

    /**
     * Gera menu de navegação da página
     *
     * @return string
     */
    public static function getMenu() {

        // Aplicar aria-label aos links. Ex.: aria-label="Read more about Seminole tax hike"
        $menu = '<header>' . PHP_EOL;

        $menu .= self::menuSuperior();

        $menu .= self::menuInferior();

        $menu .= '</header>' . PHP_EOL;

        echo $menu;
    }

    /**
     * Gera o rodapé, inclui os arquivos javascript.
     *
     * @return string
     */
    public static function getFoot() {
        $footer = '<footer class="footer mt-auto">' . PHP_EOL;
        $footer .= '    <div class="d-flex align-items-center bd-highlight mb-0" style=" height: 100%">' . PHP_EOL;
        $footer .= '       <span>2020 <?php echo $unidade7017 ?> - Centralizadora Nacional de Manutenção de Operações Bancárias </span>' . PHP_EOL;
        $footer .= '       <span style="margin-left:30px;">Versão 1.0.0</span>' . PHP_EOL;
        $footer .= '    </div>' . PHP_EOL;
        $footer .= '</div>' . PHP_EOL;
        $footer .= '</footer>' . PHP_EOL;        
        $footer .= self::carregarScripts();

        echo $footer;
    }

    /**
     * Encerra a página com , &lt;/body&gt; e &lt;/html&gt;.
     *
     * @return string
     */
    public static function getFim() {
        echo "</body>" . PHP_EOL . "</html>" . PHP_EOL;
    }

    private static function menuSuperior() {

        $menu = '  <nav class=" navbar navbar-expand-lg navbar-dark"  id="navbar-sistema-superior">' . PHP_EOL;
        $menu .= '      <div class="navbar" style="width: 100%;">' . PHP_EOL;
        $menu .= '          <div class="navbar-text">' . PHP_EOL;
        $menu .= '              <a class="navbar-brand" id="sigla-sistema" style="width: 100%;" href="' . SISTEMA_RAIZ . '/index.php" title="' . SISTEMA_SIGLA . '" aria-label="' . SISTEMA_NOME . '">' . SISTEMA_SIGLA . '</a>' . PHP_EOL;
        $menu .= '          </div >' . PHP_EOL;
        $menu .= '          <div class="navbar-text">' . PHP_EOL;
        $menu .= '              ' . self::$Usuario->getNome() . '<img class="ml-2 user-icon" src="http://tedx.caixa/lib/asp/foto.asp?Matricula=' . self::$Usuario->getCodigoUsuario() . '" width="36" height="36" alt="' . self::$Usuario->getNome() . '">' . PHP_EOL;
        $menu .= '          </div >' . PHP_EOL;
        $menu .= '      </div >' . PHP_EOL;
        $menu .= '  </nav>' . PHP_EOL;

        return $menu;
    }

    public static function menuInferior() {
        $menu = '  <nav class="navbar navbar-expand-lg navbar-dark" id="navbar-sistema-inferior">' . PHP_EOL;
        $menu .= '      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBarSiged" aria-controls="navBarSiged" aria-expanded="false" aria-label="Toggle navigation">' . PHP_EOL;
        $menu .= '          <span class="navbar-toggler-icon"></span>' . PHP_EOL;
        $menu .= '      </button>' . PHP_EOL;
        $menu .= '      <div class="collapse navbar-collapse" id="navBarSiged" style="background-color: #005CA9;">' . PHP_EOL;
        $menu .= '          <ul class="navbar-nav mr-auto">' . PHP_EOL;
        $menu .= '              <li class="nav-item nav-suban">' . PHP_EOL;
        $menu .= '                  <a class="nav-link menu-superior" href="' . SISTEMA_RAIZ . '/index.php" aria-label="Página inicial do sistema">Início <span class="sr-only">(current)</span></a>' . PHP_EOL;
        $menu .= '              </li>' . PHP_EOL;

        $menu .= self::subMenuInferior();

        $menu .= '      </div>' . PHP_EOL;
        $menu .= '  </nav>' . PHP_EOL;

        return $menu;
    }

    public static function subMenuInferior() {

        $menu = '';

        foreach (self::menus() as $Key => $ItemMenu) {

            if ($ItemMenu['itens'] === null) {
                $menu .= self::subMenuInferiorSemItens($Key, $ItemMenu);
            } else {
                $menu .= self::subMenuInferiorComItens($Key, $ItemMenu);
            }
        }

        return $menu;
    }

    private static function subMenuInferiorComItens(int $Key, array $ItemMenu) {

        $menu = "              <li class='nav-item dropdown'>" . PHP_EOL;
        $menu .= "                  <a class='nav-link menu-superior dropdown-toggle nav-suban' href='#' id='navbar{$Key}' "
                . "role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' "
                . "aria-label='{$ItemMenu['aria-label']}'>{$ItemMenu['nome']}</a>" . PHP_EOL;
        $menu .= "                      <div class='dropdown-menu' aria-labelledby='navbar{$Key}'>" . PHP_EOL;

        foreach ($ItemMenu['itens'] as $ItemSubMenu) {
            $Url = SISTEMA_RAIZ . $ItemSubMenu['url'];
            $menu .= "                          <a class='dropdown-item' href='{$Url}' aria-label='{$ItemSubMenu['aria-label']}'>{$ItemSubMenu['nome']}</a>";
        }

        $menu .= '                      </div>' . PHP_EOL;
        $menu .= '              </li>' . PHP_EOL;

        return $menu;
    }

    private static function subMenuInferiorSemItens(int $Key, array $ItemMenu) {
        
        $Url = SISTEMA_RAIZ . $ItemMenu['url'];
        
        $menu = "              <li class='nav-item active'>" . PHP_EOL;
        $menu .= "                   <a class='nav-link nav-suban' href='{$Url}' "
                . "id='navbar{$Key}' role='button' aria-haspopup='true' aria-expanded='false' "
                . "aria-label='{$ItemMenu['aria-label']}'>{$ItemMenu['nome']}</a>" . PHP_EOL;
        $menu .= '              </li>' . PHP_EOL;

        return $menu;
    }

    /**
     * Gera scripts JS da página
     *
     * @return string
     */
    private static function carregarScripts() {

        $script = '';

        foreach (self::arrayJS() as $ArquivoJS) {
            $script .= "<script src='{$ArquivoJS}' type='text/javascript'></script>" . PHP_EOL;
        }

        return $script;
    }

    /**
     * Gera scripts CSS da página
     *
     * @return string
     */
    private static function carregarEstilos() {

        $estilo = '';

        foreach (self::arrayCSS() as $ArquivoCSS) {
            $estilo .= "<link href='{$ArquivoCSS}' rel='stylesheet'>" . PHP_EOL;
        }

        return $estilo;
    }

    private static function arrayCSS() {
        return [
            '/node_modules/bootstrap/dist/css/bootstrap.min.css',
            '/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
            '/node_modules/@fortawesome/fontawesome-free/css/all.css',
            '/css/suban.css',
            '/css/fonts.css',
        ];
    }

    private static function arrayJS() {
        return [
            '/node_modules/jquery/dist/jquery.min.js',
            '/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
            '/node_modules/jquery-mask-plugin/dist/jquery.mask.min.js',
            '/node_modules/datatables.net/js/jquery.dataTables.min.js',
            '/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
            '/node_modules/@fortawesome/fontawesome-free/js/all.js',
            '/node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js',
            '/js/jQuery-CPF-CNPJ-Validator-plugin/jquery.cpfcnpj.js',
            '/js/jquery.popup.js',
            '/js/date-uk.js',
        ];
    }

    private static function menus() {
        return [
            [
                'nome' => 'Visão do Cliente',
                'aria-label' => 'Acessar visão unica do cliente, com pesquisa de clientes e contratos',
                'url' => '/index.php',
                'itens' => null
            ],
            [
                'nome' => 'Informações',
                'aria-label' => 'Opções para cadastrar, consultar ou alterar informações para os clientes',
                'url' => '#',
                'itens' => [
                    ['nome' => 'Coordenações', 'aria-label' => 'cadastrar ou alterar coordenações do sistema', 'url' => '/parametros/coordenacoes/listar.php'],
                    ['nome' => 'Supervisões', 'aria-label' => 'cadastrar ou alterar supervisões do sistema', 'url' => '/parametros/supervisoes/listar.php'],
                ]
            ],
        ];
    }



}

//EOF
