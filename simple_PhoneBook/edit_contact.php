<?php
require 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?error=' . urlencode('Invalid contact ID'));
    exit;
}

$contact_id = $_GET['id'];

try {
    // Fetch the contact to edit
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$contact_id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        header('Location: index.php?error=' . urlencode('Contact not found'));
        exit;
    }

    // Fetch all categories for dropdown
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

        // Prepare and execute the SQL statement to update the contact
        $stmt = $pdo->prepare("UPDATE contacts SET first_name = ?, last_name = ?, phone = ?, email = ?, address = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $phone, $email, $address, $category_id, $contact_id]);

        // Redirect back to the main page with success message
        header('Location: index.php?success=1');
        exit;
    }
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('Error: ' . $e->getMessage()));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center min-h-screen py-8">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Edit Contact</h1>

        <!-- Edit contact form -->
        <form action="edit_contact.php?id=<?php echo $contact_id; ?>" method="POST" class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700dledmb-1">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($contact['first_name']); ?>" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($contact['last_name']); ?>" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($contact['address']); ?>" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="category_id" name="category_id" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($contact['category_id'] == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-span-2 flex space-x-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-150 w-full">Update Contact</button>
                <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-150 w-full text-center">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>