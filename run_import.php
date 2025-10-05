<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Real Geographic Data - Sunfuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .import-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
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
        <div class="import-container">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">🌍 Import Real Uganda Geographic Data</h3>
                </div>
                <div class="card-body">
                    <p class="lead">This will import the actual Uganda geographic data from your provided SQL file.</p>
                    
                    <div class="alert alert-info">
                        <strong>What this will do:</strong>
                        <ul class="mb-0">
                            <li>Create tables with the correct structure matching your data</li>
                            <li>Import all districts, counties, subcounties, parishes, and villages</li>
                            <li>Update the territory_districts table for the form</li>
                            <li>Fix the fetch files to use correct table names</li>
                        </ul>
                    </div>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_data'])) {
                        echo '<div class="card mt-3">';
                        echo '<div class="card-header"><h5>Import Progress</h5></div>';
                        echo '<div class="card-body">';
                        
                        // Include the import script
                        ob_start();
                        include 'import_real_geographic_data.php';
                        $output = ob_get_clean();
                        
                        // Display the output
                        echo $output;
                        
                        echo '</div>';
                        echo '</div>';
                    } else {
                    ?>
                    
                    <form method="POST">
                        <div class="d-grid gap-2">
                            <button type="submit" name="import_data" class="btn btn-success btn-lg">
                                🚀 Import Real Geographic Data
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <h6>What's Different This Time:</h6>
                        <ul>
                            <li>Uses your actual SQL file with real Uganda data</li>
                            <li>Creates tables with the correct structure</li>
                            <li>Imports thousands of real districts, counties, subcounties, parishes, and villages</li>
                            <li>Updates the form's fetch files to work with the correct table names</li>
                        </ul>
                    </div>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
