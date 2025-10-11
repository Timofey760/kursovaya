<?php
header('Content-Type: application/json');
session_start();
require_once 'db_connect.php'; // Подключение к базе данных
require_once 'log.php';
logMessage("Start login");
$data = json_decode(file_get_contents('php://input'), true);

$login = $data['login'] ?? '';
$password = $data['password'] ?? '';

if (empty($login) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Заполните все поля.']);
    logMessage("Fields empty","Error");
    exit;
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        echo json_encode(['status' => 'success', 'message' => 'Вход выполнен.']);
        logMessage("Login success");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Неверный логин или пароль.']);
        logMessage("Login failed. Wrong login or password($login:$password)","Error");
        
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка сервера.']);
    logMessage("Server error","Error");
}
?>
