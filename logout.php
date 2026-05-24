<?php
session_start();

// Очищаємо всі дані сесії
$_SESSION = array();

// Знищуємо сесію
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// ПЕРЕКОНАЙСЯ, ЩО НАЗВА ФАЙЛУ НИЖЧЕ ПРАВИЛЬНА (твоя головна сторінка)
header("Location: fuulfirsttry.php"); 
exit();
?>