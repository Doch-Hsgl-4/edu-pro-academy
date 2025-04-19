<?php
require_once 'includes/db.php';

$token = $_GET['token'] ?? '';

if ($token) {
  $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ?");
  $stmt->execute([$token]);
  $user = $stmt->fetch();

  if ($user) {
    $pdo->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?")
        ->execute([$user['id']]);

    define('SECRET_KEY', 'your_secret_key');
    $signature = hash_hmac('sha256', $user['id'], SECRET_KEY);
    setcookie('auth', $user['id'] . '|' . $signature, time() + (86400 * 7), '/');

    header("Location: dashboard.php");
    exit;
  }
}

header("Location: login.php");
exit;
?>
