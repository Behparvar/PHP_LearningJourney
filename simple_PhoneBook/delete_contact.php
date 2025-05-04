<?php
require 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?error=' . urlencode('Invalid contact ID'));
    exit;
}

$contact_id = $_GET['id'];

try {
    // Prepare and execute the SQL statement to delete the contact
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$contact_id]);

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        header('Location: index.php?success=1');
    } else {
        header('Location: index.php?error=' . urlencode('Contact not found'));
    }
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('Failed to delete contact: ' . $e->getMessage()));
}
exit;
?>