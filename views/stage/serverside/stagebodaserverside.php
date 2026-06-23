<?php
ob_start();

try {
    include_once '../../../utils/dbaccess.php';
    require_once("../../../utils/datatables_helper.php");
    $dbAccess = new DbAccess();
    $con = $dbAccess->getConnection();

    $allowedTables = ['activebodausers', 'inactivebodausers', 'defaultedbodausers', 'bodauser'];
    $table = in_array($_GET['table'] ?? '', $allowedTables, true) ? $_GET['table'] : 'activebodausers';
    $name = mysqli_real_escape_string($con, $_GET['stagename'] ?? '');

    $sql = "SELECT * FROM `{$table}` WHERE stageName='{$name}'";
    $totalQuery = mysqli_query($con, $sql);
    if (!$totalQuery) {
        throw new RuntimeException(mysqli_error($con));
    }
    $total_all_rows = mysqli_num_rows($totalQuery);

    if (!empty($_POST['search']['value'])) {
        $search_value = $_POST['search']['value'];
        $sql .= " AND (bodaUserName like '%" . $search_value . "%'";
        $sql .= " OR bodaUserPhoneNumber like '%" . $search_value . "%'";
        $sql .= " OR bodaUserBodaNumber like '%" . $search_value . "%'";
        $sql .= " OR fuelStationName like '%" . $search_value . "%'";
        $sql .= " OR stageName like '%" . $search_value . "%'";
        $sql .= " OR alternativePhotoNumber like '%" . $search_value . "%'";
        $sql .= " OR bodaUserRole like '%" . $search_value . "%')";
    }

    $sql .= datatables_order_clause(
        $_POST['order'] ?? null,
        ['bodaUserId', 'bodaUserName', 'bodaUserNIN', 'bodaUserBodaNumber', 'bodaUserRole', 'bodaUserStatus', 'fuelStationName', 'stageName'],
        'bodaUserId ASC'
    );

    if (isset($_POST['length']) && $_POST['length'] !== null && (int) $_POST['length'] !== -1) {
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
        $statusLabel = match ((int) $row['bodaUserStatus']) {
            0 => "<span style='background-color:#1c478e;border-radius:5px; padding:5px; color:#fff;'>Inactive</span>",
            1 => "<span style='background-color:green;border-radius:5px; padding:5px; color:#fff;'>Active</span>",
            2 => "<span style='background-color:#997400;border-radius:5px; padding:5px; color:#fff;'>Pending payment</span>",
            3 => "<span style='background-color:red;border-radius:5px; padding:5px; color:#fff;'>Suspended</span>",
            default => 'N/A',
        };

        $data[] = [
            $row['bodaUserId'],
            $row['bodaUserName'],
            $row['bodaUserNIN'],
            $row['bodaUserBodaNumber'],
            $row['bodaUserRole'],
            $statusLabel,
            $row['fuelStationName'],
            $row['stageName'],
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
