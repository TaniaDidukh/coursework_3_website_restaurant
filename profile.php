<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565";
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$sql_user = "SELECT first_name, last_name, email, city, street, house_number, apartment_number, loyalty_points FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

$sql_orders = "
    SELECT 
        o.order_id, 
        o.order_date, 
        o.total_price, 
        GROUP_CONCAT(d.dish_name SEPARATOR ', ') AS ordered_dishes
    FROM orders o
    JOIN payments p ON o.order_id = p.order_id
    JOIN order_details od ON o.order_id = od.order_id
    JOIN dishes d ON od.dish_id = d.dish_id
    WHERE p.user_id = ?
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders_result = $stmt_orders->get_result();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Особистий кабінет | М'ясо і Вогонь</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@600&display=swap');
        body { background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: 50px auto; background: #1a1a1a; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        h1, h2 { font-family: 'Playfair Display', serif; color: #FF7900; }
        .info-block { background: #222; padding: 20px; border-radius: 6px; margin-bottom: 30px; }
        .bonus-card { background: linear-gradient(135deg, #FF7900, #b35500); padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px; }
        .bonus-card h3 { margin: 0; font-size: 24px; color: #000; }
        .bonus-card .points { font-size: 48px; font-weight: bold; color: #fff; line-height: 1; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #444; }
        th { color: #FF7900; }
        .btn-logout { background-color: #333; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px;}
        .btn-logout:hover { background-color: #555; }
    </style>
</head>
<body>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Особистий кабінет</h1>
        <a href="fuulfirsttry.php" style="color: #FF7900; text-decoration: none;">На головну</a>
    </div>

    <div class="bonus-card">
        <h3>Ваші бонуси</h3>
        <div class="points"><?php echo htmlspecialchars($user_data['loyalty_points'] ?? 0); ?> ₴</div>
        <div style="color: #000; font-size: 14px;">10% кешбеку з кожного замовлення</div>
    </div>

    <div class="info-block">
        <h2>Дані доставки</h2>
        <p><strong>Ім'я:</strong> <?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><strong>Адреса:</strong> м. <?php echo htmlspecialchars($user_data['city'] ?? '—'); ?>, 
            вул. <?php echo htmlspecialchars($user_data['street'] ?? '—'); ?>, 
            буд. <?php echo htmlspecialchars($user_data['house_number'] ?? '—'); ?>, 
            кв. <?php echo htmlspecialchars($user_data['apartment_number'] ?? '—'); ?></p>
    </div>

    <h2>Історія замовлень</h2>
    <?php if ($orders_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Дата</th>
                <th>№ Замовлення</th>
                <th>Страви</th>
                <th>Сума</th>
            </tr>
            <?php while($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d.m.Y H:i', strtotime($order['order_date'])); ?></td>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td style="color: #bbb;"><?php echo htmlspecialchars($order['ordered_dishes']); ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($order['total_price'], 0, '.', ' '); ?> ₴</td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Ви ще не здійснювали замовлень.</p>
    <?php endif; ?>

    <a href="logout.php" class="btn-logout">Вийти з акаунта</a>
</div>

</body>
</html>
<?php 
$stmt->close();
$stmt_orders->close();
$conn->close(); 
?>