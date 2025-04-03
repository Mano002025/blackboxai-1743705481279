<?php
require_once 'config.php';

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function login($email, $password) {
    global $pdo;
    
    $sql = "SELECT id, password FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
    }
    return false;
}

function logout() {
    session_destroy();
    redirect('login.php');
}

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>