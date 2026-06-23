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
<script src="/sunfuel/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/sunfuel/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="/sunfuel/dist/js/adminlte.min.js"></script>
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
        '/sunfuel/views/users/index.php',
        '/sunfuel/views/roles/index.php',
        '/sunfuel/views/stage/index.php',
        '/sunfuel/views/stage/activeStages.php',
        '/sunfuel/views/fuelstation/index.php',
        '/sunfuel/views/fuelstation/inactivefuelstations.php',
        '/sunfuel/views/fuelstation/activefuelstations.php',
        '/sunfuel/views/fuelstation/territoryfuelstations.php',
        '/sunfuel/views/fuelstation/activeoneachstage.php',
        '/sunfuel/views/bodauser/index.php',
        '/sunfuel/views/packages/index.php',
        '/sunfuel/views/deposits/index.php',
        '/sunfuel/views/deposits/float_dashboard.php',
        '/sunfuel/views/payments/index.php',
        '/sunfuel/views/territories/index.php',
        '/sunfuel/views/fuelagent/index.php',
        '/sunfuel/views/stage/territorystages.php',

    ],
    'formPageScripts' => [],

    'detailPageScripts' => []
);

if (in_array($script_name, $batches['tablePageScripts'])) {
?>

    <!-- DataTables -->
    <script src="/sunfuel/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/sunfuel/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/sunfuel/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/sunfuel/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/sunfuel/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/sunfuel/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/sunfuel/plugins/jszip/jszip.min.js"></script>
    <script src="/sunfuel/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/sunfuel/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/sunfuel/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/sunfuel/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/sunfuel/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
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
