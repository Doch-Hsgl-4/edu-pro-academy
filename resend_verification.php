<?php
require_once 'includes/db.php';

$response = ['message' => ''];

if (isset($_COOKIE['auth'])) {
  list($userId, $signature) = explode('|', $_COOKIE['auth']);
  define('SECRET_KEY', 'your_secret_key');
  if (hash_equals(hash_hmac('sha256', $userId, SECRET_KEY), $signature)) {
    $stmt = $pdo->prepare("SELECT email, username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
      $token = bin2hex(random_bytes(32));
      $url = "https://yourdomain.com/verify_email_link.php?token=$token";

      $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?")
          ->execute([$token, $userId]);

      $subject = "Подтверждение email | EduPro Academy";
      $message = "Здравствуйте, {$user['username']}!\n\nПерейдите по ссылке для подтверждения email:\n$url\n\nЕсли вы не регистрировались, проигнорируйте это письмо.";

      mail($user['email'], $subject, $message);
      $response['message'] = 'Письмо отправлено повторно.';
    }
  }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
