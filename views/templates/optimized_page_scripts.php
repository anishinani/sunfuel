<?php

/***
 * Loading the relevant javascript for each view of your application
 * @author ThinkXSoftware
 * 
 * **/


/**
 * 
 * mandatory javascript
 */

?>
<!-- jQuery -->
<script src="/creditpluswebapp/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="/creditpluswebapp/dist/js/adminlte.min.js"></script>
<?php
/**
 * end mandatory css and head links
 * **/

// create filters for each page
/**
 *@var $script_name current executing script or view file loading 
 **/
$script_name =  trim(strtolower($_SERVER['SCRIPT_NAME']));

$batches = array(
    'tablePageScripts' => [
        '/creditpluswebapp/views/users/index.php',
        '/creditpluswebapp/views/roles/index.php',
        '/creditpluswebapp/views/stage/index.php',
        '/creditpluswebapp/views/stage/activeStages.php',
        '/creditpluswebapp/views/fuelstation/index.php',
        '/creditpluswebapp/views/bodauser/index.php',
        '/creditpluswebapp/views/packages/index.php',
        '/creditpluswebapp/views/deposits/index.php',
        '/creditpluswebapp/views/payments/index.php',
        '/creditpluswebapp/views/territories/index.php',








    ],
    'formPageScripts' => [],

    'detailPageScripts' => []
);

if (in_array($script_name, $batches['tablePageScripts'])) {
?>

    <!-- DataTables -->
    <script src="/creditpluswebapp/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/jszip/jszip.min.js"></script>
    <script src="/creditpluswebapp/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/creditpluswebapp/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<?php
}

if (in_array($script_name, $batches['detailPageScripts'])) {
?>

    <!-- load detail support scripts -->

<?php
}
if (in_array($script_name, $batches['formPageScripts'])) {
?>

    <!-- load form supporting scripts -->

<?php
}
