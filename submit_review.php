<?php
session_start();
$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $conn->real_escape_string($_POST['review_text']);
    
    // Перевіряємо сесію
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL";
    $name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Анонім";

    $sql = "INSERT INTO reviews (user_id, author_name, review_text) VALUES ($user_id, '$name', '$text')";
    
    if ($conn->query($sql)) {
        header("Location: fuulfirsttry.php#reviews"); // повертаємо на блок відгуків
    }
}
?>
