<?php

namespace Classes\Sistema;

use Classes\Entity\UsuarioOnline;
use Classes\Helper\EntityManagerFactory;
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
    private static $QuantidadeUsuariosOnline;
	private static $EmpregadosAutorizados;

    /**
     * Gera cabeçalho da págia, &lt;!DOCTYPE html&gt; Scripts de Estilo e inicializa o &lt;body&gt;
     *
     * @return string
     *
     */
    public static function getHead()
    {


        $EntityManagerFactory = new EntityManagerFactory();
        $EntityManager = $EntityManagerFactory->getEntityManager();

        $UsuarioOnline = $EntityManager->getRepository(UsuarioOnline::class);

        self::$QuantidadeUsuariosOnline = $UsuarioOnline->getQuantidadeUsuariosOnline();
        self::$Usuario = $UsuarioOnline->getUsuario();
		
		/* $EmpregadosAutorizados = ['c055790', 'c068442',
		 'c103336', 'c096213', 'c086592', 'c125901', 'c050396', 'c051484', 'c037399', 'c083933', 'c128447', 'c105764', 'c055790'];
		
		 if ( ! in_array(strtolower(self::$Usuario->getCodigoUsuario()), $EmpregadosAutorizados)) {
		 header("Location: http://www.cemco.sp.caixa/sistemas/VisaoCliente/MANUTENCAO.php");
		 }*/

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
    public static function getMenu()
    {

        // Aplicar aria-label aos links. Ex.: aria-label="Read more about Seminole tax hike"
        $menu = '<header>' . PHP_EOL;

        $menu .= self::menuSuperior();

        $menu .= self::menuInferior();




        $menu .= '</header>' . PHP_EOL;

        echo $menu;
    }

    private static function contarUsuariosOnline(): string
    {


        return '<span class="form-inline my-2 my-lg-0">' . PHP_EOL
                . self::$QuantidadeUsuariosOnline . ' Usuário(s) Online' . PHP_EOL
                . '</span>' . PHP_EOL;
    }

    /**
     * Gera o rodapé, inclui os arquivos javascript.
     *
     * @return string
     */
    public static function getFoot()
    {
        $footer = '<footer class="footer mt-auto">' . PHP_EOL;
        $footer .= '    <div class="d-flex align-items-center bd-highlight mb-0" style=" height: 100%">' . PHP_EOL;
        $footer .= '       <span> ' .date("Y") .' - Centralizadora Nacional de Manutenção de Operações Bancárias </span>' . PHP_EOL;
        $footer .= '       <span style="margin-left:30px;">Versão 1.0.0</span>' . PHP_EOL;
		$footer .= '       <span style="margin-left:30px;">#INTERNO.CAIXA</span>' . PHP_EOL;
		
		$footer .= '       <span style="margin-left:30px;">' . self::$Usuario->getCodigoUsuario() . '</span>' . PHP_EOL;
		
		
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
    public static function getFim()
    {
        echo "</body>" . PHP_EOL . "</html>" . PHP_EOL;
    }

    private static function menuSuperior()
    {

        $menu = '  <nav class=" navbar navbar-expand-lg navbar-dark"  id="navbar-sistema-superior">' . PHP_EOL;
        $menu .= '      <div class="navbar" style="width: 100%;">' . PHP_EOL;
        $menu .= '          <div class="navbar-text">' . PHP_EOL;
        $menu .= '              <a class="navbar-brand" id="sigla-sistema" style="width: 100%;" href="' . SISTEMA_RAIZ . '/index.php" title="' . SISTEMA_SIGLA . '" aria-label="' . SISTEMA_NOME . '">' . SISTEMA_SIGLA . '</a>' . PHP_EOL;
        $menu .= '          </div >' . PHP_EOL;
		$menu .= '       <div style="margin-left:30px;">#INTERNO.CAIXA</div>' . PHP_EOL;
        $menu .= '          <div class="navbar-text">' . PHP_EOL;
        $menu .= '              ' . self::$Usuario->getNome() . '<img class="ml-2 user-icon" src="http://tedx.caixa/lib/asp/foto.asp?Matricula=' . self::$Usuario->getCodigoUsuario() . '" width="36" height="36" >' . PHP_EOL;
        $menu .= '          </div >' . PHP_EOL;
        $menu .= '      </div >' . PHP_EOL;
        $menu .= '  </nav>' . PHP_EOL;

        return $menu;
    }

    public static function menuInferior()
    {
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
        $menu .= '          </ul>' . PHP_EOL;
        $menu .= '      </div>' . PHP_EOL;
        $menu .= self::contarUsuariosOnline();
        $menu .= '  </nav>' . PHP_EOL;

        return $menu;
    }

    public static function subMenuInferior()
    {

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

    private static function subMenuInferiorComItens(int $Key, array $ItemMenu)
    {

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

    private static function subMenuInferiorSemItens(int $Key, array $ItemMenu)
    {

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
    private static function carregarScripts()
    {

        $script = '';

        foreach (self::arrayJS() as $ArquivoJS) {
            $script .= "<script type='text/javascript' src='{$ArquivoJS}'></script>" . PHP_EOL;
        }

        return $script;
    }

    /**
     * Gera scripts CSS da página
     *
     * @return string
     */
    private static function carregarEstilos()
    {

        $estilo = '';

        foreach (self::arrayCSS() as $ArquivoCSS) {
            $estilo .= "<link rel='stylesheet' href='{$ArquivoCSS}'>" . PHP_EOL;
        }

        return $estilo;
    }

    private static function arrayCSS()
    {
        return [
            '/node_modules/bootstrap/dist/css/bootstrap.min.css',
            '/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
            '/node_modules/@fortawesome/fontawesome-free/css/all.css',
            '/css/suban.css',
            '/css/fonts.css',
        ];
    }

    private static function arrayJS()
    {
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
            'http://www.cemob.hom.sp.caixa/sistemas/PainelSite/cemob-simon/js',
        ];
    }

    private static function menus()
    {
        $Menu[] = [
                    'nome' => 'Visão do Cliente',
                    'aria-label' => 'Acessar visão unica do cliente, com pesquisa de clientes e contratos',
                    'url' => '/index.php',
                    'itens' => null
        ];

       $UnidadesAutorizadas = ['7017', '5517', '5032'];
	   $UsuariosAutorizados = ['c128454', 'c083915', 'c088702', 'c123290', 'c130452', 'c096170', 'c123538', 'c037546', 'c080723', 'c022139', 'c126645', 'c125811', 'c096216', 'C052438', 'c085968'];



        if (in_array(self::$Usuario->getCodigoUnidade(), $UnidadesAutorizadas)) {

            $Menu[] = [
                'nome' => 'Informações',
                'aria-label' => 'Opções para cadastrar, consultar ou alterar informações para os clientes',
                'url' => '#',
                'itens' => [
                    ['nome' => 'Cadastrar', 'aria-label' => 'Cadastrar avisos e informações para clientes', 'url' => '/informacoes/informacoes-formulario.php'],
                    ['nome' => 'Minhas Informações', 'aria-label' => 'Visualizar suas informações e avisos', 'url' => '/informacoes/informacoes-listar.php'],
                ]
            ];
            $Menu[] = [
                'nome' => 'Demandas',
                'aria-label' => 'Cadastrar uma demanda de atendimento',
                'url' => '#',
                'itens' => [
                    ['nome' => 'Cadastrar', 'aria-label' => 'Cadastrar uma demanda de atendimento', 'url' => '/demandas/demanda-formulario.php'],
                ]
            ];
            $Menu[] = [
                'nome' => 'Paramêtros',
                'aria-label' => 'Paramêtros do Sistema',
                'url' => '#',
                'itens' => [
                    ['nome' => 'Grupos Base', 'aria-label' => 'Cadastrar grupos de bases', 'url' => '/parametros/bases-listar.php'],
                    ['nome' => 'Tipos de Serviços', 'aria-label' => 'Cadastrar tipos de atendimentos', 'url' => '/parametros/tipo-servico-listar.php'],
                ]
            ];
        }
		
        if (in_array(self::$Usuario->getCodigoUsuario(), $UsuariosAutorizados)) {
            $Menu[2]['itens'][] = ['nome' => 'Exportar Excel', 'aria-label' => 'Exportar registros via arquivo do Excel', 'url' => '/ExportaExcel.php'];
        }		
/* echo "<pre>";
var_dump($Menu);
exit(); */
		
        return $Menu;
    }

    static function getCodigoUsuario()
    {
        return self::$Usuario->getCodigoUsuario();
    }

}

//EOF
