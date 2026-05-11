CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- 1. Користувачі
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'client' NOT NULL
);

-- 2. Працівники
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    position VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);

-- 3. Категорії
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_active TINYINT DEFAULT 1 NOT NULL
);

-- 4. Страви
CREATE TABLE dishes (
    dish_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    dish_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    weight_grams INT NOT NULL,
    is_available TINYINT DEFAULT 1 NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- 5. Замовлення
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    employee_id INT,
    order_date DATETIME NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'new' NOT NULL,
    delivery_address VARCHAR(255),
    payment_method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- 6. Деталі замовлення
CREATE TABLE order_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (dish_id) REFERENCES dishes(dish_id)
);

-- 7. Бронювання
CREATE TABLE reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    employee_id INT,
    reservation_date DATETIME NOT NULL,
    guests_count INT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending' NOT NULL,
    table_number INT,
    special_requests TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- 8. Оплати
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    payment_date DATETIME NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'completed' NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Наповнення даними
INSERT INTO users (first_name, last_name, email, phone, password_hash, role) VALUES
('Олена', 'Коваленко', 'olena@email.com', '0981112233', 'hash1', 'client'),
('Андрій', 'Шевченко', 'andriy@email.com', '0972223344', 'hash2', 'client'),
('Марія', 'Бойко', 'maria@email.com', '0633334455', 'hash3', 'client');

INSERT INTO employees (first_name, last_name, position, phone) VALUES
('Іван', 'Петров', 'Адміністратор', '0509998877'),
('Сергій', 'Мельник', 'Курєр', '0678887766');

INSERT INTO categories (category_name, description, is_active) VALUES
('Гарячі страви', 'Мясні та рибні основні страви', 1),
('Салати', 'Свіжі та теплі салати', 1),
('Напої', 'Холодні та гарячі напої', 1);

INSERT INTO dishes (category_id, dish_name, description, price, weight_grams, is_available) VALUES
(1, 'Стейк Рибай', 'Яловичина, спеції', 450.00, 300, 1),
(1, 'Лосось на грилі', 'Філе лосося, лимон', 380.00, 250, 1),
(2, 'Цезар з куркою', 'Куряче філе, бекон, пармезан', 220.00, 250, 1),
(3, 'Лимонад', 'Лимон, мята, лід', 80.00, 400, 1);

INSERT INTO orders (user_id, employee_id, order_date, total_price, status, delivery_address, payment_method) VALUES
(1, 2, '2026-04-10 14:30:00', 750.00, 'completed', 'вул. Шевченка, 10', 'Картка'),
(2, 2, '2026-05-12 18:45:00', 460.00, 'completed', 'вул. Франка, 5', 'Готівка');

INSERT INTO order_details (order_id, dish_id, quantity, unit_price, subtotal) VALUES
(1, 1, 1, 450.00, 450.00),
(1, 3, 1, 220.00, 220.00),
(1, 4, 1, 80.00, 80.00),
(2, 2, 1, 380.00, 380.00),
(2, 4, 1, 80.00, 80.00);

INSERT INTO reservations (user_id, employee_id, reservation_date, guests_count, status, table_number) VALUES
(3, 1, '2026-06-01 19:00:00', 4, 'confirmed', 5),
(1, 1, '2026-06-02 20:00:00', 2, 'pending', 2);

INSERT INTO payments (order_id, user_id, payment_date, amount, payment_method, status) VALUES
(1, 1, '2026-04-10 14:35:00', 750.00, 'Картка', 'completed'),
(2, 2, '2026-05-12 18:50:00', 460.00, 'Готівка', 'completed');

-- Інформація про коієнтів
USE restaurant_db;
SELECT users.first_name, users.last_name, users.phone, orders.order_date, orders.total_price, orders.status
FROM users
JOIN orders ON users.user_id = orders.user_id;
-- Обчислення доходів ресторану за місяцями
USE restaurant_db;
SELECT EXTRACT(MONTH FROM payment_date) AS month, SUM(amount) AS total_income
FROM payments
WHERE status = 'completed'
GROUP BY EXTRACT(MONTH FROM payment_date)
ORDER BY month;
-- Аналіз популярності страв у меню
USE restaurant_db;
SELECT dishes.dish_name, categories.category_name, SUM(order_details.quantity) AS total_sold
FROM dishes
JOIN order_details ON dishes.dish_id = order_details.dish_id
JOIN categories ON dishes.category_id = categories.category_id
GROUP BY dishes.dish_name, categories.category_name
ORDER BY total_sold DESC;
-- Зміна статусу бронювання
USE restaurant_db;
UPDATE reservations SET status = 'cancelled' WHERE reservation_id = 1;
SELECT reservation_id, user_id, reservation_date, status FROM reservations WHERE reservation_id = 1;
