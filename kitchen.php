<?php
session_start();

// Перевірка доступу (пускаємо тільки кухаря або менеджера)
if (!isset($_SESSION['admin_logged_in']) || !in_array($_SESSION['admin_role'], ['cook', 'manager'])) {
    die("<h2 style='color:#ff6b6b; text-align:center; margin-top:50px; font-family:sans-serif;'>Доступ заборонено. Ця сторінка тільки для персоналу кухні.</h2>");
}

$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// Якщо кухар натиснув кнопку "Готово"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Змінюємо статус замовлення в базі на 'completed'
    $conn->query("UPDATE orders SET status = 'completed' WHERE order_id = $order_id");
    
    // Перезавантажуємо сторінку, щоб замовлення зникло з екрана
    header("Location: kitchen.php");
    exit();
}

// Витягуємо всі замовлення зі статусами 'new' або 'in_progress'
$orders_sql = "SELECT order_id, order_date FROM orders WHERE status IN ('new', 'in_progress') ORDER BY order_date ASC";
$orders_result = $conn->query($orders_sql);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Екран кухні | М'ясо і Вогонь</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
        body { background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; padding: 20px; margin: 0; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        h1 { color: #FF7900; margin: 0; font-size: 24px; }
        .user-info { color: #888; }
        .user-info b { color: #fff; }
        .logout-btn { color: #FF7900; text-decoration: none; border: 1px solid #FF7900; padding: 5px 15px; border-radius: 4px; transition: 0.3s; }
        .logout-btn:hover { background: #FF7900; color: #000; }
        
        .orders-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .order-card { background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); border-top: 4px solid #FF7900; }
        .order-header { display: flex; justify-content: space-between; border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 15px; font-weight: bold; font-size: 18px; }
        
        .dish-list { list-style: none; padding: 0; margin: 0 0 20px 0; }
        .dish-list li { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #333; font-size: 16px; }
        .dish-qty { background: #333; color: #FF7900; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
        
        .btn-ready { background-color: #28a745; color: #fff; border: none; padding: 12px; width: 100%; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: 0.3s; }
        .btn-ready:hover { background-color: #218838; }
        
        .empty-msg { text-align: center; color: #666; font-size: 20px; margin-top: 80px; grid-column: 1 / -1; }
    </style>
</head>
<body>

    <div class="header-bar">
        <h1>Екран кухні (Активні замовлення)</h1>
        <div class="user-info">
            Шеф: <b><?php echo htmlspecialchars($_SESSION['admin_name']); ?></b> 
            | <a href="fuulfirsttry.php" class="logout-btn">На головну</a>
        </div>
    </div>
    
    <div class="orders-grid">
        <?php if ($orders_result->num_rows > 0): ?>
            <?php while($order = $orders_result->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Замовлення #<?php echo $order['order_id']; ?></span>
                        <span style="color: #888; font-size: 14px;"><?php echo date('H:i', strtotime($order['order_date'])); ?></span>
                    </div>
                    <ul class="dish-list">
                        <?php
                        $oid = $order['order_id'];
                        // Витягуємо назви страв та їх кількість для конкретного замовлення
                        $details_sql = "SELECT d.dish_name, od.quantity FROM order_details od JOIN dishes d ON od.dish_id = d.dish_id WHERE od.order_id = $oid";
                        $details_result = $conn->query($details_sql);
                        
                        while($item = $details_result->fetch_assoc()) {
                            echo "<li><span>" . htmlspecialchars($item['dish_name']) . "</span> <span class='dish-qty'>x" . $item['quantity'] . "</span></li>";
                        }
                        ?>
                    </ul>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="complete_order" class="btn-ready">✔ Приготовано</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-msg">Нових замовлень немає. Кухня вільна! 👨‍🍳</div>
        <?php endif; ?>
    </div>

</body>
</html>
<?php $conn->close(); ?>