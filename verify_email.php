<?php
session_start();
require_once 'includes/db.php';

// Проверка, авторизован ли пользователь
if (!isset($_COOKIE['auth'])) {
  header("Location: login.php");
  exit;
}

list($userId, $signature) = explode('|', $_COOKIE['auth']);
define('SECRET_KEY', 'your_secret_key');
if (!hash_equals(hash_hmac('sha256', $userId, SECRET_KEY), $signature)) {
  header("Location: login.php");
  exit;
}

// Проверка email-подтверждения в БД
$stmt = $pdo->prepare("SELECT email_verified FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($user && $user['email_verified']) {
  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Подтверждение email | EduPro Academy</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<header>
  <h1>EduPro Academy</h1>
</header>

<main class="main-centered">
  <div class="form-container" style="text-align:center">
    <h2>Подтвердите вашу почту</h2>
    <p>Мы отправили письмо на ваш email. Пожалуйста, перейдите по ссылке из письма для завершения регистрации.</p>
    <div style="margin-top: 20px">
      <button class="btn" onclick="manualCheck()">Я подтвердил — проверить</button>
      <button class="btn outline" onclick="resendEmail()">Отправить письмо повторно</button>
      <a href="register.php" class="btn outline">Вернуться к регистрации</a>
    </div>
    <div id="status" style="margin-top:15px; color: green"></div>
  </div>
</main>

<footer>
  <div class="footer-columns">
    <div class="footer-column">
      <h4>EduPro</h4>
      <a href="index.php">О проекте</a>
      <a href="index.php#features">Курсы</a>
      <a href="index.php">Контакты</a>
    </div>
    <div class="footer-column">
      <h4>Правовая информация</h4>
      <a href="settings.php#legal">Политика конфиденциальности</a>
      <a href="settings.php#legal">Условия использования</a>
    </div>
    <div class="footer-column">
      <h4>Мы в соцсетях</h4>
      <a href="https://t.me/your_channel" target="_blank">Telegram</a>
      <a href="https://vk.com/your_page" target="_blank">VK</a>
    </div>
  </div>
  <div style="text-align:center">© 2025 EduPro Academy. Все права защищены.</div>
</footer>

<script>
function manualCheck() {
  fetch('check_verification.php')
    .then(res => res.json())
    .then(data => {
      if (data.verified) {
        window.location.href = 'dashboard.php';
      } else {
        document.getElementById('status').textContent = 'Email ещё не подтверждён.';
      }
    });
}

function resendEmail() {
  fetch('resend_verification.php')
    .then(res => res.json())
    .then(data => {
      document.getElementById('status').textContent = data.message;
    });
}

// Автоматическая проверка каждые 10 секунд
setInterval(manualCheck, 10000);
</script>
</body>
</html>
