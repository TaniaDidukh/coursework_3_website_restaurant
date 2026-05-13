<?php
// Цей рядок виправляє "каракулі"
header('Content-Type: text/html; charset=utf-8');

$servername = "10.211.55.4"; 
$username = "taniad";        
$password = "380676068565"; 
$dbname = "restaurant_db";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Якщо сервер вимкнений, ми все одно покажемо гарний текст для звіту
    die("<h2 style='color: red; font-family: sans-serif;'>Помилка підключення до бази. Перевір, чи запущена Ubuntu!</h2>");
}

$f_name = $_POST['first_name'];
$l_name = $_POST['last_name'];
$email = $_POST['email'];
$comment = $_POST['user_comment'];
$phone = $_POST['phone'];
$date = $_POST['booking_date'];

$sql = "INSERT INTO web_bookings (first_name, last_name, email, comment, phone, booking_date)
VALUES ('$f_name', '$l_name', '$email', '$comment', '$phone', '$date')";

if ($conn->query($sql) === TRUE) {
    echo "<div style='background: #111; color: white; padding: 50px; text-align: center; font-family: sans-serif;'>
            <h2 style='color: #FF7900;'>Бронювання успішне!</h2>
            <p>Дані передано в MySQL (web_bookings).</p>
            <a href='fuulfirsttry.html' style='color: #FF7900;'>Повернутися на сайт</a>
          </div>";
} else {
    echo "Помилка: " . $conn->error;
}
$conn->close();
?>
