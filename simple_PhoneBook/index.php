<?php
require 'db_connect.php';

try {
    // get search and sort inputs
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort   = isset($_GET['sort'])   ? $_GET['sort']         : 'created_at_desc';

    // start building the SQL
    $sql = "SELECT * FROM contacts";
    $params = [];

    // if there's a search term, add a WHERE clause
    if ($search !== '') {
        $sql .= " WHERE first_name LIKE ? OR last_name LIKE ? OR phone LIKE ?";
        $like = "%{$search}%";
        $params = [$like, $like, $like];
    }

    // add ORDER BY based on the selected sort
    if ($sort === 'first_name_asc') {
        $sql .= " ORDER BY first_name ASC";
    } elseif ($sort === 'first_name_desc') {
        $sql .= " ORDER BY first_name DESC";
    } elseif ($sort === 'created_at_asc') {
        $sql .= " ORDER BY created_at ASC";
    } else {
        // default newest first
        $sql .= " ORDER BY created_at DESC";
    }

    // prepare and run the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // fetch all contacts into an array
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // show error if something goes wrong
    echo "Error fetching contacts: " . $e->getMessage();
    $contacts = [];
}

// check for messages from previous actions
$success = isset($_GET['success']);
$error   = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Simple Phone Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-2xl">
        <h1 class="text-2xl font-bold text-center mb-4">My Phone Book</h1>

        <!-- show messages -->
        <?php if ($success): ?>
            <div class="text-green-600 mb-4">Contact added successfully!</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="text-red-600 mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- search & sort form -->
        <form method="GET" class="flex gap-2 mb-6">
            <input
                type="text"
                name="search"
                placeholder="Search name or phone..."
                value="<?php echo htmlspecialchars($search); ?>"
                class="border p-2 rounded w-full"
            >
            <select name="sort" class="border p-2 rounded">
                <option value="created_at_desc" <?php if ($sort==='created_at_desc') echo 'selected'; ?>>Newest First</option>
                <option value="created_at_asc"  <?php if ($sort==='created_at_asc')  echo 'selected'; ?>>Oldest First</option>
                <option value="first_name_asc"  <?php if ($sort==='first_name_asc')  echo 'selected'; ?>>Name A-Z</option>
                <option value="first_name_desc" <?php if ($sort==='first_name_desc') echo 'selected'; ?>>Name Z-A</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 rounded hover:bg-blue-600">Apply</button>
        </form>

        <!-- add contact form -->
        <form action="add_contact.php" method="POST" class="space-y-3 mb-6">
            <input type="text" name="first_name" placeholder="First Name" required class="border p-2 rounded w-full">
            <input type="text" name="last_name"  placeholder="Last Name" required class="border p-2 rounded w-full">
            <input type="text" name="phone"      placeholder="Phone Number" required class="border p-2 rounded w-full">
            <input type="email" name="email"     placeholder="Email" class="border p-2 rounded w-full">
            <input type="text" name="address"    placeholder="Address" class="border p-2 rounded w-full">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Contact</button>
        </form>

        <!-- list of contacts -->
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">First Name</th>
                    <th class="border p-2">Last Name</th>
                    <th class="border p-2">Phone</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $c): ?>
                    <tr>
                        <td class="border p-2"><?php echo htmlspecialchars($c['first_name']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($c['last_name']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($c['phone']); ?></td>
                        <td class="border p-2">
                            <button onclick="toggleDetails('d<?php echo $c['id']; ?>')" class="text-blue-500 hover:underline mr-2">
                                Show
                            </button>
                            <a href="edit_contact.php?id=<?php echo $c['id']; ?>" class="text-blue-500 hover:underline">Edit</a> |
                            <a href="delete_contact.php?id=<?php echo $c['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                    <tr id="d<?php echo $c['id']; ?>" class="hidden bg-gray-50">
                        <td colspan="4" class="border p-2">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($c['email'] ?: 'N/A'); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($c['address'] ?: 'N/A'); ?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // simple toggle for extra info
        function toggleDetails(id) {
            var el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
</body>
</html>
