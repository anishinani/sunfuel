<?php
ob_start();

try {
    include("../../utils/dbaccess.php");
    require_once("../../utils/datatables_helper.php");
    $dbAccess = new DbAccess();
    $con = $dbAccess->getConnection();

    $allowedTables = ['activefuelstation', 'inactivefuelstation', 'fuelstation'];
    $table = in_array($_GET['table'] ?? '', $allowedTables, true) ? $_GET['table'] : 'activefuelstation';
    $name = mysqli_real_escape_string($con, $_GET['name'] ?? '');

    $sql = "SELECT * FROM `{$table}`";
    if ($name !== '') {
        $sql .= " WHERE fuelStationName='{$name}'";
    }

    $totalQuery = mysqli_query($con, $sql);
    if (!$totalQuery) {
        throw new RuntimeException(mysqli_error($con));
    }
    $total_all_rows = mysqli_num_rows($totalQuery);

    if (!empty($_POST['search']['value'])) {
        $search_value = $_POST['search']['value'];
        $connector = stripos($sql, ' WHERE ') !== false ? ' AND ' : ' WHERE ';
        $sql .= $connector . "(stageName like '%" . $search_value . "%'";
        $sql .= " OR fuelStationName like '%" . $search_value . "%'";
        $sql .= " OR stageStatus like '%" . $search_value . "%')";
    }

    $sql .= datatables_order_clause(
        $_POST['order'] ?? null,
        ['fuelStationId', 'fuelStationName', 'fuelStationContactPerson', 'fuelStationAddress', 'fuelStationContactPhone', 'fuelStationStatus'],
        'fuelStationId ASC'
    );

    if (isset($_POST['length']) && (int) $_POST['length'] !== -1) {
        $start = (int) ($_POST['start'] ?? 0);
        $length = (int) $_POST['length'];
        $sql .= " LIMIT {$start}, {$length}";
    }

    $query = mysqli_query($con, $sql);
    if (!$query) {
        throw new RuntimeException(mysqli_error($con));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = [
            $row['stageName'] ?? ($row['fuelStationName'] ?? 'N/A'),
            $row['fuelStationName'] ?? 'N/A',
            ((int) ($row['stageStatus'] ?? $row['fuelStationStatus'] ?? 1) === 0) ? 'Not Active' : 'Active',
            '',
            '',
        ];
    }

    datatables_json_response([
        'draw' => intval($_POST['draw'] ?? 0),
        'recordsTotal' => $total_all_rows,
        'recordsFiltered' => $total_all_rows,
        'data' => $data,
    ]);
} catch (Throwable $e) {
    datatables_json_error($e);
}
