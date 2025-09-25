<?php
/**
 * CreditPlus Web Migration Runner
 * 
 * This script provides a web interface to run database migrations.
 * Access via: http://localhost/sunfuel/migrations/web_migrate.php
 */

require_once '../utils/dbaccess.php';

class WebMigrationRunner
{
    private $db;
    private $migrationsDir;
    private $migrationsTable = 'migrations';
    
    public function __construct()
    {
        $this->db = new DbAccess();
        $this->migrationsDir = __DIR__;
        $this->createMigrationsTable();
    }
    
    /**
     * Create migrations tracking table
     */
    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->db->getConnection()->query($sql);
    }
    
    /**
     * Get list of migration files
     */
    private function getMigrationFiles()
    {
        $files = glob($this->migrationsDir . '/*.sql');
        $migrations = [];
        
        foreach ($files as $file) {
            $filename = basename($file);
            if ($filename !== 'migrate.php' && $filename !== 'web_migrate.php') {
                $migrations[] = $filename;
            }
        }
        
        sort($migrations);
        return $migrations;
    }
    
    /**
     * Check if migration has been executed
     */
    private function isMigrationExecuted($migration)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->migrationsTable} WHERE migration = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("s", $migration);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    /**
     * Mark migration as executed
     */
    private function markMigrationExecuted($migration)
    {
        $sql = "INSERT INTO {$this->migrationsTable} (migration) VALUES (?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("s", $migration);
        $stmt->execute();
    }
    
    /**
     * Execute a single migration file
     */
    private function executeMigration($migration)
    {
        $filePath = $this->migrationsDir . '/' . $migration;
        
        if (!file_exists($filePath)) {
            throw new Exception("Migration file not found: {$migration}");
        }
        
        $sql = file_get_contents($filePath);
        
        // Split SQL by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $result = $this->db->getConnection()->query($statement);
                if (!$result) {
                    throw new Exception("Migration failed: " . $this->db->getConnection()->error . "\nStatement: " . $statement);
                }
            }
        }
        
        $this->markMigrationExecuted($migration);
        return "✓ Executed migration: {$migration}";
    }
    
    /**
     * Run all pending migrations
     */
    public function runMigrations()
    {
        $migrations = $this->getMigrationFiles();
        $results = [];
        $executed = 0;
        $skipped = 0;
        
        foreach ($migrations as $migration) {
            if ($this->isMigrationExecuted($migration)) {
                $results[] = "⏭ Skipped migration: {$migration} (already executed)";
                $skipped++;
            } else {
                try {
                    $result = $this->executeMigration($migration);
                    $results[] = $result;
                    $executed++;
                } catch (Exception $e) {
                    $results[] = "❌ Error executing migration {$migration}: " . $e->getMessage();
                    break;
                }
            }
        }
        
        return [
            'success' => true,
            'executed' => $executed,
            'skipped' => $skipped,
            'results' => $results
        ];
    }
    
    /**
     * Show migration status
     */
    public function getStatus()
    {
        $migrations = $this->getMigrationFiles();
        $status = [];
        
        foreach ($migrations as $migration) {
            $status[] = [
                'migration' => $migration,
                'executed' => $this->isMigrationExecuted($migration)
            ];
        }
        
        return $status;
    }
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $runner = new WebMigrationRunner();
        
        switch ($_POST['action']) {
            case 'run':
                $result = $runner->runMigrations();
                echo json_encode($result);
                break;
            case 'status':
                $status = $runner->getStatus();
                echo json_encode(['success' => true, 'status' => $status]);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunfuel Database Migrations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .button {
            background: #007cba;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        .button:hover {
            background: #005a87;
        }
        .button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .status-table th,
        .status-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .status-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-executed {
            color: #28a745;
        }
        .status-pending {
            color: #ffc107;
        }
        .results {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            max-height: 300px;
            overflow-y: auto;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success {
            color: #155724;
            background: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Sunfuel Database Migrations</h1>
        
        <div style="text-align: center; margin-bottom: 30px;">
            <button class="button" onclick="runMigrations()">Run All Migrations</button>
            <button class="button" onclick="checkStatus()">Check Status</button>
        </div>
        
        <div id="loading" class="loading" style="display: none;">
            <p>⏳ Processing migrations...</p>
        </div>
        
        <div id="results"></div>
        
        <div id="status-container">
            <h3>Migration Status</h3>
            <table class="status-table" id="status-table">
                <thead>
                    <tr>
                        <th>Migration File</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="status-body">
                    <!-- Status will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').innerHTML = '';
        }
        
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }
        
        function showMessage(message, type = 'success') {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `<div class="${type}">${message}</div>`;
        }
        
        function runMigrations() {
            showLoading();
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=run'
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    let message = `<h3>Migration Results</h3>`;
                    message += `<p><strong>Executed:</strong> ${data.executed} migrations</p>`;
                    message += `<p><strong>Skipped:</strong> ${data.skipped} migrations</p>`;
                    message += `<div class="results">`;
                    
                    data.results.forEach(result => {
                        message += `<div>${result}</div>`;
                    });
                    
                    message += `</div>`;
                    
                    document.getElementById('results').innerHTML = message;
                    
                    // Refresh status
                    checkStatus();
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('Error: ' + error.message, 'error');
            });
        }
        
        function checkStatus() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=status'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('status-body');
                    tbody.innerHTML = '';
                    
                    data.status.forEach(item => {
                        const row = tbody.insertRow();
                        const cell1 = row.insertCell(0);
                        const cell2 = row.insertCell(1);
                        
                        cell1.textContent = item.migration;
                        
                        if (item.executed) {
                            cell2.innerHTML = '<span class="status-executed">✓ Executed</span>';
                        } else {
                            cell2.innerHTML = '<span class="status-pending">⏳ Pending</span>';
                        }
                    });
                }
            })
            .catch(error => {
                showMessage('Error checking status: ' + error.message, 'error');
            });
        }
        
        // Load status on page load
        window.onload = function() {
            checkStatus();
        };
    </script>
</body>
</html>
