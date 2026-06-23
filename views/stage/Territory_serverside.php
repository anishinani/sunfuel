<?php
ob_start();

try {
    include("../../utils/dbaccess.php");
    require_once("../../utils/datatables_helper.php");
    include("../../controllers/TerritoryController.php");

    $tc = new TerritoryController();
    $territoryId = (int) ($_GET['territory'] ?? 0);
    $territory = $territoryId ? $tc->getTerritory($territoryId) : null;
    $con = $tc->getConnection();

    $sql = "SELECT stage.*, fuelstation.fuelStationName
            FROM stage
            INNER JOIN fuelstation ON stage.fuelStationId = fuelstation.fuelStationId";

    if ($territory && !empty($territory['districts'])) {
        $districtIds = array_map('intval', array_column($territory['districts'], 'id'));
        $districtIds = array_filter($districtIds);
        if (!empty($districtIds)) {
            $sql .= ' WHERE fuelstation.districtCode IN (' . implode(',', $districtIds) . ')';
        }
    } elseif ($territoryId > 0) {
        $sql .= ' WHERE stage.territoryId = ' . $territoryId;
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
        $sql .= " OR stageStatus like '%" . $search_value . "%'";
        $sql .= " OR fuelStationName like '%" . $search_value . "%')";
    }

    $sql .= datatables_order_clause(
        $_POST['order'] ?? null,
        ['stageName', 'fuelStationName', 'stageStatus'],
        'stageId DESC'
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
            $row['stageName'],
            $row['fuelStationName'],
            ((int) $row['stageStatus'] === 0) ? 'Not Active' : 'Active',
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
