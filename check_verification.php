<?php
require_once 'includes/db.php';

$response = ['verified' => false];

if (isset($_COOKIE['auth'])) {
  list($userId, $signature) = explode('|', $_COOKIE['auth']);
  define('SECRET_KEY', 'your_secret_key');
  if (hash_equals(hash_hmac('sha256', $userId, SECRET_KEY), $signature)) {
    $stmt = $pdo->prepare("SELECT email_verified FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    $response['verified'] = $user && $user['email_verified'];
  }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
