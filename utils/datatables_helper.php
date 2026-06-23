<?php

function datatables_order_clause(?array $order, array $columns, string $default = 'id DESC'): string
{
    if (!isset($order[0]['column'])) {
        return ' ORDER BY ' . $default;
    }

    $index = (int) $order[0]['column'];
    $defaultParts = preg_split('/\s+/', trim($default), 2);
    $column = $columns[$index] ?? $defaultParts[0];
    $dir = (isset($order[0]['dir']) && strtolower($order[0]['dir']) === 'asc') ? 'ASC' : 'DESC';

    return " ORDER BY {$column} {$dir}";
}

function datatables_json_response(array $output): void
{
    if (ob_get_level() > 0) {
        ob_clean();
    }
    header('Content-Type: application/json');
    echo json_encode($output);
}

function datatables_json_error(Throwable $e): void
{
    datatables_json_response([
        'draw' => intval($_POST['draw'] ?? 0),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => $e->getMessage(),
    ]);
}

function datatables_begin(): void
{
    if (ob_get_level() === 0) {
        ob_start();
    }
}
