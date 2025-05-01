<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['task_name'])) {
        $task_name = $_POST['task_name'];

        $query = "INSERT INTO tasks (task_name) VALUES (?)";
        $statement = $pdo->prepare($query);

        $statement->execute([$task_name]);
    }
}

header('Location: index.php');
exit();
?>