<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'agent') {
    header('Location: views/fuelagent/activation.php');
    exit();
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'utils/dbaccess.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        // Simple authentication - in production, use proper password hashing
        $db = new DbAccess();
        $sql = "SELECT fa.*, fs.fuelStationName 
                FROM fuelagent fa 
                LEFT JOIN fuelstation fs ON fa.stationId = fs.fuelStationId 
                WHERE fa.fuelAgentPhoneNumber = '{$username}' 
                AND fa.status = 1";
        
        $result = $db->selectQuery($sql);
        
        if (!empty($result)) {
            $agent = $result[0];
            
            // Simple password check (you should implement proper password hashing)
            // For demo purposes, we'll use phone number as password
            if ($password === $agent['fuelAgentPhoneNumber']) {
                $_SESSION['user_id'] = $agent['fuelAgentId'];
                $_SESSION['user_role'] = 'agent';
                $_SESSION['agent_name'] = $agent['fuelAgentName'];
                $_SESSION['station_name'] = $agent['fuelStationName'];
                
                header('Location: views/fuelagent/activation.php');
                exit();
            } else {
                $error = 'Invalid credentials';
            }
        } else {
            $error = 'Agent not found or not active';
        }
    } else {
        $error = 'Please enter both username and password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Agent Login - SunFuel</title>
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
        .btn-login {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-gas-pump fa-3x mb-3"></i>
            <h3>Fuel Agent Portal</h3>
            <p class="mb-0">SunFuel Activation System</p>
        </div>
        
        <div class="login-body">
            <!-- Demo Login Credentials -->
            <div class="alert alert-info mb-3">
                <h6><i class="fas fa-info-circle"></i> Demo Login Credentials:</h6>
                <small>
                    <strong>Username:</strong> 256700123456<br>
                    <strong>Password:</strong> 256700123456
                </small>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           placeholder="Enter your phone number"
                           required
                           autocomplete="off">
                </div>
                
                <div class="form-group mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password"
                           required>
                </div>
                
                <button type="submit" class="btn btn-login btn-block w-100">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Demo: Use your registered phone number as both username and password
                </small>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        // Auto-format phone number input
        $('#username').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
