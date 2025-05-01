<!-- first you should create a database and table in your MySQL server. -->
<!-- you need to add username and password for your database in db_connect.php file. -->
<?php
$host = 'localhost';
$dbname = 'todo_db';
$username = 'root';
$password = 'ali.k1990'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>