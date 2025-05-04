<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

    try {
        // Prepare and execute the SQL statement to insert the new contact
        $stmt = $pdo->prepare("INSERT INTO contacts (first_name, last_name, phone, email, address, category_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $phone, $email, $address, $category_id]);

        // Redirect back to the main page with success message
        header('Location: index.php?success=1');
    } catch (PDOException $e) {
        // Redirect with error message
        header('Location: index.php?error=' . urlencode('Failed to add contact: ' . $e->getMessage()));
    }
    exit;
}
?>