

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
                <div class="p-3">
                    <h5>Title</h5>
                    <p>Sidebar content</p>
                </div>
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="float-right d-none d-sm-inline">
                    Powered By <a href="https://adminlte.io">AdminLTE.io</a>. All rights reserved.
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; <?php $DataAtual = new \Datetime(); echo $DataAtual->format('Y')  . '</strong>  ' . AREA_SIGLA . ' - ' . AREA_NOME; ?>
            </footer>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->

        <!-- jQuery -->
        <script src="/node_modules/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/node_modules/admin-lte/dist/js/adminlte.min.js"></script>
        <script src="/node_modules/chart.js/dist/Chart.js"></script>

        

    </body>
</html>
