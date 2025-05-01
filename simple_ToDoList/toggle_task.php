<?php
require 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
?>