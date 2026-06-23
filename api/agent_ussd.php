<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../utils/dbaccess.php';
require_once __DIR__ . '/../utils/PhoneHelper.php';
require_once __DIR__ . '/../controllers/FuelLoanController.php';

class AgentUSSDHandler extends DbAccess
{
    private string $phoneNumber;
    private string $sessionId;
    private string $message;
    private FuelLoanController $fuelLoanController;

    public function __construct()
    {
        parent::__construct();
        $this->fuelLoanController = new FuelLoanController();
        $this->phoneNumber = PhoneHelper::toInternational($_POST['phoneNumber'] ?? '');
        $this->sessionId = $_POST['sessionId'] ?? '';
        $this->message = trim($_POST['message'] ?? '');
    }

    public function handleRequest(): string
    {
        try {
            if ($this->phoneNumber === '') {
                return $this->sendErrorResponse('Invalid phone number.');
            }

            $agent = $this->getAgent();
            if (!$agent) {
                return $this->sendResponse(
                    "SUNFUEL AGENT\nNot registered as an active fuel agent.\nContact your station manager.",
                    true
                );
            }

            $session = $this->getOrCreateSession($agent);
            $menu = $session['currentMenu'];
            $input = $this->message;

            if ($input === '*124#' || $input === '*124') {
                return $this->showMainMenu($agent);
            }

            switch ($menu) {
                case 'agent_main':
                    return $this->handleMainMenu($input, $agent);
                case 'agent_code':
                    return $this->handleCodeEntry($input, $agent);
                case 'agent_confirm':
                    return $this->handleConfirm($input, $agent);
                default:
                    return $this->showMainMenu($agent);
            }
        } catch (Throwable $e) {
            return $this->sendErrorResponse('System error. Please try again later.');
        }
    }

    private function getAgent(): ?array
    {
        $phones = PhoneHelper::sqlInList(PhoneHelper::variants($this->phoneNumber));
        $sql = "SELECT fa.*, fs.fuelStationName, fs.currentFloat
                FROM fuelagent fa
                LEFT JOIN fuelstation fs ON fa.stationId = fs.fuelStationId
                WHERE fa.fuelAgentPhoneNumber IN ({$phones})
                AND fa.status = 1
                LIMIT 1";

        $result = $this->selectQuery($sql);

        return !empty($result) ? $result[0] : null;
    }

    private function getOrCreateSession(array $agent): array
    {
        $existing = $this->select('ussd_sessions', ['*'], [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId,
            'status' => 'active',
        ]);

        foreach ($existing as $row) {
            $userData = $row['userData'] ? json_decode($row['userData'], true) : [];
            if (($userData['handler'] ?? '') === 'agent') {
                return $row;
            }
        }

        $sessionData = [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId,
            'currentMenu' => 'agent_main',
            'userData' => json_encode([
                'handler' => 'agent',
                'agentId' => $agent['fuelAgentId'],
                'stationName' => $agent['fuelStationName'],
            ]),
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ];

        $this->insert('ussd_sessions', $sessionData);

        return $sessionData;
    }

    private function updateSession(string $menu, ?array $userData = null): void
    {
        $session = $this->getOrCreateSession($this->getAgent());
        $existingData = $session['userData'] ? json_decode($session['userData'], true) : [];
        $merged = array_merge($existingData, $userData ?? []);

        $this->update('ussd_sessions', [
            'currentMenu' => $menu,
            'userData' => json_encode($merged),
        ], [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId,
        ]);
    }

    private function showMainMenu(array $agent): string
    {
        $this->updateSession('agent_main');

        $menu = "SUNFUEL AGENT\n";
        $menu .= $agent['fuelAgentName'] . "\n";
        $menu .= $agent['fuelStationName'] . "\n\n";
        $menu .= "1. Activate Fuel Code\n";
        $menu .= "2. Station Float\n";
        $menu .= "0. Exit";

        return $this->sendResponse($menu, false);
    }

    private function handleMainMenu(string $input, array $agent): string
    {
        if ($input === '') {
            return $this->showMainMenu($agent);
        }

        switch ($input) {
            case '1':
                $this->updateSession('agent_code');
                return $this->sendResponse("ENTER CODE\nEnter the 6-digit activation code from the rider:", false);
            case '2':
                $float = number_format((float) ($agent['currentFloat'] ?? 0));
                return $this->sendResponse(
                    "STATION FLOAT\n{$agent['fuelStationName']}\nAvailable: {$float} UGX",
                    true
                );
            case '0':
                return $this->endSession('Thank you for using SunFuel Agent.');
            default:
                return $this->sendResponse('Invalid option. Please try again.', false);
        }
    }

    private function handleCodeEntry(string $input, array $agent): string
    {
        $code = preg_replace('/\D+/', '', $input);

        if (strlen($code) !== 6) {
            return $this->sendResponse('Invalid code. Enter the 6-digit activation code:', false);
        }

        $result = $this->fuelLoanController->getActivationCodeDetails($code);
        if (!$result['success']) {
            return $this->sendResponse($result['message'] . "\n\nEnter another code or 0 to exit:", false);
        }

        $data = $result['data'];
        if ((int) $data['fuelStationId'] !== (int) $agent['stationId']) {
            return $this->sendResponse(
                "This code is not for your station.\nEnter another code:",
                false
            );
        }

        $this->updateSession('agent_confirm', [
            'activationCode' => $code,
            'activationPreview' => $data,
        ]);

        $menu = "CONFIRM DISPATCH\n";
        $menu .= "Rider: {$data['userName']}\n";
        $menu .= "Phone: {$data['userPhone']}\n";
        $menu .= "Fuel: " . number_format($data['fuelAmount']) . " UGX\n";
        $menu .= "Total: " . number_format($data['totalAmount']) . " UGX\n\n";
        $menu .= "1. Confirm & Dispense\n";
        $menu .= "2. Cancel";

        return $this->sendResponse($menu, false);
    }

    private function handleConfirm(string $input, array $agent): string
    {
        $session = $this->getOrCreateSession($agent);
        $userData = $session['userData'] ? json_decode($session['userData'], true) : [];
        $code = $userData['activationCode'] ?? '';

        switch ($input) {
            case '1':
                if ($code === '') {
                    return $this->showMainMenu($agent);
                }

                $result = $this->fuelLoanController->activateFuelLoan($code, $agent['fuelAgentId']);
                if ($result['success']) {
                    $data = $result['data'];
                    $response = "FUEL DISPATCHED\n";
                    $response .= "Rider: {$data['userName']}\n";
                    $response .= "Amount: " . number_format($data['amount']) . " UGX\n";
                    $response .= "Loan: " . number_format($data['totalAmount']) . " UGX\n";
                    $response .= "Loan ID: {$data['loanId']}";

                    return $this->endSession($response);
                }

                return $this->sendResponse($result['message'], true);
            case '2':
            case '0':
                return $this->showMainMenu($agent);
            default:
                return $this->sendResponse('Invalid option. 1=Confirm, 2=Cancel', false);
        }
    }

    private function endSession(string $message): string
    {
        $this->update('ussd_sessions', ['status' => 'completed'], [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId,
        ]);

        return $this->sendResponse($message, true);
    }

    private function sendResponse(string $message, bool $endSession): string
    {
        if ($endSession) {
            $this->update('ussd_sessions', ['status' => 'completed'], [
                'phoneNumber' => $this->phoneNumber,
                'sessionCode' => $this->sessionId,
            ]);
        }

        return json_encode([
            'response' => $message,
            'endSession' => $endSession,
        ]);
    }

    private function sendErrorResponse(string $message): string
    {
        return json_encode([
            'response' => $message,
            'endSession' => true,
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $handler = new AgentUSSDHandler();
    echo $handler->handleRequest();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
