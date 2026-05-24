<?php
$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565";
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$sql = "SELECT dish_name, price, category, description FROM dishes ORDER BY category, dish_name";
$result = $conn->query($sql);

if (!$result) {
    die("Помилка запиту: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Меню | М'ясо і Вогонь</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@600&display=swap');
        
        body { background-color: #111; color: #fff; font-family: 'Montserrat', sans-serif; margin: 0; }
        header { background: #171717; padding: 20px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        .logo { font-family: 'Playfair Display', serif; font-size: 24px; color: #FF7900; text-decoration: none; }
        
        nav a { transition: 0.3s; }
        nav a:hover { opacity: 0.8; }

        .menu-container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .menu-title { font-family: 'Playfair Display', serif; font-size: 48px; text-align: center; color: #FF7900; margin-bottom: 50px; }
        
        .category-title { font-family: 'Playfair Display', serif; font-size: 32px; border-bottom: 2px solid #FF7900; margin: 40px 0 20px; padding-bottom: 10px; }
        
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .menu-item { background: #1a1a1a; padding: 25px; border-radius: 8px; border: 1px solid #333; transition: 0.3s; }
        .menu-item:hover { border-color: #FF7900; transform: translateY(-3px); }
        
        .dish-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px; }
        .dish-name { font-weight: 600; font-size: 18px; color: #FF7900; }
        .dish-price { font-weight: 600; color: #fff; white-space: nowrap; margin-left: 10px; }
        .dish-desc { color: #888; font-size: 14px; line-height: 1.5; margin: 0; }

        @media (max-width: 768px) {
            .menu-grid { grid-template-columns: 1fr; }
            header { padding: 20px; }
        }
    </style>
</head>
<body>

<header>
    <a href="fuulfirsttry.php" class="logo">М'ясо і Вогонь</a>
    <nav>
        <a href="fuulfirsttry.php" style="color: #fff; text-decoration: none; margin-right: 25px;">Головна</a>
        <a href="profile.php" style="color: #FF7900; text-decoration: none; font-weight: 600;">Мій кабінет</a>
    </nav>
</header>

<div class="menu-container">
    <h1 class="menu-title">Наше Меню</h1>

    <?php 
    if ($result->num_rows > 0) {
        $current_category = "";
        while($row = $result->fetch_assoc()) {
            if ($current_category != $row['category']) {
                if ($current_category != "") {
                    echo '</div>'; 
                }
                $current_category = $row['category'];
                echo '<h2 class="category-title">' . htmlspecialchars($current_category) . '</h2>';
                echo '<div class="menu-grid">';
            }
            ?>
            <div class="menu-item">
                <div class="dish-header">
                    <span class="dish-name"><?php echo htmlspecialchars($row['dish_name']); ?></span>
                    <span class="dish-price"><?php echo number_format($row['price'], 0, '.', ' '); ?> ₴</span>
                </div>
                <p class="dish-desc"><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
            <?php
        }
        echo '</div>'; 
    } else {
        echo '<div style="text-align: center; padding: 50px;">
                <p style="color: #888; font-size: 18px;">Меню тимчасово порожнє.</p>
              </div>';
    }
    ?>
</div>

</body>
</html>
<?php $conn->close(); ?>