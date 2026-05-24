<?php
// Параметри підключення до бази
$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Отримуємо дані
    $guest_name = $conn->real_escape_string($_POST['guest_name']);
    $guest_phone = $conn->real_escape_string($_POST['guest_phone']);
    $guests_count = (int)$_POST['guests_count'];
    
    // Форматуємо дату
    $raw_date = $_POST['reservation_date'];
    $reservation_date = str_replace('T', ' ', $raw_date);
    if (strlen($reservation_date) == 16) {
        $reservation_date .= ':00';
    }

    $sql = "INSERT INTO reservations (user_id, guest_name, guest_phone, reservation_date, guests_count, status) 
            VALUES (NULL, '$guest_name', '$guest_phone', '$reservation_date', $guests_count, 'pending')";

    // Виводимо HTML-сторінку з дизайном
    echo "<!DOCTYPE html>
    <html lang='uk'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Статус бронювання | М'ясо і Вогонь</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@600&display=swap');
            body { 
                background-color: #111; 
                color: #fff; 
                font-family: 'Montserrat', sans-serif; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
                margin: 0; 
                background: linear-gradient(rgba(17, 17, 17, 0.9), rgba(17, 17, 17, 0.9)), url('https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1920&q=80') center/cover;
            }
            .message-box { 
                background: #222; 
                padding: 50px; 
                border-radius: 8px; 
                text-align: center; 
                border: 1px solid #333; 
                max-width: 500px; 
                box-shadow: 0 10px 30px rgba(0,0,0,0.8); 
            }
            h1 { font-family: 'Playfair Display', serif; color: #FF7900; margin-top: 0; margin-bottom: 20px; font-size: 32px; }
            p { color: #ccc; line-height: 1.6; margin-bottom: 30px; font-size: 16px; }
            .btn { 
                background-color: #FF7900; 
                color: #000; 
                padding: 12px 30px; 
                text-decoration: none; 
                font-weight: 600; 
                border-radius: 4px; 
                transition: 0.3s; 
                display: inline-block; 
            }
            .btn:hover { background-color: #e66a00; }
        </style>
    </head>
    <body>
        <div class='message-box'>";

    if ($conn->query($sql) === TRUE) {
        $formatted_date = date('d.m.Y о H:i', strtotime($reservation_date));
        echo "<h1>Стіл заброньовано</h1>";
        echo "<p>Дякуємо, <b>" . htmlspecialchars($guest_name) . "</b>. Ваша заявка прийнята. Ми чекаємо на вас <b>" . $formatted_date . "</b>. Наш адміністратор зв'яжеться з вами за номером " . htmlspecialchars($guest_phone) . " для підтвердження деталей.</p>";
        echo "<a href='fuulfirsttry.php' class='btn'>Повернутися на головну</a>";
    } else {
        echo "<h1 style='color: #ff4444;'>Виникла помилка</h1>";
        echo "<p>На жаль, не вдалося обробити ваше бронювання. Будь ласка, спробуйте ще раз або зателефонуйте нам.</p>";
        echo "<a href='fuulfirsttry.php' class='btn'>Повернутися на головну</a>";
    }

    echo "    </div>
    </body>
    </html>";
}

$conn->close();
?>