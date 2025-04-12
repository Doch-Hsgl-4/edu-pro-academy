<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Онлайн-школа EduPro Academy — обучение с визуальными курсами, последовательной программой и сертификацией.">
    <title>EduPro Academy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

<header>
    <h1>EduPro Academy</h1>
</header>

<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content" data-aos="fade-up">
    <h2>Учись легко — с EduPro высоко!</h2>
    <p>Современная образовательная платформа с наглядными курсами и отслеживанием прогресса.</p>
    <a href="register.php" class="btn">Зарегистрироваться</a>
    <a href="login.php" class="btn outline">Войти</a>
  </div>
</section>

<section class="features" id="features">
    <div class="feature" data-aos="fade-up" data-aos-delay="100">
        <h3>Наглядное обучение</h3>
        <p>Карта курсов, визуализация знаний и простота освоения тем.</p>
    </div>
    <div class="feature" data-aos="fade-up" data-aos-delay="200">
        <h3>Учебная последовательность</h3>
        <p>Материал структурирован от простого к сложному для лёгкого понимания.</p>
    </div>
    <div class="feature" data-aos="fade-up" data-aos-delay="300">
        <h3>Индивидуальный прогресс</h3>
        <p>Следи за своим успехом и открывай новые уровни знаний.</p>
    </div>
    <div class="feature" data-aos="fade-up" data-aos-delay="400">
        <h3>Сертификаты</h3>
        <p>Получай подтверждение своих знаний по завершении курсов.</p>
    </div>
    <div class="feature" data-aos="fade-up" data-aos-delay="500">
        <h3>Поддержка и чат</h3>
        <p>Обратная связь с администрацией и экспертами прямо на платформе.</p>
    </div>
    <div class="feature" data-aos="fade-up" data-aos-delay="600">
        <h3>Интерактивные задания</h3>
        <p>Практикуйся через тесты и задачи для лучшего закрепления материала.</p>
    </div>
</section>

<section class="preview-map">
    <h3 data-aos="zoom-in">Познакомьтесь с визуальной картой курсов</h3>
    <iframe src="map.php" title="Карта курсов" loading="lazy"></iframe>
    <a href="map.php" class="map-link">Перейти к карте курсов →</a>
</section>

<footer>
    <div class="footer-columns">
        <div class="footer-column">
            <h4>EduPro</h4>
            <a href="#">О проекте</a>
            <a href="#features">Курсы</a>
            <a href="#">Контакты</a>
        </div>
        <div class="footer-column">
            <h4>Правовая информация</h4>
            <a href="#">Политика конфиденциальности</a>
            <a href="#">Условия использования</a>
        </div>
        <div class="footer-column">
            <h4>Мы в соцсетях</h4>
            <a href="https://t.me/your_channel" target="_blank">Telegram</a>
            <a href="https://vk.com/your_page" target="_blank">VK</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 EduPro Academy. Все права защищены.
    </div>
</footer>

<script>
  AOS.init({ duration: 800 });

  function resizeHero() {
    const hero = document.querySelector('.hero');
    if (hero) {
      hero.style.height = window.innerHeight + 'px';
    }
  }

  window.addEventListener('load', resizeHero);
  window.addEventListener('resize', resizeHero);
</script>

</body>
</html>
