<?php
ob_start();

try {
    include("../../utils/dbaccess.php");
    $dbAccess = new DbAccess();
    $con = $dbAccess->getConnection();

    $sql = "SELECT payments.*, bodauser.bodaUserName, loan.boadUserId AS loanPhone
            FROM payments
            LEFT JOIN loan ON loan.loanId = payments.loanId
            LEFT JOIN bodauser ON bodauser.bodaUserPhoneNumber = loan.boadUserId";

    $totalQuery = mysqli_query($con, $sql);
    if (!$totalQuery) {
        throw new RuntimeException('Total query failed: ' . mysqli_error($con));
    }
    $total_all_rows = mysqli_num_rows($totalQuery);

    $filteredSql = $sql;
    if (!empty($_POST['search']['value'])) {
        $search_value = mysqli_real_escape_string($con, $_POST['search']['value']);
        $filteredSql .= " WHERE payments.amount LIKE '%{$search_value}%'
            OR payments.msisdn LIKE '%{$search_value}%'
            OR payments.narrative LIKE '%{$search_value}%'
            OR payments.status LIKE '%{$search_value}%'
            OR bodauser.bodaUserName LIKE '%{$search_value}%'
            OR loan.boadUserId LIKE '%{$search_value}%'";
    }

    $countQuery = mysqli_query($con, $filteredSql);
    if (!$countQuery) {
        throw new RuntimeException('Count query failed: ' . mysqli_error($con));
    }
    $recordsFiltered = mysqli_num_rows($countQuery);

    if (!empty($_POST['order'][0]['column'])) {
        $columns = ['bodaUserName', 'status', 'narrative', 'msisdn', 'amount', 'updated_at'];
        $columnIndex = (int) $_POST['order'][0]['column'];
        $orderDir = strtoupper($_POST['order'][0]['dir']) === 'ASC' ? 'ASC' : 'DESC';
        $columnName = $columns[$columnIndex] ?? 'id';

        if ($columnName === 'bodaUserName') {
            $filteredSql .= " ORDER BY bodauser.bodaUserName {$orderDir}";
        } elseif ($columnName === 'updated_at') {
            $filteredSql .= " ORDER BY COALESCE(payments.updated_at, payments.paymentDate, payments.created_at) {$orderDir}";
        } else {
            $filteredSql .= " ORDER BY payments.{$columnName} {$orderDir}";
        }
    } else {
        $filteredSql .= " ORDER BY payments.id DESC";
    }

    if (isset($_POST['length']) && (int) $_POST['length'] !== -1) {
        $start = (int) ($_POST['start'] ?? 0);
        $length = (int) $_POST['length'];
        $filteredSql .= " LIMIT {$start}, {$length}";
    }

    $query = mysqli_query($con, $filteredSql);
    if (!$query) {
        throw new RuntimeException('Database query failed: ' . mysqli_error($con));
    }

    function formatMobileNumber($number)
    {
        if ($number === null || $number === '') {
            return '';
        }

        $newNumber = $number;
        if (strpos($number, '+256') !== false) {
            $newNumber = str_replace('+256', '0', $number);
        }
        if (strpos($number, '256') === 0) {
            $newNumber = str_replace('256', '0', $number);
        }
        return $newNumber;
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $phone = $row['msisdn'] ?: $row['loanPhone'];
        $formattedPhone = formatMobileNumber($phone);

        $name = $row['bodaUserName'];
        if (!$name && $formattedPhone) {
            $bodaUsers = $dbAccess->select('bodauser', ['bodaUserName'], ['bodaUserPhoneNumber' => $formattedPhone]);
            $name = !empty($bodaUsers) ? $bodaUsers[0]['bodaUserName'] : null;
        }

        $status = $row['status'];
        if (!$status && !empty($row['paymentMethod'])) {
            $status = 'completed';
        }
        if (!$status) {
            $status = 'pending';
        }

        $reason = $row['narrative'] ?: ($row['paymentMethod'] ?: '-');
        $paidOn = $row['updated_at'] ?: ($row['paymentDate'] ?: $row['created_at']);

        $data[] = [
            $name ?: 'N/A',
            $status,
            $reason,
            $phone ?: '-',
            $row['amount'],
            $paidOn,
        ];
    }

    $output = [
        'draw' => intval($_POST['draw'] ?? 0),
        'recordsTotal' => $total_all_rows,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ];

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($output);
} catch (Throwable $th) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => $th->getMessage()]);
}
