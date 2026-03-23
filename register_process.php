<?php
// register_process.php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all received data for debugging
error_log("=== New Registration Attempt ===");
error_log("POST data: " . print_r($_POST, true));

// Get POST data (this will work with FormData)
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$age = isset($_POST['age']) ? intval($_POST['age']) : 0;
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Log extracted values
error_log("Extracted - Username: '$username', Email: '$email', Phone: '$phone', Age: $age");

// Check if data was received
if (empty($username) && empty($email) && empty($phone)) {
    echo json_encode([
        'success' => false, 
        'message' => 'No data received. Please fill out the form.',
        'received' => $_POST
    ]);
    exit;
}

// Validate Philippines number
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

// Server-side validation
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

// ============================================
// SUCCESS! Data is valid
// ============================================
error_log("✅ Registration validation passed for: $username");

// TODO: Add your database connection here
// For now, return success for testing

echo json_encode([
    'success' => true, 
    'message' => 'Registration successful! Check your email for OTP.',
    'data_received' => [
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'age' => $age
    ]
]);
exit;
?>