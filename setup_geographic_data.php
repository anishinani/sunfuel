<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Geographic Data - Sunfuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .step-card {
            margin-bottom: 20px;
        }
        .status-success {
            color: #28a745;
        }
        .status-error {
            color: #dc3545;
        }
        .status-warning {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="setup-container">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">🌍 Sunfuel Geographic Data Setup</h3>
                </div>
                <div class="card-body">
                    <p class="lead">This tool will set up the geographic data (districts, counties, subcounties, parishes, villages) for your fuel station registration system.</p>
                    
                    <div class="alert alert-info">
                        <strong>What this will do:</strong>
                        <ul class="mb-0">
                            <li>Create geographic tables (county, subcounty, parishes, villages)</li>
                            <li>Populate with Uganda's administrative divisions</li>
                            <li>Enable the fuel station registration form to work properly</li>
                        </ul>
                    </div>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup_data'])) {
                        echo '<div class="card step-card">';
                        echo '<div class="card-header"><h5>Setup Progress</h5></div>';
                        echo '<div class="card-body">';
                        
                        try {
                            require_once 'utils/dbaccess.php';
                            $dbAccess = new DbAccess();
                            
                            echo '<div class="alert alert-info">Starting geographic data setup...</div>';
                            
                            // Check if tables already exist
                            $existingTables = [];
                            try {
                                $territories = $dbAccess->select("territories");
                                $existingTables[] = "territories";
                            } catch (Exception $e) {
                                // Table doesn't exist
                            }
                            
                            try {
                                $districts = $dbAccess->select("territory_districts");
                                $existingTables[] = "territory_districts";
                            } catch (Exception $e) {
                                // Table doesn't exist
                            }
                            
                            if (count($existingTables) > 0) {
                                echo '<div class="alert alert-warning">Some tables already exist. This will add additional data.</div>';
                            }
                            
                            // Migration files to execute
                            $migrationFiles = [
                                'migrations/021_create_county_table.sql',
                                'migrations/022_create_subcounty_table.sql', 
                                'migrations/023_create_parishes_table.sql',
                                'migrations/024_create_villages_table.sql',
                                'migrations/025_seed_uganda_geographic_data.sql',
                                'migrations/026_additional_uganda_data.sql'
                            ];
                            
                            $successCount = 0;
                            $errorCount = 0;
                            
                            foreach ($migrationFiles as $file) {
                                if (file_exists($file)) {
                                    echo "<div class='mb-2'>";
                                    echo "<strong>Executing:</strong> $file ";
                                    
                                    $sql = file_get_contents($file);
                                    $statements = array_filter(array_map('trim', explode(';', $sql)));
                                    
                                    $fileSuccess = true;
                                    foreach ($statements as $statement) {
                                        if (!empty($statement) && !preg_match('/^--/', $statement)) {
                                            try {
                                                $dbAccess->execute($statement);
                                            } catch (Exception $e) {
                                                if (strpos($e->getMessage(), 'already exists') === false) {
                                                    echo "<span class='status-error'>❌ Error: " . $e->getMessage() . "</span>";
                                                    $fileSuccess = false;
                                                    $errorCount++;
                                                }
                                            }
                                        }
                                    }
                                    
                                    if ($fileSuccess) {
                                        echo "<span class='status-success'>✅ Success</span>";
                                        $successCount++;
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<div class='mb-2'><span class='status-warning'>⚠️ File not found: $file</span></div>";
                                }
                            }
                            
                            // Display summary
                            echo '<div class="alert alert-success mt-3">';
                            echo '<h5>✅ Setup Complete!</h5>';
                            echo "<p>Successfully processed: $successCount files</p>";
                            if ($errorCount > 0) {
                                echo "<p>Errors encountered: $errorCount (some may be expected if data already exists)</p>";
                            }
                            echo '</div>';
                            
                            // Show data summary
                            echo '<div class="card">';
                            echo '<div class="card-header"><h6>📊 Data Summary</h6></div>';
                            echo '<div class="card-body">';
                            
                            try {
                                $territories = $dbAccess->select("territories");
                                echo "<p><strong>Territories:</strong> " . count($territories) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Territories:</strong> 0</p>";
                            }
                            
                            try {
                                $districts = $dbAccess->select("territory_districts");
                                echo "<p><strong>Districts:</strong> " . count($districts) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Districts:</strong> 0</p>";
                            }
                            
                            try {
                                $counties = $dbAccess->select("county");
                                echo "<p><strong>Counties:</strong> " . count($counties) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Counties:</strong> 0</p>";
                            }
                            
                            try {
                                $subcounties = $dbAccess->select("subcounty");
                                echo "<p><strong>Subcounties:</strong> " . count($subcounties) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Subcounties:</strong> 0</p>";
                            }
                            
                            try {
                                $parishes = $dbAccess->select("parishes");
                                echo "<p><strong>Parishes:</strong> " . count($parishes) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Parishes:</strong> 0</p>";
                            }
                            
                            try {
                                $villages = $dbAccess->select("villages");
                                echo "<p><strong>Villages:</strong> " . count($villages) . "</p>";
                            } catch (Exception $e) {
                                echo "<p><strong>Villages:</strong> 0</p>";
                            }
                            
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="alert alert-success mt-3">';
                            echo '<h6>🎉 Ready to Use!</h6>';
                            echo '<p>Your fuel station registration form at <a href="views/fuelstation/create.php" class="alert-link">views/fuelstation/create.php</a> should now work with populated geographic data.</p>';
                            echo '</div>';
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<h5>❌ Setup Failed</h5>';
                            echo '<p>Error: ' . $e->getMessage() . '</p>';
                            echo '<p>Please check your database connection and try again.</p>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    } else {
                    ?>
                    
                    <form method="POST">
                        <div class="d-grid gap-2">
                            <button type="submit" name="setup_data" class="btn btn-primary btn-lg">
                                🚀 Setup Geographic Data
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <h6>Manual Setup (Alternative)</h6>
                        <p>If you prefer to run the setup manually, you can:</p>
                        <ol>
                            <li>Run the SQL migration files in order (021-026)</li>
                            <li>Or execute the PHP script: <code>php seed_geographic_data.php</code></li>
                        </ol>
                    </div>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
