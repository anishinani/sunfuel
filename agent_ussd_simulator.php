<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/utils/dbaccess.php';
require_once __DIR__ . '/utils/PhoneHelper.php';

$db = new DbAccess();
$fuelAgents = $db->selectQuery(
    "SELECT fa.fuelAgentId, fa.fuelAgentName, fa.fuelAgentPhoneNumber, fa.status, fs.fuelStationName
     FROM fuelagent fa
     LEFT JOIN fuelstation fs ON fa.stationId = fs.fuelStationId
     ORDER BY fa.fuelAgentName ASC"
);
$defaultPhone = !empty($fuelAgents)
    ? PhoneHelper::toInternational($fuelAgents[0]['fuelAgentPhoneNumber'])
    : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_session') {
    session_destroy();
    session_start();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'reset']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ussd') {
    header('Content-Type: application/json');

    $sessionId = $_SESSION['agent_ussd_session_id'] ?? ('agent-' . session_id());
    $_SESSION['agent_ussd_session_id'] = $sessionId;

    $postData = http_build_query([
        'phoneNumber' => $_POST['phone'] ?? $defaultPhone,
        'sessionId' => $sessionId,
        'message' => $_POST['input'] ?? '',
    ]);

    $ch = curl_init('http://localhost/sunfuel/api/agent_ussd.php');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response ?: json_encode(['response' => 'Could not reach agent USSD API.', 'endSession' => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent USSD Simulator - SunFuel</title>
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); min-height: 100vh; padding: 20px 0; }
        .ussd-container { max-width: 400px; margin: 0 auto; background: #1a1a2e; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .ussd-header { background: #0f3460; color: #38ef7d; padding: 15px; text-align: center; font-weight: bold; }
        .phone-selector { background: #16213e; color: white; padding: 15px; }
        .demo-users { display: flex; flex-direction: column; gap: 8px; margin-top: 10px; }
        .user-btn { text-align: left; white-space: normal; }
        .session-info { background: #0f3460; color: #aaa; padding: 8px 15px; font-size: 12px; text-align: center; }
        .ussd-screen { background: #000; color: #38ef7d; font-family: 'Courier New', monospace; padding: 20px; min-height: 220px; white-space: pre-wrap; font-size: 14px; line-height: 1.5; }
        .ussd-input { width: 100%; background: #16213e; border: none; color: white; padding: 12px 15px; font-size: 16px; }
        .keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: #333; }
        .key { background: #2c3e50; color: white; border: none; padding: 18px; font-size: 18px; font-weight: bold; cursor: pointer; }
        .key:hover { background: #34495e; }
        .key.primary { background: #11998e; }
        .key.danger { background: #e74c3c; }
    </style>
</head>
<body>
    <div class="ussd-container">
        <div class="ussd-header">
            <i class="fas fa-user-tie"></i> Agent USSD Simulator
        </div>

        <div class="phone-selector">
            <strong>Select Fuel Agent:</strong>
            <div class="demo-users">
                <?php if (empty($fuelAgents)): ?>
                    <p class="text-muted mb-0 small">No fuel agents in the database.</p>
                <?php else: ?>
                    <?php foreach ($fuelAgents as $index => $agent):
                        $phone = PhoneHelper::toInternational($agent['fuelAgentPhoneNumber']);
                        $statusLabel = (int) $agent['status'] === 1 ? 'Active' : 'Inactive';
                        $btnClass = $index === 0 ? 'btn-outline-success' : 'btn-outline-secondary';
                    ?>
                        <button class="btn btn-sm <?= $btnClass ?> user-btn"
                                onclick="selectPhone('<?= htmlspecialchars($phone) ?>')">
                            <?= htmlspecialchars($agent['fuelAgentName']) ?><br>
                            <small><?= htmlspecialchars($phone) ?> · <?= htmlspecialchars($agent['fuelStationName'] ?? 'No station') ?> (<?= $statusLabel ?>)</small>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mt-2">
                <button class="btn btn-warning btn-sm" onclick="resetSession()">
                    <i class="fas fa-refresh"></i> Reset Session
                </button>
                <a href="ussd_simulator.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-motorcycle"></i> Boda Rider USSD
                </a>
            </div>
        </div>

        <div class="session-info">
            <span id="sessionPhone">Phone: <?= htmlspecialchars($defaultPhone ?: 'N/A') ?></span> |
            <span id="sessionStatus">Session: Active</span>
        </div>

        <div class="ussd-screen" id="ussdScreen">Dial *124# to start

SUNFUEL AGENT
1. Activate Fuel Code
2. Station Float
0. Exit</div>

        <div class="keypad">
            <button class="key" onclick="sendInput('1')">1</button>
            <button class="key" onclick="sendInput('2')">2</button>
            <button class="key" onclick="sendInput('3')">3</button>
            <button class="key" onclick="sendInput('4')">4</button>
            <button class="key" onclick="sendInput('5')">5</button>
            <button class="key" onclick="sendInput('6')">6</button>
            <button class="key" onclick="sendInput('7')">7</button>
            <button class="key" onclick="sendInput('8')">8</button>
            <button class="key" onclick="sendInput('9')">9</button>
            <button class="key primary" onclick="sendInput('0')">0</button>
            <button class="key primary" onclick="sendInput('*')">*</button>
            <button class="key danger" onclick="sendInput('#')">#</button>
        </div>

        <input type="text" class="ussd-input" id="manualInput" placeholder="Type option or 6-digit code"
               onkeypress="if(event.key==='Enter'){sendInput(this.value.trim());this.value='';}">
    </div>

    <script>
        let currentPhone = <?= json_encode($defaultPhone) ?>;
        let sessionActive = true;

        function selectPhone(phone) {
            currentPhone = phone;
            document.getElementById('sessionPhone').textContent = 'Phone: ' + phone;
            sessionActive = true;
            document.getElementById('sessionStatus').textContent = 'Session: Active';
            makeRequest('*124#');
        }

        function sendInput(input) {
            if (!sessionActive) {
                sessionActive = true;
                makeRequest('*124#');
                return;
            }
            makeRequest(input);
        }

        function makeRequest(input) {
            const screen = document.getElementById('ussdScreen');
            screen.textContent = screen.textContent + '\n\n> ' + input + '\nProcessing...';

            fetch('agent_ussd_simulator.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=ussd&input=' + encodeURIComponent(input) + '&phone=' + encodeURIComponent(currentPhone)
            })
            .then(r => r.json())
            .then(data => {
                screen.textContent = data.response;
                sessionActive = !data.endSession;
                document.getElementById('sessionStatus').textContent =
                    sessionActive ? 'Session: Active' : 'Session: Ended';
            })
            .catch(() => {
                screen.textContent = 'Connection error. Try again.';
            });
        }

        function resetSession() {
            fetch('agent_ussd_simulator.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=reset_session'
            }).then(() => {
                sessionActive = true;
                document.getElementById('sessionStatus').textContent = 'Session: Active';
                makeRequest('*124#');
            });
        }
    </script>
</body>
</html>
