<?php
$useremail = $_POST['useremail'];
$username = $_POST['username'];
$password = $_POST['password'];
$password1 = $_POST['password1'];

$response = array();

//Check if all fieds are given
if (empty($useremail) || empty($username) || empty($password)) {
    $response['success'] = "0";
    $response['message'] = "Some fields are empty. Please try again!";
    echo json_encode($response);
    die;
}

//Check if password match
if ($password !== $password1) {
    $response['success'] = "0";
    $response['message'] = "Password mistmatch. Please try again!";
    echo json_encode($response);
    die();
}

//Check if email is a valid one
if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
    $response['success'] = "0";
    $response['message'] = "Invalid email. Please try again!";
    echo json_encode($response);
    die();
}

//Check if email exists
if (checkUserEmail($useremail)) {
    $response['success'] = "0";
    $response['message'] = "Email already exist. Please try again!";
    echo json_encode($response);
    die();
}

//Check if user name exists
if (checkUserName($username)) {
    $response['success'] = "0";
    $response['message'] = "Username already exist. Please try again!";
    echo json_encode($response);
    die();
}

$userdetails = array(
    'useremail' => $useremail,
    'username' => $username,
    'password' => $password,
);

//Insert the user into the database
if (registerUser($userdetails)) {
    $response['success'] = "1";
    $response['message'] = "User registered successfully!";
    echo json_encode($response);
} else {
    $response['success'] = "0";
    $response['message'] = "User registration failed. Please try again!";
    echo json_encode($response);
}

function registerUser($userdetails) {
    require './connect.php';
    $query = "INSERT INTO tblusers (useremail, username, password) VALUES "
            . "(:useremail, :username, :password)";
    $stmt = $pdo->prepare($query);
    return $stmt->execute($userdetails);
}

function checkUserEmail($value) {
    require './connect.php';
    $stmt = $pdo->prepare("SELECT * FROM tblusers WHERE useremail = ? ");
    $stmt->execute([$value]);
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return !empty($array);
}

function checkUserName($value) {
    require './connect.php';
    $stmt = $pdo->prepare("SELECT * FROM tblusers WHERE username = ? ");
    $stmt->execute([$value]);
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return !empty($array);
}