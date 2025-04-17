<?php
session_start();
require_once 'includes/db.php';

$errors = [
  'username' => '',
  'email' => '',
  'password' => '',
  'confirm_password' => '',
  'profile_photo' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $username = htmlspecialchars(trim($_POST['username']));
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if (empty($username)) $errors['username'] = 'Введите имя пользователя';
  if (empty($email)) $errors['email'] = 'Введите email';
  if (empty($password)) $errors['password'] = 'Введите пароль';
  if (empty($confirm_password)) $errors['confirm_password'] = 'Повторите пароль';
  if ($password !== $confirm_password) $errors['confirm_password'] = 'Пароли не совпадают';

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
  $stmt->execute([$username]);
  if ($stmt->fetchColumn()) $errors['username'] = 'Имя пользователя занято';

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
  $stmt->execute([$email]);
  if ($stmt->fetchColumn()) $errors['email'] = 'Email уже используется';

  $photoPath = 'assets/images/default-avatar.png';
  if (!empty($_FILES['profile_photo']['name'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['profile_photo']['tmp_name']);
    finfo_close($finfo);

    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime, $allowed)) {
      $errors['profile_photo'] = 'Разрешены только изображения JPEG, PNG или GIF.';
    } else {
      $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
      $newName = uniqid() . '.' . $ext;
      $uploadDir = 'uploads/';
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
      $target = $uploadDir . $newName;
      if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {
        $photoPath = $target;
      }
    }
  }

  if (!array_filter($errors)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_photo) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword, $photoPath]);
    header("Location: login.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Регистрация | EduPro Academy</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/register.css">
</head>
<body class="auth-page">
<header>
  <h1>EduPro Academy</h1>
</header>

<main class="main-centered">
  <div class="form-container">
    <h2>Регистрация</h2>
    <p style="text-align:center; color:#666">Вперёд к знаниям!</p>
    <form action="register.php" method="post" enctype="multipart/form-data" class="form-box" novalidate>
      <label for="username">Имя пользователя:</label>
      <input type="text" name="username" id="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      <?php if ($errors['username']): ?><div class="error-msg"><?= $errors['username'] ?></div><?php endif; ?>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <?php if ($errors['email']): ?><div class="error-msg"><?= $errors['email'] ?></div><?php endif; ?>

      <label for="password">Пароль:</label>
      <input type="password" name="password" id="password">
      <?php if ($errors['password']): ?><div class="error-msg"><?= $errors['password'] ?></div><?php endif; ?>

      <label for="confirm_password">Повтор пароля:</label>
      <input type="password" name="confirm_password" id="confirm_password">
      <?php if ($errors['confirm_password']): ?><div class="error-msg"><?= $errors['confirm_password'] ?></div><?php endif; ?>

      <label for="profile_photo">Аватар (необязательно):</label>
      <img id="avatarPreview" class="avatar-preview" src="assets/images/default-avatar.png" alt="avatar" />
      <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewAvatar(event)">
      <?php if ($errors['profile_photo']): ?><div class="error-msg"><?= $errors['profile_photo'] ?></div><?php endif; ?>

      <button type="submit" name="submit" class="btn">Зарегистрироваться</button>
      <a href="login.php" class="login-link">Уже есть аккаунт? Войти</a>
    </form>
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
function previewAvatar(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('avatarPreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

['username', 'email'].forEach(field => {
  document.getElementById(field).addEventListener('blur', () => {
    const value = document.getElementById(field).value;
    if (!value) return;

    fetch(`check_unique.php?field=${field}&value=${encodeURIComponent(value)}`)
      .then(res => res.json())
      .then(data => {
        const existing = document.querySelector(`#${field} + .error-msg`);
        if (existing) existing.remove();

        if (!data.available) {
          const div = document.createElement('div');
          div.className = 'error-msg';
          div.innerText = `${field === 'username' ? 'Имя пользователя' : 'Email'} уже используется.`;
          document.getElementById(field).insertAdjacentElement('afterend', div);
        }
      });
  });
});
</script>
</body>
</html>
