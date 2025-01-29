<?php
require './connect.php';

$useremail = $_POST['useremail'];
$password = $_POST['password'];

$response = array();

if (empty($useremail) || empty($password)) {
    $response['success'] = "0";
    $response['message'] = "Email or password is missing!";
    echo json_encode($response);
    die();
}

$stmt = $pdo->prepare("SELECT * FROM accfausers WHERE useremail = ?");
$stmt->execute([$useremail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (password_verify($password, $user['userpassword'])) {
        $response['success'] = "1";
        $response['message'] = "Login successful!";
        $response['username'] = $user['username'];
    } else {
        $response['success'] = "0";
        $response['message'] = "Incorrect password!";
    }
} else {
    $response['success'] = "0";
    $response['message'] = "User not found!";
}

echo json_encode($response);
?>
