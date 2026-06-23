<?php
ob_start();

try {
    session_start();
    include("../../utils/dbaccess.php");
    require_once("../../utils/datatables_helper.php");
    $dbAccess = new DbAccess();
    $con = $dbAccess->getConnection();

    $sql = "SELECT * FROM user_totals_per_day";
    $totalQuery = mysqli_query($con, $sql);
    if (!$totalQuery) {
        throw new RuntimeException(mysqli_error($con));
    }
    $total_all_rows = mysqli_num_rows($totalQuery);

    if (!empty($_POST['search']['value'])) {
        $search_value = $_POST['search']['value'];
        $sql .= " WHERE total_users like '%" . $search_value . "%'";
        $sql .= " OR total_loans like '%" . $search_value . "%'";
        $sql .= " OR total_deposits like '%" . $search_value . "%'";
        $sql .= " OR date like '%" . $search_value . "%'";
    }

    $sql .= datatables_order_clause(
        $_POST['order'] ?? null,
        ['id', 'user_id', 'total_users', 'total_loans', 'total_deposits', 'date'],
        'id ASC'
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
            $row['id'],
            $row['total_users'],
            $row['total_loans'],
            $row['total_deposits'],
            $row['date'],
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
