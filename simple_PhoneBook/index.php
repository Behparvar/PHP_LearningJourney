<?php
require 'db_connect.php';

try {
    // Initialize success variable
    $success = false;

    // Check for success message from GET parameter
    if (isset($_GET['success'])) {
        $success = true;
    }

    // Get search, category, and sort inputs
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at_desc';

    // Fetch all categories for dropdown
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Start building the SQL for contacts
    $sql = "SELECT contacts.*, categories.name AS category_name 
            FROM contacts 
            LEFT JOIN categories ON contacts.category_id = categories.id";
    $params = [];

    // Add WHERE clauses for search and category
    $where = [];
    if ($search !== '') {
        $where[] = "(first_name LIKE ? OR last_name LIKE ? OR phone LIKE ?)";
        $like = "%{$search}%";
        $params = [$like, $like, $like];
    }
    if ($category_id !== '' && $category_id !== 'all') {
        $where[] = "contacts.category_id = ?";
        $params[] = $category_id;
    }
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    // Add ORDER BY based on the selected sort
    if ($sort === 'first_name_asc') {
        $sql .= " ORDER BY first_name ASC";
    } elseif ($sort === 'first_name_desc') {
        $sql .= " ORDER BY first_name DESC";
    } elseif ($sort === 'created_at_asc') {
        $sql .= " ORDER BY created_at ASC";
    } else {
        // Default newest first
        $sql .= " ORDER BY created_at DESC";
    }

    // Prepare and run the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Fetch all contacts into an array
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Show error if something goes wrong
    echo "Error fetching contacts: " . $e->getMessage();
    $contacts = [];
    $categories = [];
}

// Check for error message
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Phone Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center min-h-screen py-8">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Company Phone Directory</h1>

        <!-- Show messages -->
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">Contact added successfully!</span>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Search & sort form -->
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Search Contacts</h2>
        <form method="GET" class="grid grid-cols-4 gap-4 mb-8">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Search name or phone..."
                    value="<?php echo htmlspecialchars($search); ?>"
                    class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="category_id" name="category_id" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" <?php if ($category_id === 'all' || $category_id === '') echo 'selected'; ?>>All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($category_id == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select id="sort" name="sort" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="created_at_desc" <?php if ($sort==='created_at_desc') echo 'selected'; ?>>Newest First</option>
                    <option value="created_at_asc"  <?php if ($sort==='created_at_asc')  echo 'selected'; ?>>Oldest First</option>
                    <option value="first_name_asc"  <?php if ($sort==='first_name_asc')  echo 'selected'; ?>>Name A-Z</option>
                    <option value="first_name_desc" <?php if ($sort==='first_name_desc') echo 'selected'; ?>>Name Z-A</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-150 w-full">Apply</button>
            </div>
        </form>

        <!-- Add contact form -->
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Add New Contact</h2>
        <form action="add_contact.php" method="POST" class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" id="first_name" name="first_name" placeholder="First Name" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Phone Number" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" id="address" name="address" placeholder="Address" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="category_id_add" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="category_id_add" name="category_id" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-span-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-150 w-full">Add Contact</button>
            </div>
        </form>

        <!-- List of contacts -->
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Contact List</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border p-4 text-left">First Name</th>
                        <th class="border p-4 text-left">Last Name</th>
                        <th class="border p-4 text-left">Phone</th>
                        <th class="border p-4 text-left">Category</th>
                        <th class="border p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c): ?>
                        <tr class="even:bg-gray-50 hover:bg-gray-100">
                            <td class="border p-4"><?php echo htmlspecialchars($c['first_name']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($c['last_name']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($c['phone']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($c['category_name'] ?: 'None'); ?></td>
                            <td class="border p-4">
                                <div class="flex space-x-2">
                                    <button onclick="toggleDetails('d<?php echo $c['id']; ?>')" class="bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition duration-150">Show</button>
                                    <a href="edit_contact.php?id=<?php echo $c['id']; ?>" class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-200 transition duration-150">Edit</a>
                                    <a href="delete_contact.php?id=<?php echo $c['id']; ?>" onclick="return confirm('Are you sure?')" class="bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition duration-150">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <tr id="d<?php echo $c['id']; ?>" class="hidden bg-gray-100">
                            <td colspan="5" class="border p-4">
                                <div class="space-y-2">
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($c['email'] ?: 'N/A'); ?></p>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($c['address'] ?: 'N/A'); ?></p>
                                    <p><strong>Category:</strong> <?php echo htmlspecialchars($c['category_name'] ?: 'N/A'); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Simple toggle for extra info
        function toggleDetails(id) {
            var el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
</body>
</html>