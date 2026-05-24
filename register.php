<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("10.211.55.4", "taniad", "380676068565", "restaurant_db");
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $city = $conn->real_escape_string($_POST['city']);
    $street = $conn->real_escape_string($_POST['street']);
    $house = $conn->real_escape_string($_POST['house_number']);
    $flat = $conn->real_escape_string($_POST['apartment_number']);

    // Вилучено колонку role та значення 'client', оскільки таблиця users тепер виключно для клієнтів
    $sql = "INSERT INTO users (first_name, last_name, email, phone, password_hash, city, street, house_number, apartment_number) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$password_hash', '$city', '$street', '$house', '$flat')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $first_name;
        header("Location: profile.php");
        exit();
    } else {
        echo "Помилка бази даних: " . $conn->error;
    }
}
$conn->close();
?>