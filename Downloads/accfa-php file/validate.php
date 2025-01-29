<?php
// CORS setup to allow requests from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow Authorization header
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get the incoming JSON request
$data = json_decode(file_get_contents("php://input"), true);

// Validate email and password input fields
if (empty($data['userEmail']) || empty($data['password'])) {
    echo json_encode([
        'success' => '0',
        'message' => 'Some fields are empty. Please try again!'
    ]);
    exit;
}

// Sanitize email and password
$useremail = filter_var($data['userEmail'], FILTER_SANITIZE_EMAIL);
$userpassword = htmlspecialchars($data['password']);

// Validate email format
if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => '0',
        'message' => 'Invalid email format!'
    ]);
    exit;
}

$userdetails = array(
    'useremail' => $useremail,
    'userpassword' => $userpassword
);

// Attempt to log in the user
$success = loginUser($userdetails);

if (!empty($success)) {
    echo json_encode([
        'success' => '1',
        'message' => 'Login successfully!',
        'details' => $success
    ]);
} else {
    echo json_encode([
        'success' => '0',
        'message' => 'Invalid credentials!'
    ]);
}

function loginUser($userdetails) {
    require './connect.php';  // Ensure this file has proper DB connection

    // Query the database for the user with the provided email
    $stmt = $pdo->prepare("SELECT * FROM tblusers WHERE useremail = :useremail");
    $stmt->execute(['useremail' => $userdetails['useremail']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;

    // Check if user exists and verify the password
    if ($user && password_verify($userdetails['userpassword'], $user['userpassword'])) {
        return $user; // Return user details if password is correct
    }

    // Return null if credentials are incorrect
    return null; 
}
?>
