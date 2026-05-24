<?php session_start(); ?>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Додаємо підключення до бази даних
$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// Перевірка підключення (про всяк випадок)
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="uk">
... (далі весь ваш код без змін)

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>М'ясо і Вогонь | Стейкхаус</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap');

        html { scroll-behavior: smooth; }
        body { margin: 0; padding: 0; background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, .logo { font-family: 'Playfair Display', serif; }
        a { text-decoration: none; color: inherit; }

        header { position: fixed; top: 0; width: 100%; background-color: rgba(17, 17, 17, 0.95); padding: 20px 50px; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; z-index: 1000; border-bottom: 1px solid #333; }
        .logo { font-size: 24px; color: #FF7900; font-weight: bold; }
        .nav-links { display: flex; gap: 30px; }
        .nav-links a { transition: color 0.3s; }
        .nav-links a:hover { color: #FF7900; }
        
        .auth-buttons { display: flex; gap: 15px; align-items: center; }
        .btn-outline { border: 1px solid #FF7900; color: #FF7900; padding: 10px 20px; border-radius: 4px; transition: 0.3s; font-weight: 600; }
        .btn-outline:hover { background: #FF7900; color: #000; }
        .btn-primary { background-color: #FF7900; color: #000; padding: 10px 20px; font-size: 16px; font-weight: 600; border: none; border-radius: 4px; cursor: pointer; transition: 0.3s; display: inline-block; text-align: center; }
        .btn-primary:hover { background-color: #e66a00; }

        .hero { height: 100vh; background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1920&q=80') center/cover; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 0 20px; }
        .hero h1 { font-size: 64px; margin-bottom: 20px; }
        .hero p { max-width: 600px; font-size: 18px; line-height: 1.6; margin-bottom: 40px; }
        .hero .btn-primary { padding: 15px 40px; font-size: 18px; }

        .section-block { padding: 100px 50px; text-align: center; }
        .bg-dark { background-color: #1a1a1a; }
        .bg-darker { background-color: #111; }
        
        .section-block h2 { font-size: 42px; color: #FF7900; margin-bottom: 50px; }
        .section-desc { font-size: 18px; max-width: 800px; margin: 0 auto 40px; line-height: 1.6; color: #ccc; }

        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto 40px; }
        .menu-card { background: #222; padding-bottom: 20px; border-radius: 8px; overflow: hidden; border: 1px solid #333; }
        .menu-card img { width: 100%; height: 250px; object-fit: cover; }
        .menu-card h3 { margin: 15px 0 5px; font-size: 18px; }
        .menu-card .price { color: #FF7900; font-weight: 600; font-size: 20px; }

        .reviews-grid { display: flex; gap: 30px; justify-content: center; max-width: 1200px; margin: 0 auto; flex-wrap: wrap; }
        .review-card { background: #222; padding: 30px; border-radius: 8px; flex: 1; min-width: 300px; border: 1px solid #333; text-align: left; }
        .review-card p { font-style: italic; color: #ddd; line-height: 1.6; margin-bottom: 20px; }
        .review-card h4 { color: #FF7900; margin: 0; font-size: 18px; }

        .booking { display: flex; flex-direction: column; align-items: center; }
        .form-container { width: 100%; max-width: 800px; background: #222; padding: 50px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #333; }
        .form-container h2 { margin-top: 0; }
        .input-row { display: flex; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        input { background-color: #111; border: 1px solid #444; color: white; padding: 15px; font-size: 16px; width: 100%; box-sizing: border-box; border-radius: 4px; }
        input:focus { outline: none; border-color: #FF7900; }
        .half-width { flex: 1; }

        footer { text-align: center; padding: 30px; background-color: #000; font-size: 14px; color: #666; border-top: 1px solid #222; }

        @media (max-width: 768px) {
            header { padding: 15px 20px; flex-direction: column; gap: 15px; }
            .nav-links { flex-wrap: wrap; justify-content: center; }
            .input-row { flex-direction: column; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">М'ясо і Вогонь</div>
        <nav class="nav-links">
            <a href="#home">Головна</a>
            <a href="#about">Про нас</a>
            <a href="menu.php">Меню</a>
            <a href="delivery.php" style="color: #FF7900;">Доставка</a>
            <a href="#loyalty">Лояльність</a>
            <a href="#reviews">Відгуки</a>
        </nav>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="btn-primary">Мій кабінет</a>
            <?php else: ?>
                <a href="login.html" class="btn-outline">Вхід</a>
                <a href="registration.html" class="btn-primary">Реєстрація</a>
            <?php endif; ?>
        </div>
    </header>

    <section id="home" class="hero">
        <h1>М'ясо і Вогонь</h1>
        <p>Ресторан найсмачнішого м'яса у Львові. Тільки свіжі продукти, відкритий вогонь та справжня українська гостинність.</p>
        <a href="#booking" class="btn-primary">Зарезервувати стіл</a>
    </section>

    <section id="about" class="section-block bg-dark">
        <h2>Про нас</h2>
        <div style="max-width: 1000px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 50px; text-align: left;">
            
            <!-- Текстова частина -->
            <div style="flex: 1; min-width: 300px;">
                <h3 style="color: #FF7900; margin-top: 0; font-size: 24px;">Стейкхаус «М'ясо і Вогонь»</h3>
                <p style="color: #ccc; line-height: 1.6; font-size: 16px;">Ми спеціалізуємося на приготуванні преміальних відрубів на відкритому вогні. Ресторан використовує виключно локальне фермерське та імпортне м'ясо високої якості. Концепція закладу об'єднує класичні традиції барбекю, лаконічний темний інтер'єр та уважний сервіс.</p>
            </div>

            <!-- Контакти та графік -->
            <div style="flex: 1; min-width: 300px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <h4 style="color: #fff; margin-top: 0; margin-bottom: 10px; font-family: 'Montserrat', sans-serif; font-size: 16px;">Адреса</h4>
                    <p style="color: #aaa; margin: 0; line-height: 1.5; font-size: 15px;">м. Львів, вул. Староєфремова, 2<br><span style="font-size: 13px; color: #777;">Безкоштовна гостьова парковка</span></p>
                </div>
                <div>
                    <h4 style="color: #fff; margin-top: 0; margin-bottom: 10px; font-family: 'Montserrat', sans-serif; font-size: 16px;">Графік роботи</h4>
                    <p style="color: #aaa; margin: 0; line-height: 1.5; font-size: 15px;">Щодня<br>8:00 – 23:00</p>
                </div>
                <div>
                    <h4 style="color: #fff; margin-top: 0; margin-bottom: 10px; font-family: 'Montserrat', sans-serif; font-size: 16px;">Зв'язок</h4>
                    <p style="color: #aaa; margin: 0; line-height: 1.5; font-size: 15px;">
                        <a href="tel:+380981112233" style="color: #FF7900; text-decoration: none; transition: 0.3s;">+38 (098) 111-22-33</a><br>
                        <a href="mailto:info@meatandfire.lviv.ua" style="color: #aaa; text-decoration: none; transition: 0.3s;">info@meatandfire.ua</a>
                    </p>
                </div>
                <div>
                    <h4 style="color: #fff; margin-top: 0; margin-bottom: 10px; font-family: 'Montserrat', sans-serif; font-size: 16px;">Соцмережі</h4>
                    <p style="color: #aaa; margin: 0; line-height: 1.5; font-size: 15px;">
                        <a href="#" style="color: #FF7900; text-decoration: none; margin-right: 10px;">Instagram - @meatandfire</a><br>
                        <a href="#" style="color: #FF7900; text-decoration: none;">Facebook - @meatandfire</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="section-block bg-darker">
    <h2>Шеф-кухар рекомендує</h2>
    <p style="text-align: center; color: #e6b381; margin-bottom: 40px; font-size: 1.1em;">
        Переглянути ціни ви можете в нашому меню.
    </p>
    <div class="menu-grid">
        <div class="menu-card">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80" alt="Бріскет">
            <h3>Телячий бріскет</h3>
        </div>
        <div class="menu-card">
            <img src="https://images.unsplash.com/photo-1514516345957-556ca7d90a29?auto=format&fit=crop&w=500&q=80" alt="Каре">
            <h3>Каре ягняти</h3>
        </div>
        <div class="menu-card">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=500&q=80" alt="Ребра">
            <h3>Свинячі ребра BBQ</h3>
        </div>
        <div class="menu-card">
            <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=500&q=80" alt="Стейк">
            <h3>Стейк Рібай (Prime)</h3>
        </div>
    </div>
    <a href="menu.php" class="btn-outline">Відкрити повне меню</a>
</section>

    <section id="loyalty" class="section-block bg-dark">
        <h2>Програма лояльності</h2>
        <p class="section-desc">Зареєструйтесь на сайті та отримуйте 10% кешбеку з кожного замовлення. Бонусами можна розрахуватися при наступних візитах. Вся історія накопичень зберігається у вашому особистому кабінеті.</p>
        <a href="registration.html" class="btn-primary">Приєднатися до програми</a>
    </section>

    <section id="reviews" style="padding: 60px 20px;">
    <h2 style="text-align: center; font-family: 'Playfair Display'; color: #FF7900;">Відгуки наших гостей</h2>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin-bottom: 40px;">
        <?php
        // Витягуємо останні 3 відгуки з бази
        $res = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 3");
        while($row = $res->fetch_assoc()) {
            echo '<div style="background: #1a1a1a; padding: 25px; border-radius: 8px; border: 1px solid #333; width: 300px;">';
            echo '<p style="font-style: italic; color: #ccc;">"' . htmlspecialchars($row['review_text']) . '"</p>';
            echo '<h4 style="color: #FF7900; margin-top: 15px;">' . htmlspecialchars($row['author_name']) . '</h4>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Форма додавання відгуку -->
    <div style="max-width: 600px; margin: 0 auto; background: #1a1a1a; padding: 30px; border-radius: 8px;">
        <h3 style="color: #fff; margin-bottom: 20px;">Залишити відгук</h3>
        <form action="submit_review.php" method="POST">
            <?php if(isset($_SESSION['user_name'])): ?>
                <p style="color: #888;">Ви пишете як: <b style="color: #FF7900;"><?php echo $_SESSION['user_name']; ?></b></p>
            <?php else: ?>
                <p style="color: #888;">Ви не авторизовані. Ваш відгук буде <b style="color: #FF7900;">анонімним</b>.</p>
            <?php endif; ?>
            
            <textarea name="review_text" required style="width: 100%; background: #222; border: 1px solid #444; color: #fff; padding: 15px; border-radius: 4px; height: 100px; margin-bottom: 15px;"></textarea>
            <button type="submit" style="background: #FF7900; color: #000; border: none; padding: 12px 25px; font-weight: bold; border-radius: 4px; cursor: pointer;">Опублікувати</button>
        </form>
    </div>
</section>


    <section id="booking" class="section-block bg-dark booking">
        <div class="form-container">
            <h2>Бронювання столика</h2>
            <form action="booking.php" method="POST">
                <div class="input-row">
                    <input type="text" name="guest_name" class="half-width" placeholder="Ваше ім'я" required>
                    <input type="tel" name="guest_phone" class="half-width" placeholder="Телефон" required>
                </div>
                <div class="input-row">
                    <input type="number" name="guests_count" class="half-width" placeholder="Кількість гостей" min="1" max="20" required>
                    <input type="datetime-local" name="reservation_date" class="half-width" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">Забронювати</button>
            </form>
        </div>
    </section>

    <footer>
        © 2026 Ресторан «М'ясо і Вогонь». Усі права захищено.
    </footer>

</body>
</html>