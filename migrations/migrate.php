<?php
/**
 * Sunfuel Migration Runner
 * 
 * This script runs all database migrations in the correct order.
 * Usage: php migrate.php
 */

require_once '../utils/dbaccess.php';

class MigrationRunner
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
            if ($filename !== 'migrate.php') {
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
        echo "✓ Executed migration: {$migration}\n";
    }
    
    /**
     * Run all pending migrations
     */
    public function runMigrations()
    {
        echo "Starting Sunfuel Database Migrations...\n";
        echo "==========================================\n\n";
        
        $migrations = $this->getMigrationFiles();
        $executed = 0;
        $skipped = 0;
        
        foreach ($migrations as $migration) {
            if ($this->isMigrationExecuted($migration)) {
                echo "⏭ Skipped migration: {$migration} (already executed)\n";
                $skipped++;
            } else {
                try {
                    $this->executeMigration($migration);
                    $executed++;
                } catch (Exception $e) {
                    echo "❌ Error executing migration {$migration}: " . $e->getMessage() . "\n";
                    echo "Stopping migration process.\n";
                    return false;
                }
            }
        }
        
        echo "\n==========================================\n";
        echo "Migration Summary:\n";
        echo "✓ Executed: {$executed} migrations\n";
        echo "⏭ Skipped: {$skipped} migrations\n";
        echo "🎉 All migrations completed successfully!\n";
        
        return true;
    }
    
    /**
     * Show migration status
     */
    public function showStatus()
    {
        echo "Sunfuel Migration Status\n";
        echo "==========================\n\n";
        
        $migrations = $this->getMigrationFiles();
        
        foreach ($migrations as $migration) {
            $status = $this->isMigrationExecuted($migration) ? '✓ Executed' : '⏳ Pending';
            echo "{$status} - {$migration}\n";
        }
    }
    
    /**
     * Reset all migrations (DANGEROUS - use with caution)
     */
    public function resetMigrations()
    {
        echo "⚠️  WARNING: This will drop all tables and reset migrations!\n";
        echo "Are you sure you want to continue? (yes/no): ";
        
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim($line) !== 'yes') {
            echo "Migration reset cancelled.\n";
            return;
        }
        
        // Drop all tables
        $tables = ['user_totals_per_day', 'package', 'deposits', 'payments', 'loan', 'bodauser', 'stage', 'inactivefuelstation', 'activefuelstation', 'fuelstation', 'territory_districts', 'territories', 'role_modules', 'role_permissions', 'users', 'features', 'permissions', 'roles', 'migrations'];
        
        foreach ($tables as $table) {
            $this->db->getConnection()->query("DROP TABLE IF EXISTS {$table}");
        }
        
        // Drop database
        $this->db->getConnection()->query("DROP DATABASE IF EXISTS sunfuel");
        
        echo "✓ Database reset completed. You can now run migrations again.\n";
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $runner = new MigrationRunner();
    
    $command = $argv[1] ?? 'run';
    
    switch ($command) {
        case 'run':
            $runner->runMigrations();
            break;
        case 'status':
            $runner->showStatus();
            break;
        case 'reset':
            $runner->resetMigrations();
            break;
        default:
            echo "Usage: php migrate.php [run|status|reset]\n";
            echo "  run    - Execute all pending migrations (default)\n";
            echo "  status - Show migration status\n";
            echo "  reset  - Reset all migrations (DANGEROUS)\n";
            break;
    }
} else {
    echo "This script must be run from the command line.\n";
}
?>
