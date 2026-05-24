<?php
session_start();

$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$message = "";
$user_data = [];
$is_logged_in = isset($_SESSION['user_id']);

// Дістаємо дані користувача для автозаповнення
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT first_name, last_name, email, phone, city, street, house_number, apartment_number FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql_user);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
    }
    $stmt->close();
}

// Обробка форми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantities = $_POST['quantities'] ?? [];
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    
    // Адреса та контакти
    $guest_name = $conn->real_escape_string($_POST['guest_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $city = $conn->real_escape_string($_POST['city']);
    $street = $conn->real_escape_string($_POST['street']);
    $house = $conn->real_escape_string($_POST['house']);
    $apartment = $conn->real_escape_string($_POST['apartment']);

    $total_price = 0;
    $order_items = [];

    // Перебираємо масив переданих страв
    foreach ($quantities as $dish_id => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $dish_id = (int)$dish_id;
            // Дістаємо актуальну ціну з бази
            $price_query = $conn->query("SELECT price FROM dishes WHERE dish_id = $dish_id");
            if ($price_query && $price_query->num_rows > 0) {
                $dish_price = $price_query->fetch_assoc()['price'];
                $total_price += ($dish_price * $qty);
                
                // Зберігаємо страву для подальшого запису
                $order_items[] = [
                    'dish_id' => $dish_id,
                    'quantity' => $qty,
                    'unit_price' => $dish_price
                ];
            }
        }
    }

    // Якщо клієнт натиснув "Замовити", але не обрав жодної страви
    if (empty($order_items)) {
        $message = "<div class='error-msg'>❌ Ви не обрали жодної страви з меню. Додайте кількість навпроти бажаних позицій.</div>";
    } else {
        $conn->begin_transaction();
        try {
            // 1. Створюємо замовлення
            $conn->query("INSERT INTO orders (total_price, status, order_date) VALUES ($total_price, 'new', NOW())");
            $order_id = $conn->insert_id;

            // 2. Додаємо всі обрані страви в деталі замовлення
            foreach ($order_items as $item) {
                $conn->query("INSERT INTO order_details (order_id, dish_id, quantity, unit_price) 
                              VALUES ($order_id, {$item['dish_id']}, {$item['quantity']}, {$item['unit_price']})");
            }

            // 3. Фіксуємо платіж
            $uid = $is_logged_in ? $_SESSION['user_id'] : "NULL";
            $conn->query("INSERT INTO payments (order_id, user_id, amount, payment_date, payment_method, city) 
                          VALUES ($order_id, $uid, $total_price, NOW(), '$payment_method', '$city')");

            // 4. Оновлюємо кешбек
            if ($is_logged_in) {
                $conn->query("UPDATE users SET loyalty_points = (SELECT IFNULL(FLOOR(SUM(amount) * 0.10), 0) FROM payments WHERE user_id = $uid) WHERE user_id = $uid");
            }

            $conn->commit();
            $message = "<div class='success-msg'>✔ Замовлення успішно оформлено! Сума до сплати: <b>{$total_price} ₴</b>. Кур'єр зателефонує вам найближчим часом.</div>";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "<div class='error-msg'>❌ Деталі помилки MySQL: " . $e->getMessage() . "</div>";
        }
}} 

// Отримуємо повне меню
$dishes_result = $conn->query("SELECT dish_id, dish_name, price, category FROM dishes ORDER BY category, dish_name");
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оформити доставку | М'ясо і Вогонь</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@600&display=swap');
        body { background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; margin: 0; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; background: #1a1a1a; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        h1 { font-family: 'Playfair Display', serif; color: #FF7900; text-align: center; margin-bottom: 30px; }
        
        .alert-warning { background: #332200; border: 1px solid #FF7900; color: #FFb300; padding: 15px; border-radius: 4px; margin-bottom: 25px; font-size: 14px; text-align: center; }
        .alert-warning a { color: #fff; text-decoration: underline; font-weight: bold; }
        
        .success-msg { background: #003300; border: 1px solid #00ff00; color: #00ff00; padding: 15px; border-radius: 4px; margin-bottom: 25px; text-align: center; }
        .error-msg { background: #330000; border: 1px solid #ff0000; color: #ff0000; padding: 15px; border-radius: 4px; margin-bottom: 25px; text-align: center; }

        label { display: block; margin-bottom: 8px; color: #ccc; font-size: 14px; }
        input[type="text"], input[type="tel"], select { background-color: #222; border: 1px solid #444; color: white; padding: 12px; font-size: 16px; width: 100%; box-sizing: border-box; border-radius: 4px; margin-bottom: 20px; }
        input:focus, select:focus { outline: none; border-color: #FF7900; }
        
        .row { display: flex; gap: 15px; }
        .col { flex: 1; }

        .dish-list { display: grid; grid-template-columns: 1fr; gap: 10px; max-height: 400px; overflow-y: auto; padding-right: 10px; margin-bottom: 25px; border: 1px solid #333; padding: 15px; border-radius: 4px; background: #151515; }
        .dish-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #222; padding-bottom: 10px; margin-bottom: 10px; }
        .dish-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .dish-info { flex: 1; }
        .dish-name { font-weight: 600; color: #fff; display: block; }
        .dish-price { color: #FF7900; font-size: 14px; }
        .dish-qty { width: 80px; background: #222; color: #fff; border: 1px solid #444; padding: 10px; text-align: center; border-radius: 4px; }

        .btn-submit { background-color: #FF7900; color: #000; padding: 15px; font-size: 18px; font-weight: 600; border: none; border-radius: 4px; cursor: pointer; width: 100%; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: #e66a00; }
        
        .back-link { display: block; text-align: center; margin-top: 25px; color: #888; text-decoration: none; transition: 0.3s; }
        .back-link:hover { color: #FF7900; }

        /* Стиль для скролбару */
        .dish-list::-webkit-scrollbar { width: 6px; }
        .dish-list::-webkit-scrollbar-thumb { background-color: #FF7900; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Оформити доставку</h1>

    <?php echo $message; ?>

    <?php if (!$is_logged_in && empty($message)): ?>
        <div class="alert-warning">
            Ви оформлюєте замовлення як гість.<br>
            <a href="login.html">Увійдіть</a> або <a href="registration.html">зареєструйтесь</a>, щоб отримати <b>10% кешбеку</b> з цієї покупки.
        </div>
    <?php endif; ?>

    <form action="delivery.php" method="POST">
        
        <h3>1. Вибір страв</h3>
        <p style="font-size: 14px; color: #888; margin-top: -10px;">Вкажіть кількість порцій навпроти бажаних страв. Порожні поля ігноруються.</p>
        <div class="dish-list">
            <?php 
            if ($dishes_result->num_rows > 0) {
                while($dish = $dishes_result->fetch_assoc()) {
                    echo "<div class='dish-item'>";
                    echo "<div class='dish-info'>";
                    echo "<span class='dish-name'>" . htmlspecialchars($dish['dish_name']) . "</span>";
                    echo "<span class='dish-price'>" . $dish['price'] . " ₴</span>";
                    echo "</div>";
                    // Передаємо ID страви як ключ масиву quantities[]
                    echo "<input type='number' name='quantities[" . $dish['dish_id'] . "]' class='dish-qty' value='0' min='0' max='50'>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <h3>2. Контактні дані та адреса</h3>
        <div class="row">
            <div class="col">
                <label>Ім'я</label>
                <input type="text" name="guest_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required>
            </div>
            <div class="col">
                <label>Телефон</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user_data['phone'] ?? (isset($_SESSION['phone']) ? $_SESSION['phone'] : '')); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Місто</label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($user_data['city'] ?? 'Львів'); ?>" required>
            </div>
            <div class="col">
                <label>Вулиця</label>
                <input type="text" name="street" value="<?php echo htmlspecialchars($user_data['street'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Будинок</label>
                <input type="text" name="house" value="<?php echo htmlspecialchars($user_data['house_number'] ?? ''); ?>" required>
            </div>
            <div class="col">
                <label>Квартира</label>
                <input type="text" name="apartment" value="<?php echo htmlspecialchars($user_data['apartment_number'] ?? ''); ?>">
            </div>
        </div>

        <h3>3. Оплата</h3>
        <select name="payment_method" required>
            <option value="cash">Готівкою кур'єру</option>
            <option value="card">Карткою кур'єру (через термінал)</option>
        </select>

        <button type="submit" class="btn-submit">Підтвердити замовлення</button>
    </form>

    <a href="fuulfirsttry.php" class="back-link">← Повернутися на головну</a>
</div>

</body>
</html>
<?php $conn->close(); ?>