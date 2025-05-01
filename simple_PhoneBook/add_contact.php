<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Prepare and execute the SQL statement to insert the new contact
    $stmt = $pdo->prepare("INSERT INTO contacts (first_name, last_name, phone, email, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $phone, $email, $address]);

    // Redirect back to the main page after adding the contact
    header('Location: index.php');
    exit;
}
?>