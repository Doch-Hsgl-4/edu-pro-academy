<?php
require_once 'includes/db.php';
require 'vendor/autoload.php'; // Подключаем PHPMailer через Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
      $url = "http://localhost/verify_email_link.php?token=$token"; // пока localhost

      $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?")
          ->execute([$token, $userId]);

      // === SMTP НАСТРОЙКИ ===
      $mail = new PHPMailer(true);

      try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'e4baf78fdef1d6';
        $mail->Password = 'd66edcf5be564f';
        $mail->Port = 2525;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@edupro.academy', 'EduPro Academy');
        $mail->addAddress($user['email'], $user['username']);
        $mail->Subject = 'Подтверждение email | EduPro Academy';
        $mail->isHTML(true);

        $mail->Body = "
          <h2>Здравствуйте, {$user['username']}!</h2>
          <p>Спасибо за регистрацию на платформе <strong>EduPro Academy</strong>.</p>
          <p>Пожалуйста, подтвердите ваш email, перейдя по ссылке ниже:</p>
          <p><a href='$url' style='display:inline-block; padding:10px 20px; background:#ff8c00; color:#fff; text-decoration:none; border-radius:6px;'>Подтвердить Email</a></p>
          <br>
          <p>Если вы не регистрировались — просто проигнорируйте это сообщение.</p>
        ";

        $mail->AltBody = "Перейдите по ссылке для подтверждения: $url";

        $mail->send();
        $response['message'] = 'Письмо с подтверждением отправлено повторно.';
      } catch (Exception $e) {
        $response['message'] = 'Ошибка при отправке письма: ' . $mail->ErrorInfo;
      }
    }
  }
}

header('Content-Type: application/json');
echo json_encode($response);
