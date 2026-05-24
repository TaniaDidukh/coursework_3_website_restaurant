

<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "10.211.55.4";
$username = "taniad";
$password = "380676068565"; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];

    // 1. Пошук у таблиці персоналу (employees)
    $emp_result = $conn->query("SELECT * FROM employees WHERE email = '$email'");

    if ($emp_result && $emp_result->num_rows > 0) {
        $emp = $emp_result->fetch_assoc();
        
        // Перевірка пароля працівника
        if (password_verify($pass, $emp['password_hash']) || $pass === $emp['password_hash']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $emp['first_name'];
            $_SESSION['admin_role'] = $emp['position']; // manager, cook, waiter
            
            header("Location: admin.php");
            exit();
        } else {
            echo "<h3 style='color:red; text-align:center;'>Неправильний пароль!</h3>";
            exit();
        }
    }

    // 2. Пошук у таблиці клієнтів (users), якщо email не знайдено серед працівників
    $user_result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        
        // Перевірка пароля клієнта
        if (password_verify($pass, $user['password_hash']) || $pass === $user['password_hash']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['phone'] = $user['phone'];
            
            header("Location: profile.php");
            exit();
        } else {
            echo "<h3 style='color:red; text-align:center;'>Неправильний пароль!</h3>";
            exit();
        }
    }

    // 3. Якщо email відсутній в обох таблицях
    echo "<h3 style='color:red; text-align:center;'>Користувача не знайдено!</h3>";
}
$conn->close();
?>