<?php
// Налаштування підключення (використовуємо твої дані з Workbench)
$servername = "10.211.55.4"; 
$username = "taniad";        
$password = "380676068565"; // пароль, у Workbench
$dbname = "meat_and_fire";   

// Створюємо з'єднання
$conn = new mysqli($servername, $username, $password, $dbname);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримуємо дані з форми
$f_name = $_POST['first_name'];
$l_name = $_POST['last_name'];
$email = $_POST['email'];
$comment = $_POST['user_comment'];
$phone = $_POST['phone'];
$date = $_POST['booking_date'];

// SQL-запит на додавання запису в нову таблицю
$sql = "INSERT INTO web_bookings (first_name, last_name, email, comment, phone, booking_date)
VALUES ('$f_name', '$l_name', '$email', '$comment', '$phone', '$date')";


if ($conn->query($sql) === TRUE) {
    echo "<div style='background: #111; color: white; padding: 50px; text-align: center; font-family: Montserrat;'>
            <h2 style='color: #FF7900;'>Бронювання успішне!</h2>
            <p>Ваші дані збережені в базі даних ресторану.</p>
            <a href='fuulfirsttry.html' style='color: #FF7900;'>Повернутися на сайт</a>
          </div>";
} else {
    echo "Помилка: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
