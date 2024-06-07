<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Entity\UnidadeCaixa;
use Classes\Helper\EntityManagerFactory;
use Classes\LDAP\Usuario;

$Usuario = new Usuario();

$DataAtual = new \DateTimeImmutable();

$DataFim = $DataAtual;

//Data Inicial = 6 meses atrás
$DataInicio = $DataAtual->sub(new DateInterval('P6M'));

//Carrega o entity manager.
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();

$VicePresidenciasAtivas = $EntityManager->getRepository(UnidadeCaixa::class)->findBy(['TipoUnidade' => 'VP', 'Situacao' => 'Ativa']);
foreach ($VicePresidenciasAtivas as $VP) {
    $Array[] = "<a href='?UnidadeCaixaID={$VP->getID()}'>{$VP->getTipoUnidade()} {$VP->getNome()}</a><br>";
}

require_once '__cabecalho.php';
?>

<link rel="stylesheet" href="/node_modules/jstree/dist/themes/default/style.min.css" />
<link rel="stylesheet" href="/js/jstree-bootstrap-theme/dist/themes/proton/style.min.css" />


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Acessos: Por Unidade</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
<!--                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
                    </ol>-->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="form-group row w-50">
                <label for="tree_q" class="col-sm-2 col-form-label">Pesquisar</label>
                <div class="col-sm-10">
                    <input type="email" name="tree_q" id="tree_q" class="form-control">
                </div>
            </div>

            <div id="tree"></div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
require_once '__rodape.php';
?>

<script type='text/javascript' src='/node_modules/datatables.net/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='/node_modules/jstree/dist/jstree.min.js'></script>
<script type='text/javascript' src='/js/jstree-bootstrap-theme/dist/jstree.min.js'></script>


<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip({html: true});

        $('#tree').jstree({
            "plugins": ["search", "wholerow"],
            'core': {
                'themes': {
                    'name': 'proton',
                    'responsive': true
                },
                'data': {
                    'url': function (node) {
                        return node.id === '#' ?
                                'dados/acessos-unidade.php' :
                                'dados/acessos-unidade.php';
                    },
                    'data': function (node) {
                        return {'UnidadeCaixaID': node.id};
                    }
                }
            }

        });

        var to = false;
        $('#tree_q').keyup(function () {
            if (to) {
                clearTimeout(to);
            }
            to = setTimeout(function () {
                var v = $('#tree_q').val();
                $('#tree').jstree(true).search(v);
            }, 250);
        });



//https://www.jstree.com/
//https://github.com/jvanbruegge/tree-selector#readme
//https://github.com/vakata/jstree-php-demos/blob/master/sitebrowser/index.php

    });
</script>