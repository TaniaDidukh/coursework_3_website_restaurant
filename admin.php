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

// Захист: якщо людина не авторизована як персонал, відкидаємо на головну
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: fuulfirsttry.php");
    exit;
}

$role = $_SESSION['admin_role'] ?? 'unknown';
$admin_name = $_SESSION['admin_name'] ?? 'Працівник';

// Обробка виходу
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: fuulfirsttry.php");
    exit;
}

// Обробка кнопки "Виконано" (для менеджера - переводить в статус completed)
if (isset($_GET['complete_order_id']) && $role === 'manager') {
    $oid = (int)$_GET['complete_order_id'];
    $conn->query("UPDATE orders SET status = 'completed' WHERE order_id = $oid");
    header("Location: admin.php");
    exit;
}

// Обробка кнопки "Приготовано" (для кухаря - переводить в статус ready)
if (isset($_GET['cook_complete_id']) && $role === 'cook') {
    $oid = (int)$_GET['cook_complete_id'];
    $conn->query("UPDATE orders SET status = 'ready' WHERE order_id = $oid");
    header("Location: admin.php");
    exit;
}

// Обробка кнопки "Погоджено" (для офіціанта)
if (isset($_GET['confirm_res_id']) && $role === 'waiter') {
    $rid = (int)$_GET['confirm_res_id'];
    $conn->query("UPDATE reservations SET status = 'confirmed' WHERE reservation_id = $rid");
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Робоча панель | М'ясо і Вогонь</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@600&display=swap');
        body { background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; margin: 0; padding: 40px 20px; }
        h1, h2 { font-family: 'Playfair Display', serif; color: #FF7900; }
        .container { max-width: 1000px; margin: 0 auto; background: #1a1a1a; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 30px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        th { color: #FF7900; font-weight: 600; background: #222; }
        tr:hover { background-color: #222; }
        
        .badge { padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #000; display: inline-block; }
        .bg-orange { background: #FF7900; }
        .bg-green { background: #00ff00; }
        .bg-gray { background: #444; color: #ccc; }
        
        .btn { background-color: #FF7900; color: #000; padding: 10px 20px; font-weight: 600; border: none; border-radius: 4px; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .btn:hover { background-color: #e66a00; }
        .btn-small { background: transparent; border: 1px solid #00ff00; color: #00ff00; padding: 8px 15px; text-decoration: none; border-radius: 4px; transition: 0.3s; font-size: 14px; display: inline-block; }
        .btn-small:hover { background: #00ff00; color: #000; }
        
        .header-flex { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 30px; }

        /* Стилі для аналітики */
        .analytics-grid { display: flex; gap: 20px; margin-top: 20px; }
        .analytics-card { flex: 1; background: #222; padding: 20px; border-radius: 6px; border: 1px solid #333; text-align: center; }
        .analytics-card h3 { margin: 0 0 10px 0; font-size: 13px; color: #aaa; text-transform: uppercase; font-family: 'Montserrat', sans-serif; letter-spacing: 1px; }
        .analytics-value { font-size: 26px; font-weight: 700; color: #FF7900; }
        .top-dishes-list { list-style: none; padding: 0; margin: 10px 0 0 0; text-align: left; }
        .top-dishes-list li { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #333; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <div>
            <h1 style="margin: 0; font-size: 28px;">Робочий термінал</h1>
            <p style="margin: 5px 0 0 0; color: #ccc;">
                Працівник: <b><?php echo htmlspecialchars($admin_name); ?></b> | 
                Посада: <span style="color: #FF7900; text-transform: uppercase;"><?php echo htmlspecialchars($role); ?></span>
            </p>
        </div>
        <div>
            <a href="admin.php?logout=1" style="color: #888; text-decoration: none; margin-right: 20px; transition: 0.3s;">Вийти з акаунта</a>
            <a href="fuulfirsttry.php" class="btn">На сайт</a>
        </div>
    </div>

    <?php if ($role === 'manager'): ?>
        <h2>Управління доставкою</h2>
        <?php
        // Менеджер бачить замовлення зі статусами 'new' (готується) та 'ready' (приготовано, чекає доставку)
        $orders_sql = "SELECT o.order_id, o.total_price, o.order_date, o.status, p.payment_method 
                       FROM orders o 
                       LEFT JOIN payments p ON o.order_id = p.order_id 
                       WHERE o.status IN ('new', 'ready') 
                       ORDER BY o.order_date DESC";
        $result = $conn->query($orders_sql);
        if ($result->num_rows > 0) {
            echo "<table><tr><th>№</th><th>Час</th><th>Сума</th><th>Оплата</th><th>Статус</th><th>Дія</th></tr>";
            while($row = $result->fetch_assoc()) {
                $pay = $row['payment_method'] == 'cash' ? 'Готівка' : 'Картка';
                
                if ($row['status'] === 'new') {
                    $status_badge = "<span class='badge bg-gray'>Готується</span>";
                    $action_cell = "<span style='color: #666; font-size: 14px;'>Чекаємо кухню</span>";
                } else {
                    $status_badge = "<span class='badge bg-orange'>На доставку</span>";
                    $action_cell = "<a href='admin.php?complete_order_id={$row['order_id']}' class='btn-small'>✔ Виконано</a>";
                }
                
                echo "<tr>
                        <td><b>#{$row['order_id']}</b></td>
                        <td>{$row['order_date']}</td>
                        <td>{$row['total_price']} ₴</td>
                        <td>$pay</td>
                        <td>{$status_badge}</td>
                        <td>{$action_cell}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: #888; margin-bottom: 40px;'>Немає нових або готових замовлень.</p>";
        }

        // Блок автоматичної аналітики ресторану
        // 1. Виторг за сьогодні
        $today_revenue_query = $conn->query("SELECT IFNULL(SUM(amount), 0) as total FROM payments WHERE DATE(payment_date) = CURDATE()");
        $today_revenue = $today_revenue_query->fetch_assoc()['total'];

        // 2. Виторг за поточний місяць
        $month_revenue_query = $conn->query("SELECT IFNULL(SUM(amount), 0) as total FROM payments WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())");
        $month_revenue = $month_revenue_query->fetch_assoc()['total'];

        // 3. Кількість виконаних замовлень за сьогодні
        $today_orders_query = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'completed' AND DATE(order_date) = CURDATE()");
        $today_orders_count = $today_orders_query->fetch_assoc()['total'];

        // 4. Топ 3 популярні страви
        $top_dishes_query = $conn->query("SELECT d.dish_name, SUM(od.quantity) as total_qty 
                                          FROM order_details od 
                                          JOIN dishes d ON od.dish_id = d.dish_id 
                                          JOIN orders o ON od.order_id = o.order_id
                                          WHERE o.status = 'completed'
                                          GROUP BY d.dish_id 
                                          ORDER BY total_qty DESC LIMIT 3");
        ?>
        
        <h2 style="margin-top: 50px;">Загальна аналітика ресторану</h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Виторг за сьогодні</h3>
                <div class="analytics-value"><?php echo $today_revenue; ?> ₴</div>
            </div>
            <div class="analytics-card">
                <h3>Продажі за місяць</h3>
                <div class="analytics-value"><?php echo $month_revenue; ?> ₴</div>
            </div>
            <div class="analytics-card">
                <h3>Виконано сьогодні</h3>
                <div class="analytics-value"><?php echo $today_orders_count; ?> шт.</div>
            </div>
            <div class="analytics-card" style="flex: 1.3;">
                <h3>Топ страв (порції)</h3>
                <ul class="top-dishes-list">
                    <?php if ($top_dishes_query && $top_dishes_query->num_rows > 0): ?>
                        <?php while($dish = $top_dishes_query->fetch_assoc()): ?>
                            <li><span><?php echo htmlspecialchars($dish['dish_name']); ?></span> <b style="color: #00ff00;"><?php echo $dish['total_qty']; ?> шт.</b></li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li style="color: #666; border: none;">Дані відсутні</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    <?php elseif ($role === 'cook'): ?>
        <h2>Кухня: Активні чеки</h2>
        <?php
        // Кухар бачить тільки замовлення зі статусом 'new'
        $cook_sql = "SELECT o.order_id, d.dish_name, od.quantity 
                     FROM orders o 
                     JOIN order_details od ON o.order_id = od.order_id 
                     JOIN dishes d ON od.dish_id = d.dish_id 
                     WHERE o.status = 'new' 
                     ORDER BY o.order_id ASC";
        $result = $conn->query($cook_sql);
        if ($result->num_rows > 0) {
            echo "<table><tr><th>№ Чека</th><th>Страва (готувати)</th><th>Кількість порцій</th><th>Дія</th></tr>";
            $current_order_id = null;
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><span class='badge bg-orange'>Чек #{$row['order_id']}</span></td>";
                echo "<td><b style='font-size: 18px;'>{$row['dish_name']}</b></td>";
                echo "<td><b style='font-size: 20px; color: #00ff00;'>{$row['quantity']} шт.</b></td>";
                
                if ($current_order_id !== $row['order_id']) {
                    echo "<td><a href='admin.php?cook_complete_id={$row['order_id']}' class='btn-small' style='border-color: #FF7900; color: #FF7900;'>✔ Приготовано</a></td>";
                    $current_order_id = $row['order_id'];
                } else {
                    echo "<td></td>";
                }
                
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: #888;'>Кухня вільна. Нових замовлень немає.</p>";
        }
        ?>

    <?php elseif ($role === 'waiter'): ?>
        <h2>Зал: Бронювання столів</h2>
        <?php
        $waiter_sql = "SELECT * FROM reservations ORDER BY reservation_date DESC LIMIT 50";
        $result = $conn->query($waiter_sql);
        if ($result && $result->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>Гість</th><th>Телефон</th><th>Дата та час</th><th>Гостей</th><th>Статус</th></tr>";
            while($row = $result->fetch_assoc()) {
                $name = $row['name'] ?? $row['guest_name'] ?? $row['first_name'] ?? 'Не вказано';
                $phone = $row['guest_phone'] ?? $row['contact'] ?? 'Не вказано';
                $date = $row['reservation_date'] ?? $row['date'] ?? 'Не вказано';
                $guests = $row['guests_count'] ?? $row['guests'] ?? $row['people'] ?? '-';
                $status = $row['status'] ?? 'pending';
                
                echo "<tr>";
                echo "<td>#{$row['reservation_id']}</td>";
                echo "<td><b>{$name}</b></td>";
                echo "<td><a href='tel:{$phone}' style='color: #FF7900; text-decoration: none;'>{$phone}</a></td>";
                echo "<td>{$date}</td>";
                echo "<td>{$guests}</td>";
                
                if ($status === 'pending') {
                    echo "<td><a href='admin.php?confirm_res_id={$row['reservation_id']}' class='btn-small' style='border-color: #FF7900; color: #FF7900;'>Передзвонити</a></td>";
                } else {
                    echo "<td><span class='badge bg-green'>Погоджено</span></td>";
                }
                
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: #888;'>Немає активних бронювань на найближчий час.</p>";
        }
        ?>
    <?php endif; ?>
</div>

</body>
</html>
<?php $conn->close(); ?>