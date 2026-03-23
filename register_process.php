<?php
// Enable CORS for Render
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to validate Philippines mobile number
function isValidPhilippinesNumber($phone) {
    $cleanPhone = preg_replace('/[\s\-\(\)]/', '', $phone);
    if (!preg_match('/^09\d{9}$/', $cleanPhone)) {
        return false;
    }
    
    $prefix = substr($cleanPhone, 0, 4);
    $validPrefixes = [
        "0915", "0916", "0917", "0918", "0919", "0920", "0921", "0922", "0923", "0924",
        "0925", "0926", "0927", "0928", "0929", "0930", "0931", "0932", "0933", "0934",
        "0935", "0936", "0937", "0938", "0939", "0945", "0946", "0947", "0948", "0949",
        "0950", "0951", "0956", "0960", "0961", "0965", "0966", "0967", "0970", "0975",
        "0976", "0977", "0978", "0981", "0989", "0998", "0999", "0905", "0906", "0907",
        "0908", "0909", "0910", "0911", "0912", "0913", "0914"
    ];
    return in_array($prefix, $validPrefixes);
}

// Get data from different sources
$username = '';
$email = '';
$phone = '';
$age = 0;
$password = '';

// Check if data is sent via POST (FormData)
if (!empty($_POST)) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
    $password = isset($_POST['password']) ? $_POST['password'] : '';
}
// Check if data is sent via JSON
else {
    $raw_input = file_get_contents('php://input');
    $json_data = json_decode($raw_input, true);
    if ($json_data) {
        $username = isset($json_data['username']) ? trim($json_data['username']) : '';
        $email = isset($json_data['email']) ? trim($json_data['email']) : '';
        $phone = isset($json_data['phone']) ? trim($json_data['phone']) : '';
        $age = isset($json_data['age']) ? intval($json_data['age']) : 0;
        $password = isset($json_data['password']) ? $json_data['password'] : '';
    }
}

// Debug log (optional - remove in production)
error_log("Register attempt - Username: $username, Email: $email, Phone: $phone");

// Validate data
if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username is required']);
    exit;
}

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

if (empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Phone number is required']);
    exit;
}

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

if ($age < 13 || $age > 100) {
    echo json_encode(['success' => false, 'message' => 'Age must be between 13 and 100']);
    exit;
}

if (!isValidPhilippinesNumber($phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid Philippines mobile number. Must be 11 digits starting with 09 (e.g., 09171234567)']);
    exit;
}

// TODO: Add database connection here
// For now, we'll simulate successful registration
// In production, you would save to a database

echo json_encode([
    'success' => true,
    'message' => 'Registration successful! You can now login.',
    'user' => [
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'age' => $age
    ]
]);
?>
